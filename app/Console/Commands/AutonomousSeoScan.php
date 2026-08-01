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

            $url = url($pagePath);
            
            // Coba ambil seluruh isi halaman saat ini agar AI punya konteks penuh
            $pageHtml = "";
            try {
                $pageHtml = \Illuminate\Support\Facades\Http::timeout(15)->get($url)->body();
            } catch (\Exception $e) {
                $this->warn("Gagal scrape halaman $url, melanjutkan tanpa HTML penuh.");
            }

            // Bersihkan HTML dari script, style, dan elemen tidak penting agar proses AI jauh lebih cepat (mengurangi beban token)
            $cleanHtml = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $pageHtml);
            $cleanHtml = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $cleanHtml);
            $cleanHtml = preg_replace('/<svg\b[^>]*>(.*?)<\/svg>/is', '', $cleanHtml);
            $cleanHtml = preg_replace('/<!--(.*?)-->/is', '', $cleanHtml);
            // Hapus atribut class/style yang panjang dan tidak berguna untuk SEO
            $cleanHtml = preg_replace('/\s(class|style|id|data-[^=]*)=["\'][^"\']*["\']/is', '', $cleanHtml);
            $cleanHtml = preg_replace('/\s+/', ' ', $cleanHtml);
            
            // Batasi panjang HTML agar tidak meledak di konteks model Llama
            $safeHtml = substr(trim($cleanHtml), 0, 4000); // 4000 char sudah cukup untuk ambil judul, meta, dan awal body

            $prompt = "You are an expert SEO Consultant. I have a webpage at '$url'.
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
                $this->info("Menganalisa halaman dengan Ollama...");
                $response = \Illuminate\Support\Facades\Http::timeout(600)->post('http://scanyuk-ollama:11434/api/generate', [
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
