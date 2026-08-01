<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\SeoRecommendation;
use App\Models\PageSeoContent;

class AdminSeoController extends Controller
{

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
