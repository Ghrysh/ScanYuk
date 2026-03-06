<?php

namespace App\Http\Controllers;

use App\Models\QrCode;
use App\Models\User;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function showDemo()
    {
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            return "Silakan jalankan database seeder untuk membuat akun admin terlebih dahulu.";
        }

        $demoQr = QrCode::updateOrCreate(
            ['id' => 'demo-scanyuk'],
            [
                'user_id' => $admin->id,
                'title' => 'Demo Augmented Reality ScanYuk',
                'ar_type' => '3d',
                'ar_asset_id' => null,
                'file_path' => 'demo/logo.glb', 
                'bgm_path' => 'demo/future.mp3',
                'bgm_start' => 0,
                'bgm_end' => 100,
                'bgm_volume' => 0.5,
                'narration' => 'Selamat datang di scanyuk, ciptakan kreasi Augmented Reality mu sendiri',
                'qr_image_path' => 'demo',
                'scan_count' => 0
            ]
        );

        return view('demo', ['demoId' => $demoQr->id]);
    }
}