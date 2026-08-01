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

        // Bersihkan HTML dari elemen panjang yang tidak berguna untuk AI
        $cleanHtml = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $pageHtml);
        $cleanHtml = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $cleanHtml);
        $cleanHtml = preg_replace('/<svg\b[^>]*>(.*?)<\/svg>/is', '', $cleanHtml);
        $cleanHtml = preg_replace('/<!--(.*?)-->/is', '', $cleanHtml);
        $cleanHtml = preg_replace('/\s(class|style|id|data-[^=]*)=["\'][^"\']*["\']/is', '', $cleanHtml);
        $cleanHtml = preg_replace('/\s+/', ' ', $cleanHtml);

        $safeHtml = substr(trim($cleanHtml), 0, 4000);

        $keywordPrompt = $keyword ? "Saya secara spesifik ingin menargetkan keyword '$keyword', jadi tolong evaluasi apakah halaman ini sudah optimal untuk keyword tersebut." : "Tolong evaluasi SEO halaman ini secara holistik dan berikan saran keyword target jika diperlukan.";

        $prompt = "Anda adalah Konsultan SEO Senior. Berikut adalah kerangka HTML dari halaman '$url'. $keywordPrompt
Berikut ini cuplikan dari source code HTML halamannya:
```html
$safeHtml
```

Tugas Anda adalah mengevaluasi SEO halaman tersebut dan memberikan 2-3 rekomendasi paling krusial.
ATURAN WAJIB:
1. Seluruh jawaban WAJIB menggunakan BAHASA INDONESIA yang detail dan mudah dipahami.
2. Output HANYA boleh berupa JSON ARRAY. Jangan ada teks markdown atau penjelasan di luar JSON.
3. Maksimal 3 rekomendasi saja.

Format JSON WAJIB seperti ini:
[
    {
        \"category\": \"Nama Kategori (Contoh: Optimasi Kecepatan, Konten, Meta Tag)\",
        \"research_finding\": \"Sebutkan temuan riset dan sumber atau alasannya (contoh: 'Menurut algoritma Google terbaru...')\",
        \"current_condition\": \"Sebutkan secara spesifik bagian/tag HTML mana di web ScanYuk yang bermasalah\",
        \"impact\": \"Jelaskan detail apa dampak buruknya saat ini dan dampak positifnya jika diperbaiki\",
        \"recommendation_text\": \"Jelaskan panduan teknis yang harus dilakukan programmer, dan hasil akhir yang diharapkan (contoh: 'Ubah X menjadi Y, agar website bisa masuk halaman pertama pencarian')\"
    }
]";

        try {
            $response = Http::timeout(1200)->post('http://scanyuk-ollama:11434/api/generate', [
                'model' => 'llama3', 
                'prompt' => $prompt,
                'stream' => false,
                'format' => 'json',
                'options' => [
                    'num_predict' => 1024,
                    'temperature' => 0.5,
                    'num_ctx' => 2048
                ]
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
