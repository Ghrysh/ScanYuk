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
            
            // Coba ambil seluruh isi halaman saat ini agar AI punya konteks penuh
            $pageHtml = "";
            try {
                $pageHtml = \Illuminate\Support\Facades\Http::timeout(15)->get($url)->body();
            } catch (\Exception $e) {
                $this->warn("Gagal scrape halaman $url, melanjutkan tanpa HTML penuh.");
            }

            // Batasi panjang HTML agar tidak meledak di konteks model Llama (potong jika sangat panjang)
            $safeHtml = substr($pageHtml, 0, 8000); 

            $prompt = "You are an expert SEO Consultant. I have a webpage at '$url' and I want to target the trending keyword '$trendingKeyword'.
Here is a snippet of the current HTML source code of the page:
```html
$safeHtml
```

Please generate a comprehensive list of actionable SEO recommendations. 
IMPORTANT RULES YOU MUST FOLLOW:
1. The content MUST be in BAHASA INDONESIA.
2. Provide a dynamic list of recommendations. You can use standard categories like 'FAQ', 'Backlink', 'Internal Link', 'Update Heading', 'Optimasi Gambar', 'Page Speed', or INVENT NEW CATEGORIES if you find specific opportunities (e.g., 'Feature Addition', 'Keyword Optimization').
3. You MUST output ONLY a valid JSON ARRAY of objects, with no markdown formatting.

Format the output EXACTLY like this JSON array:
[
    {
        \"category\": \"Nama Kategori (contoh: Optimasi Gambar / Page Speed)\",
        \"research_finding\": \"Fakta/riset tren SEO saat ini terkait hal ini\",
        \"current_condition\": \"Kondisi yang Anda temukan di kode HTML web ini\",
        \"impact\": \"Dampak negatif jika dibiarkan atau dampak positif jika diperbaiki\",
        \"recommendation_text\": \"Saran konkret apa yang harus dilakukan oleh tim kami\"
    }
]";

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
                    $resultText = $data['response'] ?? '[]';
                    
                    preg_match('/\[.*\]/s', $resultText, $matches);
                    if (!empty($matches)) {
                        $resultText = $matches[0];
                    }
                    
                    $parsed = json_decode($resultText, true);

                    if ($parsed && is_array($parsed)) {
                        foreach ($parsed as $rec) {
                            if (isset($rec['category']) && isset($rec['recommendation_text'])) {
                                \App\Models\SeoRecommendation::create([
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
                        $this->info("Berhasil! " . count($parsed) . " Rekomendasi SEO Proaktif telah ditambahkan ke database.");
                    } else {
                        $this->error("Gagal memparsing respons JSON dari AI. Respons mentah: \n" . $data['response']);
                        $hasError = true;
                    }
                } else {
                    $this->error("Gagal mendapat response HTTP 200 dari Ollama. Status: " . $response->status());
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
