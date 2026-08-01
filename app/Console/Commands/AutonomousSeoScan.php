<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\SeoRecommendation;

class AutonomousSeoScan extends Command
{
    protected $signature = 'seo:autonomous-scan';
    protected $description = 'Scan halaman ScanYuk dan generate rekomendasi SEO menggunakan AI';

    public function handle()
    {
        $this->info("Memulai Autonomous AI SEO Scan...");

        $pages = ['/', '/pricing', '/business', '/consumer', '/faq'];
        $hasError = false;

        foreach ($pages as $pagePath) {
            $this->info("\n=== Menganalisa Halaman: " . $pagePath . " ===");

            $url = url($pagePath);

            // 1. Ambil HTML halaman
            $pageHtml = "";
            try {
                $pageHtml = Http::timeout(15)->get($url)->body();
                $this->info("HTML berhasil diambil (" . strlen($pageHtml) . " bytes)");
            } catch (\Exception $e) {
                $this->warn("Gagal scrape halaman $url: " . $e->getMessage());
            }

            if (empty($pageHtml)) {
                $this->warn("Halaman kosong, skip...");
                continue;
            }

            // 2. Ekstrak metadata SEO secara terstruktur (BUKAN dump HTML mentah)
            //    Ini jauh lebih efektif karena Llama 3 menerima data bersih dan terstruktur
            $seoSummary = $this->extractSeoMetadata($pageHtml, $pagePath);
            $this->info("Metadata SEO berhasil diekstrak.");

            // 3. Bangun prompt terstruktur dengan few-shot example
            $system = "Anda adalah AI Konsultan SEO profesional yang bekerja untuk website ScanYuk (scanyuk.com), sebuah platform Augmented Reality (AR) dan QR Code Scanner di Indonesia. Tugas Anda adalah menganalisis data SEO yang diberikan dan memberikan rekomendasi perbaikan yang konkrit dan spesifik. SEMUA jawaban WAJIB dalam BAHASA INDONESIA. Output HARUS berupa JSON array yang valid tanpa teks tambahan apapun di luar JSON.";

            $prompt = <<<PROMPT
Berikut data SEO yang sudah diekstrak dari halaman {$pagePath} website ScanYuk:

{$seoSummary}

Berdasarkan data di atas, berikan rekomendasi SEO yang SPESIFIK merujuk ke data yang diberikan. Setiap rekomendasi harus menyebutkan data konkret dari halaman ini.

Contoh 1 rekomendasi dengan level detail yang BENAR (ikuti format dan tingkat detail ini):
[{"category":"Meta Description","research_finding":"Menurut panduan Google Search Central, meta description optimal adalah 150-160 karakter dan mengandung keyword utama. Halaman tanpa meta description kehilangan hingga 30 persen potensi klik dari hasil pencarian.","current_condition":"Meta description halaman / saat ini adalah: 'ScanYuk - Platform AR'. Panjangnya hanya 20 karakter dan tidak menyebutkan keyword penting seperti 'QR Code Scanner' atau 'Augmented Reality Indonesia'.","impact":"Google akan mengambil teks acak dari halaman sebagai cuplikan di hasil pencarian. Pengunjung potensial tidak memahami isi halaman sehingga rasio klik (CTR) turun drastis dibandingkan kompetitor.","recommendation_text":"Ubah meta description menjadi kalimat yang lebih deskriptif, contoh: 'ScanYuk adalah platform Augmented Reality dan QR Code Scanner terdepan di Indonesia. Buat pengalaman AR interaktif untuk bisnis Anda dengan mudah.' Pastikan panjangnya 150-160 karakter dan mengandung keyword utama.","expected_outcome":"Dengan meta description yang informatif dan mengandung keyword, CTR dari Google Search meningkat 15-30 persen. Halaman ScanYuk tampil lebih menarik dan profesional di halaman hasil pencarian Google."}]

Sekarang analisis data SEO halaman {$pagePath} di atas dan berikan 3-7 rekomendasi Anda. Output HANYA JSON array, tidak boleh ada teks lain di luar JSON.
PROMPT;

            // 4. Kirim ke Ollama dengan parameter system terpisah (format Llama 3)
            try {
                $this->info("Mengirim ke Ollama untuk dianalisis...");
                $response = Http::timeout(1200)->post('http://scanyuk-ollama:11434/api/generate', [
                    'model' => 'llama3',
                    'system' => $system,
                    'prompt' => $prompt,
                    'stream' => false,
                    'format' => 'json',
                    'options' => [
                        'num_predict' => 2048,
                        'temperature' => 0.4,
                        'num_ctx' => 4096,
                    ]
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $resultText = $data['response'] ?? '[]';

                    // Coba ekstrak JSON array dari response
                    preg_match('/\[.*\]/s', $resultText, $matches);
                    if (!empty($matches)) {
                        $resultText = $matches[0];
                    }

                    $parsed = json_decode($resultText, true);

                    if ($parsed && is_array($parsed)) {
                        $inserted = 0;
                        foreach ($parsed as $rec) {
                            if (isset($rec['category']) && isset($rec['recommendation_text'])) {
                                SeoRecommendation::create([
                                    'page_path' => $pagePath,
                                    'category' => $rec['category'],
                                    'research_finding' => $rec['research_finding'] ?? '',
                                    'current_condition' => $rec['current_condition'] ?? '',
                                    'impact' => $rec['impact'] ?? '',
                                    'recommendation_text' => $rec['recommendation_text'],
                                    'expected_outcome' => $rec['expected_outcome'] ?? '',
                                    'status' => 'pending'
                                ]);
                                $inserted++;
                            }
                        }
                        $this->info("Berhasil! $inserted rekomendasi SEO telah ditambahkan ke database.");
                    } else {
                        $this->error("Gagal parsing JSON dari AI. Response: " . substr($data['response'] ?? '', 0, 500));
                        $hasError = true;
                    }
                } else {
                    $this->error("Ollama HTTP Error: " . $response->status());
                    $hasError = true;
                }
            } catch (\Exception $e) {
                $this->error("Error: " . $e->getMessage());
                $hasError = true;
            }
        }

        return $hasError ? 1 : 0;
    }

    /**
     * Ekstrak metadata SEO dari HTML secara terstruktur.
     * 
     * Alih-alih mengirim HTML mentah ke LLM (yang penuh noise dan memboroskan token),
     * kita ekstrak elemen-elemen yang relevan untuk SEO dan menyajikannya
     * sebagai ringkasan teks yang bersih dan terstruktur.
     */
    private function extractSeoMetadata(string $html, string $pagePath): string
    {
        // Title
        preg_match('/<title[^>]*>(.*?)<\/title>/is', $html, $m);
        $title = trim(strip_tags($m[1] ?? 'Tidak ditemukan'));

        // Meta description (handle kedua urutan atribut)
        $metaDesc = $this->extractMetaContent($html, 'description');

        // Meta keywords
        $metaKeywords = $this->extractMetaContent($html, 'keywords');

        // H1
        preg_match_all('/<h1[^>]*>(.*?)<\/h1>/is', $html, $m);
        $h1Tags = array_map(fn($t) => trim(strip_tags($t)), $m[1] ?? []);
        $h1Tags = array_filter($h1Tags, fn($t) => !empty($t));

        // H2
        preg_match_all('/<h2[^>]*>(.*?)<\/h2>/is', $html, $m);
        $h2Tags = array_map(fn($t) => trim(strip_tags($t)), $m[1] ?? []);
        $h2Tags = array_filter($h2Tags, fn($t) => !empty($t));

        // Gambar — ekstrak src, alt, dan format file
        preg_match_all('/<img[^>]*>/is', $html, $m);
        $allImgTags = $m[0] ?? [];
        $totalImages = count($allImgTags);
        $images = [];
        $imagesWithoutAlt = 0;
        $nonWebpImages = 0;
        foreach (array_slice($allImgTags, 0, 15) as $imgTag) {
            preg_match('/src=["\']([^"\']*)["\']/', $imgTag, $srcMatch);
            preg_match('/alt=["\']([^"\']*)["\']/', $imgTag, $altMatch);
            $src = $srcMatch[1] ?? '?';
            $hasAlt = isset($altMatch[1]) && trim($altMatch[1]) !== '';
            $alt = $hasAlt ? $altMatch[1] : 'TIDAK ADA ALT';
            if (!$hasAlt) $imagesWithoutAlt++;
            $ext = strtolower(pathinfo(parse_url($src, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION)) ?: '?';
            if (!in_array($ext, ['webp', 'svg', 'avif'])) $nonWebpImages++;
            $images[] = "  - {$src} (format: {$ext}, alt: \"{$alt}\")";
        }

        // Link internal & external
        preg_match_all('/<a[^>]*href=["\']([^"\'#]*)["\'][^>]*>/is', $html, $m);
        $internalLinks = [];
        $externalLinks = [];
        foreach (array_unique($m[1] ?? []) as $link) {
            $link = trim($link);
            if (empty($link) || str_starts_with($link, 'javascript') || str_starts_with($link, 'mailto')) continue;
            if (str_starts_with($link, 'http') && !str_contains($link, 'scanyuk')) {
                $externalLinks[] = $link;
            } else {
                $internalLinks[] = $link;
            }
        }

        // Canonical
        preg_match('/<link[^>]*rel=["\']canonical["\'][^>]*href=["\']([^"\']*)["\'][^>]*>/is', $html, $m);
        $canonical = trim($m[1] ?? 'Tidak ditemukan');

        // JSON-LD structured data
        preg_match_all('/<script[^>]*type=["\']application\/ld\+json["\'][^>]*>/is', $html, $m);
        $jsonLdCount = count($m[0] ?? []);
        $hasJsonLd = $jsonLdCount > 0 ? "Ada ({$jsonLdCount} schema)" : 'Tidak ada';

        // OG tags
        $ogTitle = $this->extractOgContent($html, 'og:title');
        $ogDesc = $this->extractOgContent($html, 'og:description');
        $ogImage = $this->extractOgContent($html, 'og:image');

        // Bangun ringkasan terstruktur
        $summary = "JUDUL HALAMAN: {$title}\n";
        $summary .= "META DESKRIPSI: {$metaDesc} (panjang: " . mb_strlen($metaDesc) . " karakter)\n";
        $summary .= "META KEYWORDS: {$metaKeywords}\n";
        $summary .= "CANONICAL URL: {$canonical}\n";
        $summary .= "OG:TITLE: {$ogTitle}\n";
        $summary .= "OG:DESCRIPTION: {$ogDesc}\n";
        $summary .= "OG:IMAGE: {$ogImage}\n";
        $summary .= "STRUCTURED DATA (JSON-LD): {$hasJsonLd}\n";
        $summary .= "HEADING H1: " . (empty($h1Tags) ? 'TIDAK ADA H1 (masalah serius!)' : implode(' | ', array_slice($h1Tags, 0, 3))) . "\n";
        $summary .= "HEADING H2 (" . count($h2Tags) . " total): " . (empty($h2Tags) ? 'Tidak ada H2' : implode(' | ', array_slice($h2Tags, 0, 5))) . "\n";
        $summary .= "GAMBAR ({$totalImages} total, {$imagesWithoutAlt} tanpa alt, {$nonWebpImages} bukan WebP):\n" . implode("\n", $images) . "\n";
        $summary .= "LINK INTERNAL (" . count($internalLinks) . "): " . implode(', ', array_slice($internalLinks, 0, 8)) . "\n";
        $summary .= "LINK EXTERNAL (" . count($externalLinks) . "): " . implode(', ', array_slice($externalLinks, 0, 5)) . "\n";

        return $summary;
    }

    private function extractMetaContent(string $html, string $name): string
    {
        // Coba urutan name...content
        preg_match('/<meta[^>]*name=["\']' . $name . '["\'][^>]*content=["\']([^"\']*)["\'][^>]*>/is', $html, $m);
        if (!empty($m[1])) return trim($m[1]);
        // Coba urutan content...name
        preg_match('/<meta[^>]*content=["\']([^"\']*)["\'][^>]*name=["\']' . $name . '["\'][^>]*>/is', $html, $m);
        return trim($m[1] ?? 'Tidak ditemukan');
    }

    private function extractOgContent(string $html, string $property): string
    {
        preg_match('/<meta[^>]*property=["\']' . preg_quote($property) . '["\'][^>]*content=["\']([^"\']*)["\'][^>]*>/is', $html, $m);
        if (!empty($m[1])) return trim($m[1]);
        preg_match('/<meta[^>]*content=["\']([^"\']*)["\'][^>]*property=["\']' . preg_quote($property) . '["\'][^>]*>/is', $html, $m);
        return trim($m[1] ?? 'Tidak ditemukan');
    }
}
