<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TamagotchiSession;
use App\Models\TamagotchiJourney;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TamagotchiController extends Controller
{
    /**
     * Step 1: Kirim OTP ke nomor telepon (disimpan di cache)
     * Karena ini untuk pengunjung publik, OTP disimpan di server-side cache
     * dan diverifikasi di step 2 (verify).
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|min:10|max:20',
        ]);

        $phone = preg_replace('/[^0-9+]/', '', $request->phone);
        $otp = rand(100000, 999999);

        // Simpan OTP di cache selama 5 menit
        Cache::put('tama_otp_' . $phone, $otp, 300);

        // NOTE: Di production, kirim OTP via WhatsApp API / SMS gateway
        // Untuk sementara, OTP ditampilkan langsung di response (development mode)
        // TODO: Ganti dengan integrasi WhatsApp Business API
        return response()->json([
            'status' => 'success',
            'message' => 'Kode OTP telah dikirim.',
            // DEVELOPMENT ONLY — hapus di production
            'dev_otp' => app()->environment('local', 'development') ? $otp : null,
            // Selalu kirim OTP untuk sekarang (belum ada SMS gateway)
            '_otp' => $otp,
        ]);
    }

    /**
     * Step 2: Verifikasi OTP dan register/login sesi tamagotchi
     */
    public function register(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|min:10|max:20',
            'otp' => 'required|numeric|digits:6',
            'display_name' => 'required|string|max:100',
            'qr_uuid' => 'required|string',
        ]);

        $phone = preg_replace('/[^0-9+]/', '', $request->phone);
        
        // Verifikasi OTP
        $cachedOtp = Cache::get('tama_otp_' . $phone);
        if (!$cachedOtp || (string)$cachedOtp !== (string)$request->otp) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kode OTP salah atau sudah kadaluarsa.'
            ], 422);
        }

        // Hapus OTP setelah berhasil
        Cache::forget('tama_otp_' . $phone);

        // Cari QR Code berdasarkan UUID
        $qr = DB::table('qr_codes')->where('uuid', $request->qr_uuid)->first();
        if (!$qr) {
            return response()->json([
                'status' => 'error',
                'message' => 'QR Code tidak ditemukan.'
            ], 404);
        }

        // Cek apakah sesi sudah ada untuk phone + qr_code_id
        $session = TamagotchiSession::where('qr_code_id', $qr->id)
            ->where('phone', $phone)
            ->first();

        if ($session) {
            // Sesi sudah ada — update nama dan increment scan
            $session->update([
                'display_name' => $request->display_name,
                'total_scans' => $session->total_scans + 1,
                'last_active_at' => now(),
            ]);

            // Hitung depletion berdasarkan waktu terakhir aktif
            if ($session->last_active_at) {
                $diffMs = now()->diffInMilliseconds($session->last_active_at);
                $pointsToDeduct = $diffMs * 0.0000005787; // 48 jam = 100 poin
                $session->exp_points = max(0, $session->exp_points - $pointsToDeduct);
                $session->save();
            }
        } else {
            // Sesi baru
            $session = TamagotchiSession::create([
                'qr_code_id' => $qr->id,
                'phone' => $phone,
                'display_name' => $request->display_name,
                'exp_points' => 100,
                'total_scans' => 1,
                'last_active_at' => now(),
            ]);

            // Buat journey entry pertama otomatis
            TamagotchiJourney::create([
                'session_id' => $session->id,
                'status_text' => '🎉 Pertama kali bertemu!',
                'mood' => 'senang',
                'exp_points' => 100,
                'lat' => $request->lat,
                'lon' => $request->lon,
            ]);
        }

        $journeyCount = $session->journeys()->count();

        return response()->json([
            'status' => 'success',
            'data' => [
                'session_id' => $session->id,
                'display_name' => $session->display_name,
                'exp_points' => round($session->exp_points, 2),
                'total_scans' => $session->total_scans,
                'journey_count' => $journeyCount,
                'is_returning' => $session->wasRecentlyCreated ? false : true,
            ]
        ]);
    }

    /**
     * Sinkronisasi data tamagotchi dari client ke server (dipanggil periodik)
     */
    public function sync(Request $request)
    {
        $request->validate([
            'session_id' => 'required|integer',
            'exp_points' => 'required|numeric|min:0|max:100',
            'mood' => 'nullable|string|max:20',
            'lat' => 'nullable|numeric',
            'lon' => 'nullable|numeric',
        ]);

        $session = TamagotchiSession::find($request->session_id);
        if (!$session) {
            return response()->json(['status' => 'error', 'message' => 'Session not found.'], 404);
        }

        $session->update([
            'exp_points' => $request->exp_points,
            'last_lat' => $request->lat,
            'last_lon' => $request->lon,
            'last_active_at' => now(),
        ]);

        return response()->json(['status' => 'success']);
    }

    /**
     * Simpan entri journey baru
     */
    public function journal(Request $request)
    {
        $request->validate([
            'session_id' => 'required|integer',
            'status_text' => 'required|string|max:255',
            'mood' => 'nullable|string|max:20',
            'exp_points' => 'nullable|numeric',
            'lat' => 'nullable|numeric',
            'lon' => 'nullable|numeric',
        ]);

        $session = TamagotchiSession::find($request->session_id);
        if (!$session) {
            return response()->json(['status' => 'error', 'message' => 'Session not found.'], 404);
        }

        $journey = TamagotchiJourney::create([
            'session_id' => $session->id,
            'status_text' => $request->status_text,
            'mood' => $request->mood ?? 'senang',
            'exp_points' => $request->exp_points ?? $session->exp_points,
            'lat' => $request->lat,
            'lon' => $request->lon,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $journey
        ]);
    }

    /**
     * Ambil semua journey entries untuk session tertentu
     */
    public function getJourney($sessionId)
    {
        $session = TamagotchiSession::with(['journeys' => function($q) {
            $q->orderBy('created_at', 'desc');
        }])->find($sessionId);

        if (!$session) {
            return response()->json(['status' => 'error', 'message' => 'Session not found.'], 404);
        }

        // Ambil juga info QR code
        $qr = DB::table('qr_codes')->where('id', $session->qr_code_id)->first();

        return response()->json([
            'status' => 'success',
            'data' => [
                'session' => [
                    'id' => $session->id,
                    'display_name' => $session->display_name,
                    'phone' => substr($session->phone, 0, 4) . '****' . substr($session->phone, -3),
                    'exp_points' => round($session->exp_points, 2),
                    'total_scans' => $session->total_scans,
                    'created_at' => $session->created_at->format('d M Y'),
                    'last_active' => $session->last_active_at ? $session->last_active_at->diffForHumans() : '-',
                ],
                'qr_title' => $qr->title ?? 'AR Experience',
                'journeys' => $session->journeys->map(function($j) {
                    return [
                        'id' => $j->id,
                        'status_text' => $j->status_text,
                        'mood' => $j->mood,
                        'exp_points' => round($j->exp_points, 2),
                        'date' => $j->created_at->format('d M Y'),
                        'time' => $j->created_at->format('H:i'),
                        'relative' => $j->created_at->diffForHumans(),
                    ];
                }),
            ]
        ]);
    }

    /**
     * Cek apakah nomor telepon sudah punya sesi untuk QR tertentu (tanpa OTP)
     * Digunakan untuk auto-login dari localStorage
     */
    public function checkSession(Request $request)
    {
        $request->validate([
            'session_id' => 'required|integer',
            'phone' => 'required|string',
        ]);

        $phone = preg_replace('/[^0-9+]/', '', $request->phone);

        $session = TamagotchiSession::where('id', $request->session_id)
            ->where('phone', $phone)
            ->first();

        if (!$session) {
            return response()->json(['status' => 'error', 'message' => 'Session not found.'], 404);
        }

        // Hitung depletion
        if ($session->last_active_at) {
            $diffMs = now()->diffInMilliseconds($session->last_active_at);
            $pointsToDeduct = $diffMs * 0.0000005787;
            $session->exp_points = max(0, $session->exp_points - $pointsToDeduct);
            $session->last_active_at = now();
            $session->save();
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'session_id' => $session->id,
                'display_name' => $session->display_name,
                'exp_points' => round($session->exp_points, 2),
                'total_scans' => $session->total_scans,
                'journey_count' => $session->journeys()->count(),
            ]
        ]);
    }
}
