<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ArTemplate;

class ArTemplateSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Template Ulang Tahun (3D Cake + Kado)
        ArTemplate::create([
            'title' => 'Kejutan Ulang Tahun 3D',
            'ar_type' => '3d',
            'file_path' => env('AWS_URL') . '/junikastudio-cake-1682.glb',
            'bgm_path' => 'krasnoshchok-happy-birthday-486360.mp3',
            'narration' => 'Selamat ulang tahun! Semoga hari-harimu selalu dipenuhi dengan kebahagiaan, tawa, dan cinta. Buka kadonya sekarang!'
        ]);

        // 2. Template Kelulusan / Wisuda (3D Topi Wisuda)
        ArTemplate::create([
            'title' => 'Ucapan Selamat Wisuda 3D',
            'ar_type' => '3d',
            'file_path' => env('AWS_URL') . '/febrianes86-graduation-3134.glb',
            'bgm_path' => 'hitslab-achievement-graduation-ceremony-music-277992.mp3',
            'narration' => 'Selamat atas kelulusanmu! Perjalanan panjang yang penuh dedikasi telah membuahkan hasil. Semoga sukses di dunia karier!'
        ]);

        // 3. Template Valentine / Cinta (3D Hati)
        ArTemplate::create([
            'title' => 'Kartu Ucapan Valentine 3D',
            'ar_type' => '3d',
            'file_path' => env('AWS_URL') . '/blendertimer-heart-23.glb',
            'bgm_path' => 'lkoliks-romantics-love-valentines-day-468191.mp3',
            'narration' => 'Terima kasih sudah selalu ada di sisiku. Kaulah hal terbaik yang pernah terjadi dalam hidupku. Happy Valentines day.'
        ]);

        // 4. Template Promo Makanan (3D Pizza)
        ArTemplate::create([
            'title' => 'Promo Diskon Restoran 3D',
            'ar_type' => '3d',
            'file_path' => env('AWS_URL') . '/plaggy_cc0-pizza-572.glb',
            'bgm_path' => 'poradovskyi-cozy-chill-lounge-music-469048.mp3',
            'narration' => 'Lapar? Dapatkan diskon spesial dua puluh persen untuk semua menu makanan hari ini. Tunjukkan AR ini ke kasir kami!'
        ]);
    }
}