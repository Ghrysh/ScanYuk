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
            $keywordPrompt = "You are a Market Researcher. What is a highly searched, trending long-tail keyword related to 'AR QR Code Scanner' or 'Platform AR' this month? Please reply with ONLY the keyword string itself, no quotes, no explanation.";
            
            $trendingKeyword = "Platform AR Interaktif"; // Fallback
            
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
                }
            } catch (\Exception $e) {
                $this->warn("Ollama offline untuk pencarian tren. Memakai keyword fallback.");
                $trendingKeyword = "Solusi AR Marketing " . rand(100, 999);
            }

            $this->info("Target Keyword dari AI: " . $trendingKeyword);

            // 3. Analisa SEO dengan AI
            $url = url($pagePath);
            $prompt = "You are an expert SEO Consultant. I have a webpage at '$url' and I want to target the trending keyword '$trendingKeyword'.
Please generate a comprehensive SEO recommendation report.
You MUST output ONLY a valid JSON object with the following exact structure, no markdown:
{
    \"overall_score\": 75,
    \"meta_title\": \"Suggested Meta Title (max 60 chars)\",
    \"meta_description\": \"Suggested Meta Description (max 160 chars)\",
    \"h1_heading\": \"Suggested H1 Heading containing the keyword\",
    \"faq_schema\": [
        {\"question\": \"FAQ Question 1\", \"answer\": \"FAQ Answer 1\"}
    ],
    \"backlink_strategy\": \"Suggestion for acquiring backlinks for this keyword\",
    \"internal_link_strategy\": \"Suggestion for internal links\",
    \"image_optimization\": \"Suggestions for alt texts and image compression\",
    \"page_speed\": \"Suggestions for improving page speed\"
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
