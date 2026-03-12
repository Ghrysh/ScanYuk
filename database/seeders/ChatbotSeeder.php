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
                'intent_name' => 'cara_login',
                'keywords' => ['login', 'masuk', 'sign in', 'cara login', 'loginnya', 'masuknya'],
                'response' => 'Untuk login, silakan klik tulisan "Masuk" di menu navigasi atas. Masukkan email dan password yang telah didaftarkan ya!'
            ],
            [
                'topic' => 'Akun & Login',
                'intent_name' => 'registrasi',
                'keywords' => ['daftar', 'register', 'buat akun', 'sign up', 'bikin akun', 'belum punya', 'akun baru'],
                'response' => 'Untuk mendaftar, klik tombol "Daftar". Anda hanya perlu mengisi Nama, Email, Password, dan memasukkan 6 digit OTP yang akan kami kirim ke email Anda.'
            ],
            [
                'topic' => 'Akun & Login',
                'intent_name' => 'lupa_password',
                'keywords' => ['lupa password', 'sandi', 'reset', 'ganti password', 'tidak bisa masuk', 'gagal login', 'lupa'],
                'response' => 'Jika Anda lupa password, klik tulisan "Lupa Password?" di halaman Login. Kami akan mengirimkan tautan reset password langsung ke email Anda.'
            ],

            // ================= TOPIK 2: PAKET & PEMBAYARAN =================
            [
                'topic' => 'Paket & Pembayaran',
                'intent_name' => 'harga_paket',
                'keywords' => ['harga', 'paket', 'premium', 'gratis', 'free', 'berlangganan', 'biaya', 'upgrade', 'beli', 'berbayar'],
                'response' => 'Kami punya paket Free (Gratis)! Jika butuh lebih banyak kesempatan untuk membuat AR, silakan upgrade ke Pemula, Professional, atau Bisnis.'
            ],
            [
                'topic' => 'Paket & Pembayaran',
                'intent_name' => 'cara_bayar',
                'keywords' => ['cara bayar', 'pembayaran', 'qris', 'transfer', 'metode bayar', 'gopay', 'dana', 'metode'],
                'response' => 'Pembayaran sangat mudah! Saat Anda klik beli paket, Anda bisa membayar menggunakan QRIS (semua e-Wallet seperti GoPay/OVO/Dana) atau via Virtual Account Bank.'
            ],

            // ================= TOPIK 3: PEMBUATAN AR & 3D =================
            [
                'topic' => 'Pembuatan AR & 3D',
                'intent_name' => 'dasar_ar',
                'keywords' => ['cara buat', 'bikin ar', 'awal', 'mulai', '2d', '3d', 'upload', 'cara pakai'],
                'response' => 'Masuk ke menu "Buat AR". Anda bisa upload gambar 2D biasa (PNG/JPG) atau langsung unggah file 3D (.glb). Tambahkan audio jika perlu, lalu klik Generate QR Code.'
            ],
            [
                'topic' => 'Pembuatan AR & 3D',
                'intent_name' => 'fitur_imajinasi_ai',
                'keywords' => ['ai', 'imajinasi', 'foto', 'sepatu', 'kursi', 'benda nyata', 'triposr', 'render', 'lama'],
                'response' => 'Mode "Imajinas AI" mengubah foto benda nyata menjadi 3D utuh! AI harus merakit sisi belakang foto Anda, jadi proses render memakan waktu sekitar 2 - 10 menit.'
            ],
            [
                'topic' => 'Pembuatan AR & 3D',
                'intent_name' => 'fitur_cetak_timbul',
                'keywords' => ['cetak timbul', 'extrude', 'logo', 'teks', 'akrilik', 'medali', 'cepat', 'ikon'],
                'response' => 'Mode "Cetak Timbul" paling cocok untuk Logo/Teks berformat PNG Transparan. Sistem memotong pola dan memberinya ketebalan. Sangat cepat, hanya 2-5 detik!'
            ],
            [
                'topic' => 'Pembuatan AR & 3D',
                'intent_name' => 'fitur_template',
                'keywords' => ['template', 'siap pakai', 'contoh', 'gak mau ribet', 'instan'],
                'response' => 'Pilih tab "Template" di halaman Buat AR. Tersedia banyak desain siap pakai. Cukup klik "Gunakan Template Ini", lalu Generate QR-nya langsung!'
            ],

            // ================= TOPIK 4: CARA SCAN & KENDALA =================
            [
                'topic' => 'Cara Scan & Kendala',
                'intent_name' => 'cara_scan',
                'keywords' => ['cara scan', 'kamera', 'cara lihat', 'munculkan ar', 'pemindai', 'scan qr'],
                'response' => 'Sangat Mudah! Anda bisa klik tombol "Mulai Scan" di halaman utama, atau Buka kamera HP Anda atau aplikasi Google Lens. Arahkan ke QR Code yang Anda buat, klik link yang muncul di layar, dan AR akan tampil di dunia nyata!'
            ],
            [
                'topic' => 'Cara Scan & Kendala',
                'intent_name' => 'kendala_error',
                'keywords' => ['error', 'gagal', 'bug', 'macet', 'berhenti', 'stack', 'kosong', 'rusak', 'tidak bisa'],
                'response' => 'Maaf atas kendalanya! Jika macet, pastikan format gambar PNG/JPG, hapus background rumit, atau turunkan ukuran file. Cobalah refresh halaman Anda.'
            ],

            // ================= TOPIK UMUM (GLOBAL) =================
            [
                'topic' => 'Umum',
                'intent_name' => 'sapaan',
                'keywords' => ['halo', 'hai', 'pagi', 'siang', 'sore', 'malam', 'ping', 'hei', 'helo'],
                'response' => 'Halo kembali! Silakan tanyakan hal yang ingin Anda ketahui, ScanYuk Bot siap membantu.'
            ],
            [
                'topic' => 'Umum',
                'intent_name' => 'terima_kasih',
                'keywords' => ['terimakasih', 'makasih', 'terima kasih', 'makasi', 'tq', 'thanks', 'thank you', 'nuhun', 'oke', 'sip', 'mantap', 'baik', 'oke min'],
                'response' => 'Sama-sama! Senang bisa membantu Anda. Jika tidak ada yang ditanyakan lagi, silakan klik "Akhiri Chat & Hubungi CS" agar tim kami punya data Anda, atau klik tombol New Chat di atas untuk membahas topik lain.'
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