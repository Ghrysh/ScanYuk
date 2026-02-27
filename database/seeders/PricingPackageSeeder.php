<?php

namespace Database\Seeders;

use App\Models\PricingPackage;
use Illuminate\Database\Seeder;

class PricingPackageSeeder extends Seeder
{
    public function run(): void
    {
        PricingPackage::truncate();
        
        $packages = [
            [
                'name' => 'Gratis',
                'price' => 0,
                'features' => ['1 Gambar AR', '1 Narasi Suara', '2 Total Scan', 'Analitik dasar'],
                'is_popular' => false,
                'button_text' => 'Pilih Paket'
            ],
            [
                'name' => 'Pemula',
                'price' => 100000,
                'features' => ['2 Gambar AR', '2 Narasi Suara', '10 Total Scan', 'Analitik lengkap', 'Unduh QR'],
                'is_popular' => false,
                'button_text' => 'Pilih Paket'
            ],
            [
                'name' => 'Profesional',
                'price' => 500000,
                'features' => ['10 Gambar AR', '10 Narasi Suara', '70 Total Scan', 'Dukungan prioritas', 'QR siap cetak'],
                'is_popular' => true,
                'button_text' => 'Pilih Paket'
            ],
            [
                'name' => 'Bisnis',
                'price' => 1000000,
                'features' => ['20 Gambar AR', '20 Narasi Suara', '150 Total Scan', 'Dukungan khusus', 'Branding kustom'],
                'is_popular' => false,
                'button_text' => 'Pilih Paket'
            ],
        ];

        foreach ($packages as $pkg) {
            PricingPackage::create($pkg);
        }
    }
}