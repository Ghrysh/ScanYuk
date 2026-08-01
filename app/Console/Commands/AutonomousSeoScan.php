<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AutonomousSeoScan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seo:autonomous-scan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically scan competitors/trends and generate SEO recommendations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Memulai Autonomous AI SEO Scan...");

        $pages = ['/', '/pricing', '/business', '/consumer', '/faq'];
        $hasError = false;

        foreach ($pages as $pagePath) {
            $this->info("\n=== Menganalisa Halaman: " . $pagePath . " ===");

            // 2. Tanya AI (Ollama) untuk memberikan "Trending Keyword" terkait AR QR Code
            $keywordPrompt = "You are a Market Researcher. What is a highly searched, trending long-tail keyword related to 'AR QR Code Scanner' or 'Platform AR' this month? Please reply with ONLY the keyword string itself in BAHASA INDONESIA, no quotes, no explanation.";
            $trendingKeyword = "";

            try {
                $this->info("Menghubungi Ollama untuk mencari tren kata kunci...");
                $res = \Illuminate\Support\Facades\Http::timeout(120)->post('http://scanyuk-ollama:11434/api/generate', [
                    'model' => 'llama3',
                    'prompt' => $keywordPrompt,
                    'stream' => false
                ]);
                
                if ($res->successful()) {
                    $trendingKeyword = trim($res->json('response'));
                    $trendingKeyword = str_replace(['"', "'"], '', $trendingKeyword);
                } else {
                    $this->error("Ollama HTTP Error: " . $res->status() . " - " . $res->body());
                    return;
                }
            } catch (\Exception $e) {
                $this->error("Gagal terhubung ke Ollama untuk mencari keyword: " . $e->getMessage());
                return;
            }

            $this->info("Target Keyword dari AI: " . $trendingKeyword);

            // 3. Analisa SEO dengan AI
            $url = url($pagePath);
            
            // Coba ambil isi halaman saat ini agar AI punya konteks (Scrape Title & Meta Description)
            $currentTitle = "Tidak diketahui";
            $currentDesc = "Tidak diketahui";
            try {
                $pageHtml = \Illuminate\Support\Facades\Http::timeout(10)->get($url)->body();
                if (preg_match('/<title>(.*?)<\/title>/is', $pageHtml, $m)) {
                    $currentTitle = trim(strip_tags($m[1]));
                }
                if (preg_match('/<meta\s+name=["\']description["\']\s+content=["\'](.*?)["\']/is', $pageHtml, $m)) {
                    $currentDesc = trim($m[1]);
                }
            } catch (\Exception $e) {
                // Abaikan jika gagal scrape
            }

            $prompt = "You are an expert SEO Consultant. I have a webpage at '$url' and I want to target the trending keyword '$trendingKeyword'.
Here is the current state of the page:
- Current Meta Title: $currentTitle
- Current Meta Description: $currentDesc

Please generate a comprehensive SEO recommendation report. 
IMPORTANT RULES YOU MUST FOLLOW:
1. The content of your recommendations MUST be in BAHASA INDONESIA.
2. Meta Title MUST be under 60 characters, catchy, and include the brand name 'ScanYuk'.
3. Meta Description MUST be between 120-160 characters, persuasive, and include a Call to Action (CTA).
4. H1 Heading MUST be short, punchy, and relevant to the page content (not a full sentence).

You MUST output ONLY a valid JSON object with the following exact structure, no markdown:
{
    \"overall_score\": 75,
    \"meta_title\": {
        \"current\": \"$currentTitle\",
        \"recommendation\": \"Saran Judul Meta Baru (max 60 chars)\",
        \"reason\": \"Alasan kenapa judul ini lebih baik\"
    },
    \"meta_description\": {
        \"current\": \"$currentDesc\",
        \"recommendation\": \"Saran Deskripsi Meta Baru (120-160 chars)\",
        \"reason\": \"Alasan kenapa deskripsi ini lebih baik\"
    },
    \"h1_heading\": {
        \"recommendation\": \"Saran Heading H1 (Singkat & Padat)\",
        \"reason\": \"Alasan pemilihan H1 ini\"
    },
    \"faq_schema\": [
        {\"question\": \"Pertanyaan FAQ 1\", \"answer\": \"Jawaban FAQ 1\", \"reason\": \"Alasan menambah FAQ ini\"}
    ],
    \"backlink_strategy\": {
        \"recommendation\": \"Saran strategi backlink\",
        \"reason\": \"Alasan strategi backlink ini\"
    },
    \"internal_link_strategy\": {
        \"recommendation\": \"Saran strategi tautan internal\",
        \"reason\": \"Alasan strategi ini\"
    },
    \"image_optimization\": {
        \"recommendation\": \"Saran optimasi gambar\",
        \"reason\": \"Alasan perlunya optimasi ini\"
    },
    \"page_speed\": {
        \"recommendation\": \"Saran optimasi kecepatan loading\",
        \"reason\": \"Dampak pada SEO\"
    }
}";

            try {
                $this->info("Menganalisa halaman dengan Ollama...");
                $response = \Illuminate\Support\Facades\Http::timeout(300)->post('http://scanyuk-ollama:11434/api/generate', [
                    'model' => 'llama3', 
                    'prompt' => $prompt,
                    'stream' => false,
                    'format' => 'json'
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $resultText = $data['response'] ?? '{}';
                    
                    preg_match('/\{.*\}/s', $resultText, $matches);
                    if (!empty($matches)) {
                        $resultText = $matches[0];
                    }
                    
                    $parsed = json_decode($resultText, true);

                    if ($parsed) {
                        \App\Models\SeoRecommendation::create([
                            'page_path' => $pagePath,
                            'target_keyword' => $trendingKeyword,
                            'overall_score' => $parsed['overall_score'] ?? rand(70,95),
                            'recommendations' => $parsed,
                            'status' => 'pending',
                            'manual_status' => 'pending',
                            'ai_type' => 'proactive'
                        ]);
                        $this->info("Berhasil! Rekomendasi SEO Proaktif telah ditambahkan ke database.");
                    } else {
                        $this->error("Gagal memparsing respons JSON dari AI. Respons mentah: \n" . $data['response']);
                        $hasError = true;
                    }
                } else {
                    $this->error("Gagal mendapat response HTTP 200 dari Ollama. Status: " . $response->status() . "\nBody: " . $response->body());
                    $hasError = true;
                }
            } catch (\Exception $e) {
                $this->error("Gagal terhubung ke Ollama atau terjadi error: " . $e->getMessage());
                $hasError = true;
            }
        }

        return $hasError ? 1 : 0;
    }
}
