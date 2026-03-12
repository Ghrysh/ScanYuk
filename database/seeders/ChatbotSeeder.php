<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChatbotKnowledge;

class ChatbotSeeder extends Seeder
{
    public function run()
    {
        ChatbotKnowledge::truncate();

        $data = [
            // ================= TOPIK 1: AKUN & LOGIN =================
            [
                'topic' => 'Akun & Login',
                'intent_name' => 'registrasi',
                'keywords' => ['daftar', 'register', 'buat akun', 'sign up', 'bikin akun', 'belum punya'],
                'response' => 'Untuk mendaftar, klik tombol "Sign Up" di pojok kanan atas. Anda hanya perlu mengisi Nama, Email, dan melakukan verifikasi OTP yang dikirim ke email Anda.'
            ],
            [
                'topic' => 'Akun & Login',
                'intent_name' => 'lupa_password',
                'keywords' => ['lupa password', 'sandi', 'reset', 'ganti password', 'tidak bisa masuk', 'gagal login'],
                'response' => 'Jika Anda lupa password atau gagal login, klik tulisan "Lupa Password?" di halaman Login. Kami akan mengirimkan tautan (link) reset password langsung ke email terdaftar Anda.'
            ],

            // ================= TOPIK 2: PAKET & PEMBAYARAN =================
            [
                'topic' => 'Paket & Pembayaran',
                'intent_name' => 'harga_paket',
                'keywords' => ['harga', 'paket', 'premium', 'gratis', 'free', 'berlangganan', 'biaya', 'upgrade'],
                'response' => 'Kami memiliki paket Free (Gratis)! Jika Anda butuh ukuran upload lebih besar atau fitur premium, Anda bisa upgrade ke paket Starter, Professional, atau Business. Cek daftar harga lengkapnya di menu Dashboard.'
            ],
            [
                'topic' => 'Paket & Pembayaran',
                'intent_name' => 'cara_bayar',
                'keywords' => ['cara bayar', 'pembayaran', 'qris', 'transfer', 'metode bayar', 'gopay', 'dana'],
                'response' => 'Sistem pembayaran kami sangat mudah. Saat Anda memilih paket langganan, Anda bisa membayar menggunakan QRIS (mendukung semua e-Wallet seperti GoPay/OVO/Dana) atau melalui Virtual Account Bank.'
            ],

            // ================= TOPIK 3: PEMBUATAN AR & 3D =================
            [
                'topic' => 'Pembuatan AR & 3D',
                'intent_name' => 'dasar_ar',
                'keywords' => ['cara buat', 'bikin ar', 'awal', 'mulai', '2d', '3d', 'upload'],
                'response' => 'Untuk membuat AR, masuk ke menu "Buat AR". Anda bisa upload gambar 2D biasa (PNG/JPG) atau langsung mengunggah file objek 3D Anda sendiri (format .glb). Setelah itu klik Generate QR Code.'
            ],
            [
                'topic' => 'Pembuatan AR & 3D',
                'intent_name' => 'fitur_imajinasi_ai',
                'keywords' => ['ai', 'imajinasi', 'foto', 'sepatu', 'kursi', 'benda nyata', 'triposr', 'render'],
                'response' => 'Fitur "Imajinas AI" digunakan untuk mengubah foto benda nyata menjadi 3D solid! Pastikan objek difoto dengan jelas. Proses rendering AI ini memakan waktu sekitar 2 - 10 menit karena AI harus menebak sisi belakang foto Anda.'
            ],
            [
                'topic' => 'Pembuatan AR & 3D',
                'intent_name' => 'fitur_cetak_timbul',
                'keywords' => ['cetak timbul', 'extrude', 'logo', 'teks', 'akrilik', 'medali', 'cepat'],
                'response' => 'Mode "Cetak Timbul" sangat cocok untuk Logo atau Teks berformat PNG Transparan. Sistem akan memotong polanya dan memberinya ketebalan seperti medali/akrilik. Proses ini super kilat, hanya 2-5 detik!'
            ],
            [
                'topic' => 'Pembuatan AR & 3D',
                'intent_name' => 'fitur_template',
                'keywords' => ['template', 'siap pakai', 'contoh', 'gak mau ribet'],
                'response' => 'Kami menyediakan banyak Template AR siap pakai di tab "Template". Anda cukup pilih salah satu, klik "Gunakan Template Ini", dan Generate QR-nya langsung!'
            ],

            // ================= TOPIK 4: CARA SCAN & KENDALA =================
            [
                'topic' => 'Cara Scan & Kendala',
                'intent_name' => 'cara_scan',
                'keywords' => ['cara scan', 'kamera', 'cara lihat', 'munculkan ar', 'pemindai'],
                'response' => 'Sangat mudah! Buka kamera HP bawaan Anda atau gunakan aplikasi Google Lens/QR Scanner. Arahkan ke QR Code yang sudah kami buat, lalu klik link yang muncul untuk melihat AR-nya di dunia nyata!'
            ],
            [
                'topic' => 'Cara Scan & Kendala',
                'intent_name' => 'kendala_error',
                'keywords' => ['error', 'gagal', 'bug', 'macet', 'berhenti', 'stack', 'kosong', 'rusak'],
                'response' => 'Mohon maaf atas kendala ini. Jika proses macet, pastikan format file sudah benar (PNG/JPG transparan) dan ukurannya tidak terlalu besar. Anda juga bisa mencoba refresh halaman.'
            ],
            [
                'topic' => 'Cara Scan & Kendala',
                'intent_name' => 'kontak_cs',
                'keywords' => ['admin', 'kontak', 'cs', 'bantuan', 'bantu', 'hubungi'],
                'response' => 'Jika Anda butuh bantuan lebih spesifik, silakan klik tombol "Akhiri Chat & Hubungi CS" di bawah, lalu tinggalkan email Anda agar tim teknis kami bisa membantu Anda.'
            ]
        ];

        foreach ($data as $item) {
            ChatbotKnowledge::create([
                'topic' => $item['topic'],
                'intent_name' => $item['intent_name'],
                'keywords' => json_encode($item['keywords']),
                'response' => $item['response']
            ]);
        }
    }
}