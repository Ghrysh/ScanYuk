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
Please generate a comprehensive SEO recommendation report for this page.
You MUST output ONLY a valid JSON object with the following exact structure, no markdown, no other text:
{
    \"overall_score\": 75,
    \"meta_title\": \"Suggested Meta Title (max 60 chars)\",
    \"meta_description\": \"Suggested Meta Description (max 160 chars)\",
    \"h1_heading\": \"Suggested H1 Heading containing the keyword\",
    \"faq_schema\": [
        {\"question\": \"FAQ Question 1\", \"answer\": \"FAQ Answer 1\"},
        {\"question\": \"FAQ Question 2\", \"answer\": \"FAQ Answer 2\"}
    ],
    \"backlink_strategy\": \"Suggestion for acquiring backlinks for this keyword\",
    \"internal_link_strategy\": \"Suggestion for internal links\",
    \"image_optimization\": \"Suggestions for alt texts and image compression\",
    \"page_speed\": \"Suggestions for improving page speed (e.g. caching, minification)\"
}";

        try {
            $response = Http::timeout(60)->post('http://scanyuk-ollama:11434/api/generate', [
                'model' => 'llama3', 
                'prompt' => $prompt,
                'stream' => false,
                'format' => 'json'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $resultText = $data['response'] ?? '{}';
                
                $parsed = json_decode($resultText, true);
                if (!$parsed) {
                    $parsed = json_decode(preg_replace('/```json|```/', '', $resultText), true);
                }

                if ($parsed) {
                    $recommendation = SeoRecommendation::create([
                        'page_path' => $pagePath,
                        'target_keyword' => $keyword,
                        'overall_score' => $parsed['overall_score'] ?? 0,
                        'recommendations' => $parsed,
                        'status' => 'pending'
                    ]);

                    return response()->json(['success' => true, 'data' => $recommendation]);
                }
            }

            return response()->json(['success' => false, 'message' => 'Gagal mendapatkan response valid dari AI.']);
        } catch (\Exception $e) {
            // Jika ollama tidak jalan, pakai fallback mock agar sistem tidak freeze total saat demo
            $mockParsed = [
                "overall_score" => rand(70, 95),
                "meta_title" => "Optimasi " . ucfirst($keyword) . " Terbaik - ScanYuk",
                "meta_description" => "Tingkatkan interaksi dengan " . $keyword . ". Solusi AR QR Code terdepan untuk bisnis Anda.",
                "h1_heading" => "Solusi Inovatif " . ucfirst($keyword) . " untuk Bisnis",
                "faq_schema" => [
                    ["question" => "Apa itu " . $keyword . "?", "answer" => "Ini adalah layanan terintegrasi dari ScanYuk."],
                    ["question" => "Bagaimana cara kerja " . $keyword . "?", "answer" => "Sangat mudah, cukup scan QR Code Anda."]
                ],
                "backlink_strategy" => "Cari backlink dari situs teknologi dan marketing dengan DR tinggi.",
                "internal_link_strategy" => "Tambahkan link dari blog post ke halaman ini.",
                "image_optimization" => "Gunakan format WebP dan tambahkan alt text yang relevan.",
                "page_speed" => "Aktifkan lazy loading untuk gambar di bawah lipatan (below the fold)."
            ];

            $recommendation = SeoRecommendation::create([
                'page_path' => $pagePath,
                'target_keyword' => $keyword,
                'overall_score' => $mockParsed['overall_score'],
                'recommendations' => $mockParsed,
                'status' => 'pending'
            ]);

            return response()->json(['success' => true, 'data' => $recommendation, 'warning' => 'Ollama offline, menggunakan fallback AI mock. Error: ' . $e->getMessage()]);
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
    
    public function getRecommendations()
    {
        $recs = SeoRecommendation::latest()->get();
        return response()->json($recs);
    }
}
