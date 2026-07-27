<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TamagotchiSession;
use App\Models\TamagotchiJourney;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class TamagotchiController extends Controller
{
    /**
     * Register atau Login sesi tamagotchi dengan username dan password
     */
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|alpha_dash|max:50',
            'password' => 'required|string|min:4',
            'qr_uuid' => 'required|string',
        ]);

        $username = strtolower(trim($request->username));
        
        // Cari QR Code berdasarkan UUID
        $qr = DB::table('qr_codes')->where('uuid', $request->qr_uuid)->first();
        if (!$qr) {
            return response()->json([
                'status' => 'error',
                'message' => 'QR Code tidak ditemukan.'
            ], 404);
        }

        // Cek apakah username sudah ada secara global
        $session = TamagotchiSession::where('username', $username)->first();

        if ($session) {
            // Jika username ada tapi milik QR Code lain, tolak! (1 Akun = 1 QR Code)
            if ($session->qr_code_id != $qr->id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Username ini sudah digunakan. Silakan pilih nama lain.'
                ], 403);
            }

            // Login - cek password
            if (!Hash::check($request->password, $session->password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Password salah untuk username ini.'
                ], 401);
            }

            // Hitung depletion berdasarkan waktu tidur/bangun
            $session->exp_points = $this->calculateNewExp($session->exp_points, $session->last_active_at);

            // Sesi sudah ada — increment scan
            $session->update([
                'total_scans' => $session->total_scans + 1,
                'exp_points' => $session->exp_points,
                'last_active_at' => now(),
            ]);
        } else {
            // Register - Sesi baru
            $session = TamagotchiSession::create([
                'qr_code_id' => $qr->id,
                'username' => $username,
                'password' => Hash::make($request->password),
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
                'username' => $session->username,
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
            'exp_points' => min(100, max(0, $request->exp_points)),
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

        $locationName = null;
        if ($request->lat && $request->lon) {
            try {
                $response = Http::withHeaders(['User-Agent' => 'ScanYuk/1.0'])->timeout(3)->get('https://nominatim.openstreetmap.org/reverse', [
                    'lat' => $request->lat,
                    'lon' => $request->lon,
                    'format' => 'jsonv2'
                ]);
                if ($response->successful()) {
                    $data = $response->json();
                    $address = $data['address'] ?? [];
                    $city = $address['city'] ?? $address['town'] ?? $address['village'] ?? $address['county'] ?? '';
                    $state = $address['state'] ?? '';
                    $locationName = trim($city . ($city && $state ? ', ' : '') . $state, ', ');
                }
            } catch (\Exception $e) {}
        }

        $journey = TamagotchiJourney::create([
            'session_id' => $session->id,
            'status_text' => $request->status_text,
            'mood' => $request->mood ?? 'senang',
            'exp_points' => min(100, max(0, $request->exp_points ?? $session->exp_points)),
            'lat' => $request->lat,
            'lon' => $request->lon,
            'location_name' => $locationName,
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
                    'username' => $session->username,
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
                        'location_name' => $j->location_name,
                        'date' => $j->created_at->format('d M Y'),
                        'time' => $j->created_at->format('H:i'),
                        'relative' => $j->created_at->diffForHumans(),
                    ];
                }),
            ]
        ]);
    }

    /**
     * Cek apakah username sudah punya sesi untuk QR tertentu
     * Digunakan untuk auto-login dari localStorage
     */
    public function checkSession(Request $request)
    {
        $request->validate([
            'session_id' => 'required|integer',
            'username' => 'required|string',
        ]);

        $username = strtolower(trim($request->username));

        $session = TamagotchiSession::where('id', $request->session_id)
            ->where('username', $username)
            ->first();

        if (!$session) {
            return response()->json(['status' => 'error', 'message' => 'Session not found.'], 404);
        }

        // Hitung depletion dan increment scan karena user me-load ulang halaman (dianggap scan baru)
        if ($session->last_active_at) {
            $session->exp_points = $this->calculateNewExp($session->exp_points, $session->last_active_at);
            $session->total_scans = $session->total_scans + 1;
            
            $session->last_active_at = now();
            $session->save();
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'session_id' => $session->id,
                'username' => $session->username,
                'exp_points' => round($session->exp_points, 2),
                'total_scans' => $session->total_scans,
                'journey_count' => $session->journeys()->count(),
            ]
        ]);
    }

    /**
     * Kalkulasi EXP baru berdasarkan waktu berlalu.
     * Bangun (06:00 - 21:00): EXP berkurang agar habis dalam 15 jam (~6.66/jam)
     * Tidur (21:00 - 06:00): EXP bertambah 1 poin per jam
     */
    private function calculateNewExp($currentExp, $lastActiveAt)
    {
        if (!$lastActiveAt) return $currentExp;

        $now = now();
        if ($lastActiveAt->greaterThanOrEqualTo($now)) return $currentExp;

        $exp = $currentExp;
        $time = clone $lastActiveAt;

        // Simulasi per jam (atau fraksi) untuk akurasi perbedaan siang/malam
        while ($time->lessThan($now)) {
            $nextHour = (clone $time)->addHour()->startOfHour();
            if ($nextHour->greaterThan($now)) {
                $nextHour = clone $now;
            }

            $hoursDiff = $time->diffInSeconds($nextHour) / 3600;
            $hourOfDay = $time->hour;

            if ($hourOfDay >= 21 || $hourOfDay < 6) {
                // Tidur: +1 per jam
                $exp += (1.0 * $hoursDiff);
            } else {
                // Bangun: -6.66 per jam
                $exp -= (6.666 * $hoursDiff);
            }

            $time = clone $nextHour;
        }

        return min(100, max(0, $exp));
    }
}
