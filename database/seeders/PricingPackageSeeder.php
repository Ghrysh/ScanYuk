<?php

namespace Database\Seeders;

use App\Models\PricingPackage;
use Illuminate\Database\Seeder;

class PricingPackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Free',
                'price' => 0,
                'features' => ['1 AR Image', '1 Voice Narration', '2 Total Scans', 'Basic analytics'],
                'is_popular' => false,
                'button_text' => 'Coba Gratis'
            ],
            [
                'name' => 'Starter',
                'price' => 100000,
                'features' => ['2 AR Images', '2 Voice Narrations', '10 Total Scans', 'Full analytics', 'Download QR'],
                'is_popular' => false,
                'button_text' => 'Beli Paket'
            ],
            [
                'name' => 'Professional',
                'price' => 500000,
                'features' => ['10 AR Images', '10 Voice Narrations', '70 Total Scans', 'Priority support', 'Print-ready QR'],
                'is_popular' => true,
                'button_text' => 'Beli Paket'
            ],
            [
                'name' => 'Business',
                'price' => 1000000,
                'features' => ['20 AR Images', '20 Voice Narrations', '150 Total Scans', 'Dedicated support', 'Custom branding'],
                'is_popular' => false,
                'button_text' => 'Beli Paket'
            ],
        ];

        foreach ($packages as $pkg) {
            PricingPackage::create($pkg);
        }
    }
}