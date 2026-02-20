<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ScanController extends Controller
{
    public function scanQr($uuid)
    {
        $qr = DB::table('qr_codes')->where('uuid', $uuid)->where('is_active', true)->first();

        if (!$qr) {
            return response()->json(['status' => 'error', 'message' => 'QR Code tidak valid atau dinonaktifkan.'], 404);
        }

        $user = DB::table('users')->where('id', $qr->user_id)->first();

        $scanLimits = [
            'free' => 2,
            'starter' => 50,
            'professional' => 100,
            'business' => 150,
        ];

        $maxScans = $scanLimits[$user->role] ?? 0;

        if ($user->total_scans >= $maxScans) {
            return response()->json([
                'status' => 'limit_reached',
                'message' => 'Akun pembuat QR ini telah mencapai batas maksimal scan ('. $maxScans .'). Upgrade paket untuk scan lebih lanjut.'
            ], 403);
        }

        DB::table('users')->where('id', $user->id)->increment('total_scans');

        return response()->json([
            'status' => 'success',
            'data' => [
                'title' => $qr->title,
                'image_url' => asset('storage/' . $qr->image_path),
                'narration' => $qr->narration
            ]
        ]);
    }
}