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
            'target_keyword' => 'required|string'
        ]);

        $pagePath = $request->page_path;
        $keyword = $request->target_keyword;

        $url = url($pagePath);
        
        $prompt = "You are an expert SEO Consultant. I have a webpage at '$url' and I want to target the keyword '$keyword'.
Please generate a comprehensive SEO recommendation report.
IMPORTANT: The content of your recommendations MUST be in BAHASA INDONESIA.
You MUST output ONLY a valid JSON object with the following exact structure, no markdown:
{
    \"overall_score\": 75,
    \"meta_title\": \"Saran Judul Meta (max 60 chars, in Indonesian)\",
    \"meta_description\": \"Saran Deskripsi Meta (max 160 chars, in Indonesian)\",
    \"h1_heading\": \"Saran Heading H1 yang mengandung keyword (in Indonesian)\",
    \"faq_schema\": [
        {\"question\": \"Pertanyaan FAQ 1\", \"answer\": \"Jawaban FAQ 1\"}
    ],
    \"backlink_strategy\": \"Saran mencari backlink untuk keyword ini (in Indonesian)\",
    \"internal_link_strategy\": \"Saran untuk tautan internal (in Indonesian)\",
    \"image_optimization\": \"Saran untuk alt text dan optimasi gambar (in Indonesian)\",
    \"page_speed\": \"Saran untuk mempercepat loading halaman (in Indonesian)\"
}";

        try {
            $response = Http::timeout(300)->post('http://scanyuk-ollama:11434/api/generate', [
                'model' => 'llama3', 
                'prompt' => $prompt,
                'stream' => false,
                'format' => 'json'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $resultText = $data['response'] ?? '{}';
                
                // Coba ekstrak JSON dengan regex jika terbungkus teks lain
                preg_match('/\{.*\}/s', $resultText, $matches);
                if (!empty($matches)) {
                    $resultText = $matches[0];
                }
                
                $parsed = json_decode($resultText, true);

                if ($parsed) {
                    $recommendation = SeoRecommendation::create([
                        'page_path' => $pagePath,
                        'target_keyword' => $keyword,
                        'overall_score' => $parsed['overall_score'] ?? 0,
                        'recommendations' => $parsed,
                        'status' => 'pending',
                        'manual_status' => 'pending',
                        'ai_type' => 'manual'
                    ]);

                    return response()->json(['success' => true, 'data' => $recommendation]);
                }
                
                return response()->json(['success' => false, 'message' => 'Gagal mendapatkan response valid dari AI. Respons mentah: ' . $data['response']]);
            }

            return response()->json(['success' => false, 'message' => 'Gagal mendapat response HTTP 200 dari Ollama. Status: ' . $response->status() . ' Body: ' . $response->body()]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal terhubung ke AI: ' . $e->getMessage()]);
        }
    }

    public function apply(Request $request, $id)
    {
        $recommendation = SeoRecommendation::findOrFail($id);
        
        $recData = $recommendation->recommendations;

        $pageSeo = PageSeoContent::firstOrNew(['page_path' => $recommendation->page_path]);
        
        if (isset($recData['meta_title'])) $pageSeo->meta_title = $recData['meta_title'];
        if (isset($recData['meta_description'])) $pageSeo->meta_description = $recData['meta_description'];
        if (isset($recData['h1_heading'])) $pageSeo->h1_heading = $recData['h1_heading'];
        if (isset($recData['faq_schema'])) $pageSeo->faq_schema = $recData['faq_schema'];
        
        $pageSeo->save();

        $recommendation->update(['status' => 'applied']);

        return response()->json(['success' => true, 'message' => 'Rekomendasi SEO berhasil diterapkan ke website!']);
    }
    public function updateManualStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:pending,proses,selesai']);
        $recommendation = SeoRecommendation::findOrFail($id);
        $recommendation->update(['manual_status' => $request->status]);
        return response()->json(['success' => true, 'message' => 'Status manual berhasil diperbarui.']);
    }

    public function getRecommendations()
    {
        $recs = SeoRecommendation::latest()->get();
        return response()->json($recs);
    }
}
