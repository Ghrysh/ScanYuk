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

        // 1. Ambil HTML halaman
        $pageHtml = "";
        try {
            $pageHtml = Http::timeout(15)->get($url)->body();
        } catch (\Exception $e) {}

        if (empty($pageHtml)) {
            return response()->json(['success' => false, 'message' => 'Gagal mengambil HTML halaman. Pastikan halaman bisa diakses.']);
        }

        // 2. Ekstrak metadata SEO secara terstruktur
        $seoSummary = $this->extractSeoMetadata($pageHtml, $pagePath);

        // 3. Bangun prompt
        $keywordContext = $keyword
            ? "Admin secara spesifik ingin menargetkan keyword '{$keyword}'. Evaluasi apakah halaman ini sudah optimal untuk keyword tersebut dan berikan rekomendasi terkait."
            : "Evaluasi SEO halaman ini secara menyeluruh dan sarankan keyword target jika diperlukan.";

        $system = "Anda adalah AI Konsultan SEO profesional yang bekerja untuk website ScanYuk (scanyuk.com), sebuah platform Augmented Reality (AR) dan QR Code Scanner di Indonesia. Tugas Anda adalah menganalisis data SEO yang diberikan dan memberikan rekomendasi perbaikan yang konkrit dan spesifik. SEMUA jawaban WAJIB dalam BAHASA INDONESIA. Output HARUS berupa JSON array yang valid tanpa teks tambahan apapun di luar JSON.";

        $prompt = <<<PROMPT
{$keywordContext}

Berikut data SEO yang sudah diekstrak dari halaman {$pagePath} website ScanYuk:

{$seoSummary}

Berdasarkan data di atas, berikan rekomendasi SEO yang SPESIFIK merujuk ke data yang diberikan. Setiap rekomendasi harus menyebutkan data konkret dari halaman ini.

Contoh 1 rekomendasi dengan level detail yang BENAR (ikuti format dan tingkat detail ini):
[{"category":"Meta Description","research_finding":"Menurut panduan Google Search Central, meta description optimal adalah 150-160 karakter dan mengandung keyword utama. Halaman tanpa meta description kehilangan hingga 30 persen potensi klik dari hasil pencarian.","current_condition":"Meta description halaman / saat ini adalah: 'ScanYuk - Platform AR'. Panjangnya hanya 20 karakter dan tidak menyebutkan keyword penting seperti 'QR Code Scanner' atau 'Augmented Reality Indonesia'.","impact":"Google akan mengambil teks acak dari halaman sebagai cuplikan di hasil pencarian. Pengunjung potensial tidak memahami isi halaman sehingga rasio klik (CTR) turun drastis dibandingkan kompetitor.","recommendation_text":"Ubah meta description menjadi kalimat yang lebih deskriptif, contoh: 'ScanYuk adalah platform Augmented Reality dan QR Code Scanner terdepan di Indonesia. Buat pengalaman AR interaktif untuk bisnis Anda dengan mudah.' Pastikan panjangnya 150-160 karakter dan mengandung keyword utama.","expected_outcome":"Dengan meta description yang informatif dan mengandung keyword, CTR dari Google Search meningkat 15-30 persen. Halaman ScanYuk tampil lebih menarik dan profesional di halaman hasil pencarian Google."}]

