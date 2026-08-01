<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\SeoRecommendation;
use App\Models\PageSeoContent;

class AdminSeoController extends Controller
{
    public function analyze(Request $request)
    {
        $request->validate([
            'page_path' => 'required|string',
            'target_keyword' => 'nullable|string'
        ]);

        $pagePath = $request->page_path;
        $keyword = $request->target_keyword;
        $url = url($pagePath);

        // Coba ambil seluruh isi halaman saat ini agar AI punya konteks penuh
        $pageHtml = "";
        try {
            $pageHtml = Http::timeout(15)->get($url)->body();
        } catch (\Exception $e) {}

        $safeHtml = substr($pageHtml, 0, 8000);

        $keywordPrompt = $keyword ? "I specifically want to target the keyword '$keyword', so evaluate if the page is optimized for it." : "Please evaluate the page's current SEO holistically and suggest target keywords if necessary.";

        $prompt = "You are an expert SEO Consultant. I have a webpage at '$url'. $keywordPrompt
Here is a snippet of the current HTML source code of the page:
```html
$safeHtml
```

Please evaluate the page's current SEO and generate a comprehensive list of actionable SEO recommendations. 
IMPORTANT RULES YOU MUST FOLLOW:
1. The content MUST be in BAHASA INDONESIA.
2. Provide a dynamic list of recommendations. You can use standard categories like 'Keyword Target Baru', 'FAQ', 'Backlink', 'Internal Link', 'Update Heading', 'Optimasi Gambar', 'Page Speed', or INVENT NEW CATEGORIES if you find specific opportunities.
3. You MUST output ONLY a valid JSON ARRAY of objects, with no markdown formatting.

Format the output EXACTLY like this JSON array:
[
    {
        \"category\": \"Nama Kategori (contoh: Keyword Target Baru / Optimasi Gambar)\",
        \"research_finding\": \"Fakta/riset tren SEO saat ini terkait hal ini\",
        \"current_condition\": \"Kondisi yang Anda temukan di kode HTML web ini\",
        \"impact\": \"Dampak negatif jika dibiarkan atau dampak positif jika diperbaiki\",
        \"recommendation_text\": \"Saran konkret apa yang harus dilakukan oleh tim kami\"
    }
]";

        try {
            $response = Http::timeout(300)->post('http://scanyuk-ollama:11434/api/generate', [
                'model' => 'llama3', 
                'prompt' => $prompt,
                'stream' => false,
                'format' => 'json'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $resultText = $data['response'] ?? '[]';
                
                preg_match('/\[.*\]/s', $resultText, $matches);
                if (!empty($matches)) {
                    $resultText = $matches[0];
                }
                
                $parsed = json_decode($resultText, true);

                if ($parsed && is_array($parsed)) {
                    $createdItems = [];
                    foreach ($parsed as $rec) {
                        if (isset($rec['category']) && isset($rec['recommendation_text'])) {
                            $createdItems[] = SeoRecommendation::create([
                                'page_path' => $pagePath,
                                'category' => $rec['category'],
                                'research_finding' => $rec['research_finding'] ?? '',
                                'current_condition' => $rec['current_condition'] ?? '',
                                'impact' => $rec['impact'] ?? '',
                                'recommendation_text' => $rec['recommendation_text'],
                                'status' => 'pending'
                            ]);
                        }
                    }
                    return response()->json(['success' => true, 'data' => $createdItems, 'message' => 'Berhasil mendapatkan ' . count($createdItems) . ' rekomendasi.']);
                }
                
                return response()->json(['success' => false, 'message' => 'Gagal mendapatkan response array JSON valid dari AI.']);
            }

            return response()->json(['success' => false, 'message' => 'Gagal mendapat response HTTP 200 dari Ollama. Status: ' . $response->status()]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal terhubung ke AI: ' . $e->getMessage()]);
        }
    }

    public function apply(Request $request, $id)
    {
        $recommendation = SeoRecommendation::findOrFail($id);
        $recommendation->update(['status' => 'applied']);

        return response()->json(['success' => true, 'message' => 'Rekomendasi SEO ditandai sebagai selesai.']);
    }

    public function getRecommendations()
    {
        $recs = SeoRecommendation::latest()->get();
        return response()->json($recs);
    }
}
