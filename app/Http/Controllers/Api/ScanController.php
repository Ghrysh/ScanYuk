<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ScanController extends Controller
{
    public function scanQr($uuid)
    {
        $qr = DB::table('qr_codes')
                ->where('uuid', $uuid)
                ->where('status', 'Aktif')
                ->first();

        if (!$qr) {
            return response()->json([
                'status' => 'error', 
                'message' => 'QR Code tidak valid atau sedang dinonaktifkan.'
            ], 404);
        }

        $user = DB::table('users')->where('id', $qr->user_id)->first();

        $scanLimits = [
            'free' => 2,
            'starter' => 50,
            'professional' => 100,
            'business' => 150,
        ];
        $userRole = strtolower($user->role);
        $maxScans = $scanLimits[$userRole] ?? 0;

        if ($user->scan >= $maxScans) {
            return response()->json([
                'status' => 'limit_reached',
                'message' => 'Akun pembuat QR ini telah mencapai batas maksimal scan ('. $maxScans .').'
            ], 403);
        }

        DB::table('users')->where('id', $user->id)->increment('scan');
        DB::table('qr_codes')->where('id', $qr->id)->increment('scan_count');

        $arData = [
            'title' => $qr->title,
            'ar_type' => $qr->ar_type ?? '2d',
            'narration' => $qr->narration,
            'ai_voice' => $qr->ai_voice,
            'custom_audio_url' => $qr->custom_audio_path,
            'image_url' => $qr->image_path ? asset('storage/' . $qr->image_path) : null,
            'file_3d_url' => null,
            'bgm_url' => $qr->bgm_path ? asset('bg_sounds/' . $qr->bgm_path) : null,
        ];

        if ($arData['ar_type'] === '3d' && !empty($qr->ar_asset_id)) {
            $asset = DB::table('ar_assets')->where('id', $qr->ar_asset_id)->first();
            $arData['file_3d_url'] = $asset ? $asset->file_path : null;
        }

        return response()->json([
            'status' => 'success',
            'data' => $arData
        ]);
    }
}