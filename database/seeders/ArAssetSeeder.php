<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ArAsset;
use Illuminate\Support\Facades\DB;

class ArAssetSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('TRUNCATE TABLE ar_assets RESTART IDENTITY CASCADE');

        $assets = [
            ['name' => 'Hati (Heart) 3D', 'file_path' => 'blendertimer-heart-23.glb'],
            ['name' => 'Tulisan Happy Birthday', 'file_path' => 'dezyne_3d-happy-birthday-1532.glb'],
            ['name' => 'Tulisan Valentine', 'file_path' => 'dezyne_3d-valentine-1539.glb'],
            ['name' => 'Ornamen Happy', 'file_path' => 'eiacreations-happy-3297.glb'],
            ['name' => 'Ornamen Merah', 'file_path' => 'eiacreations-red-3309.glb'],
            ['name' => 'Menu Makanan 1', 'file_path' => 'ergoninane-food-55.glb'],
            ['name' => 'Menu Makanan 2', 'file_path' => 'ergoninane-food-64.glb'],
            ['name' => 'Menu Makanan 3', 'file_path' => 'ergoninane-food-68.glb'],
            ['name' => 'Koin Emas', 'file_path' => 'febrianes86-coin-3135.glb'],
            ['name' => 'Topi Wisuda', 'file_path' => 'febrianes86-graduation-3134.glb'],
            ['name' => 'Batangan Emas', 'file_path' => 'gustavorezende-gold-3629.glb'],
            ['name' => 'Kotak Susu', 'file_path' => 'jeremywoodsster-milk-2579.glb'],
            ['name' => 'Popcorn', 'file_path' => 'julientromeur-popcorn-125.glb'],
            ['name' => 'Kue Ulang Tahun 1', 'file_path' => 'junikastudio-cake-1682.glb'],
            ['name' => 'Kado (Gift)', 'file_path' => 'junikastudio-gift-157.glb'],
            ['name' => 'Kue Ulang Tahun 2', 'file_path' => 'mastertux-cake-256.glb'],
            ['name' => 'Gelas Cocktail', 'file_path' => 'mastertux-cocktail-1594.glb'],
            ['name' => 'Tanda Tanya 3D', 'file_path' => 'mohamed_hassan-question-mark-2253.glb'],
            ['name' => 'Kucing Lucu', 'file_path' => 'niknet_art-cat-2747.glb'],
            ['name' => 'Sushi', 'file_path' => 'niknet_art-sushi-2679.glb'],
            ['name' => 'Boneka Salju', 'file_path' => 'pixelmotion4096-snowman-2600.glb'],
            ['name' => 'Cangkir Kopi', 'file_path' => 'pixelmotion4096-the-cup-46.glb'],
            ['name' => 'Hamburger', 'file_path' => 'plaggy_cc0-hamburger-530.glb'],
            ['name' => 'Pizza', 'file_path' => 'plaggy_cc0-pizza-572.glb'],
            ['name' => 'Buku', 'file_path' => 'quaternius_cc0-book-730.glb'],
            ['name' => 'Kursi Kayu', 'file_path' => 'quaternius_cc0-chair-804.glb'],
        ];

        foreach ($assets as $asset) {
            ArAsset::create([
                'user_id' => 1, 
                'name' => $asset['name'],
                'file_path' => env('AWS_URL') . '/' . $asset['file_path'], 
                'is_public' => true,
            ]);
        }
    }
}