Sekarang analisis data SEO halaman {$pagePath} di atas dan berikan 3-7 rekomendasi Anda. Output HANYA JSON array, tidak boleh ada teks lain di luar JSON.
PROMPT;

        // 4. Kirim ke Ollama
        try {
            $response = Http::timeout(1200)->post('http://scanyuk-ollama:11434/api/generate', [
                'model' => 'llama3',
                'system' => $system,
                'prompt' => $prompt,
                'stream' => false,
                'format' => 'json',
                'options' => [
                    'num_predict' => 1536,
                    'temperature' => 0.4,
                    'num_ctx' => 2048,
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
                                'expected_outcome' => $rec['expected_outcome'] ?? '',
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

    /**
     * Ekstrak metadata SEO dari HTML secara terstruktur.
     */
    private function extractSeoMetadata(string $html, string $pagePath): string
    {
        // Title
        preg_match('/<title[^>]*>(.*?)<\/title>/is', $html, $m);
        $title = trim(strip_tags($m[1] ?? 'Tidak ditemukan'));

        // Meta description
        $metaDesc = $this->extractMetaContent($html, 'description');
        $metaKeywords = $this->extractMetaContent($html, 'keywords');

        // H1 & H2
        preg_match_all('/<h1[^>]*>(.*?)<\/h1>/is', $html, $m);
        $h1Tags = array_filter(array_map(fn($t) => trim(strip_tags($t)), $m[1] ?? []), fn($t) => !empty($t));

        preg_match_all('/<h2[^>]*>(.*?)<\/h2>/is', $html, $m);
        $h2Tags = array_filter(array_map(fn($t) => trim(strip_tags($t)), $m[1] ?? []), fn($t) => !empty($t));

        // Gambar
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
            if (!$hasAlt) $imagesWithoutAlt++;
            $ext = strtolower(pathinfo(parse_url($src, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION)) ?: '?';
            if (!in_array($ext, ['webp', 'svg', 'avif'])) $nonWebpImages++;
            $images[] = "  - {$src} (format: {$ext}, alt: \"" . ($hasAlt ? $altMatch[1] : 'TIDAK ADA ALT') . "\")";
        }

        // Links
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

        // Canonical, JSON-LD, OG
        preg_match('/<link[^>]*rel=["\']canonical["\'][^>]*href=["\']([^"\']*)["\'][^>]*>/is', $html, $m);
        $canonical = trim($m[1] ?? 'Tidak ditemukan');

        preg_match_all('/<script[^>]*type=["\']application\/ld\+json["\'][^>]*>/is', $html, $m);
        $hasJsonLd = count($m[0] ?? []) > 0 ? "Ada (" . count($m[0]) . " schema)" : 'Tidak ada';

        $ogTitle = $this->extractOgContent($html, 'og:title');
        $ogDesc = $this->extractOgContent($html, 'og:description');

        // Bangun ringkasan
        $s = "JUDUL HALAMAN: {$title}\n";
        $s .= "META DESKRIPSI: {$metaDesc} (panjang: " . mb_strlen($metaDesc) . " karakter)\n";
        $s .= "META KEYWORDS: {$metaKeywords}\n";
        $s .= "CANONICAL URL: {$canonical}\n";
        $s .= "OG:TITLE: {$ogTitle}\n";
        $s .= "OG:DESCRIPTION: {$ogDesc}\n";
        $s .= "STRUCTURED DATA (JSON-LD): {$hasJsonLd}\n";
        $s .= "HEADING H1: " . (empty($h1Tags) ? 'TIDAK ADA H1!' : implode(' | ', array_slice($h1Tags, 0, 3))) . "\n";
        $s .= "HEADING H2 (" . count($h2Tags) . " total): " . (empty($h2Tags) ? 'Tidak ada H2' : implode(' | ', array_slice($h2Tags, 0, 5))) . "\n";
        $s .= "GAMBAR ({$totalImages} total, {$imagesWithoutAlt} tanpa alt, {$nonWebpImages} bukan WebP):\n" . implode("\n", $images) . "\n";
        $s .= "LINK INTERNAL (" . count($internalLinks) . "): " . implode(', ', array_slice($internalLinks, 0, 8)) . "\n";
        $s .= "LINK EXTERNAL (" . count($externalLinks) . "): " . implode(', ', array_slice($externalLinks, 0, 5)) . "\n";

        return $s;
    }

    private function extractMetaContent(string $html, string $name): string
    {
        preg_match('/<meta[^>]*name=["\']' . $name . '["\'][^>]*content=["\']([^"\']*)["\'][^>]*>/is', $html, $m);
        if (!empty($m[1])) return trim($m[1]);
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
