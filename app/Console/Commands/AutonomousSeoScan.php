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

            $prompt = "Anda adalah Konsultan SEO Senior. Berikut adalah kerangka HTML dari halaman '$url' (Path: $pagePath):
```html
$safeHtml
```

Tugas Anda adalah mengevaluasi SEO halaman tersebut dan memberikan 2-3 rekomendasi paling krusial.
ATURAN WAJIB:
1. Seluruh jawaban WAJIB menggunakan BAHASA INDONESIA yang detail dan mudah dipahami.
2. Output HANYA boleh berupa JSON ARRAY. Jangan ada teks markdown atau penjelasan di luar JSON.
3. Maksimal 3 rekomendasi saja.
4. Anda harus mencantumkan KODE SPESIFIK yang Anda temukan di kerangka HTML yang diberikan.

Format JSON WAJIB seperti ini:
[
    {
        \"category\": \"Nama Kategori (Contoh: Optimasi Kecepatan, Konten, Meta Tag)\",
        \"research_finding\": \"Sebutkan temuan riset dan sumber (contoh: 'Menurut Google PageSpeed Insights...')\",
        \"current_condition\": \"KUTIP SECARA SPESIFIK tag HTML atau teks dari source code di halaman $pagePath ini yang bermasalah (contoh: 'Pada halaman ini, tag <img src=\"/img/contoh.png\"> belum menggunakan format WebP dan tidak memiliki atribut alt')\",
        \"impact\": \"Jelaskan detail dampak buruk saat ini dan dampak positifnya jika diperbaiki (contoh: 'Gambar PNG memberatkan loading, jika diubah ke WebP akan mempercepat LCP')\",
        \"recommendation_text\": \"Jelaskan langkah teknis spesifik yang harus dilakukan (contoh: 'Ubah file gambar /img/contoh.png menjadi format .webp dan tambahkan alt=\"contoh\"')\"
    }
]";

            try {
                $this->info("Menganalisa halaman dengan Ollama...");
                $response = \Illuminate\Support\Facades\Http::timeout(1200)->post('http://scanyuk-ollama:11434/api/generate', [
                    'model' => 'llama3', 
                    'prompt' => $prompt,
                    'stream' => false,
                    'format' => 'json',
                    'options' => [
                        'num_predict' => 1024,
                        'temperature' => 0.5,
                        'num_ctx' => 2048 // Batasi ukuran konteks LLM agar tidak memakan RAM berlebih
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
                        $inserted = 0;
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
                                $inserted++;
                            }
                        }
                        $this->info("Berhasil! " . $inserted . " Rekomendasi SEO Proaktif telah ditambahkan ke database.");
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
