<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ScanController extends Controller
{
    public function scanQr($uuid)
    {
        // 1. Tambahkan parameter default untuk scan versi Demo
        if ($uuid === 'demo-scanyuk') {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'title' => 'Demo Augmented Reality ScanYuk',
                    'ar_type' => '3d',
                    'narration' => 'Selamat datang, ciptakan kreasi AR mu sendiri',
                    'ai_voice' => null,
                    'custom_audio_url' => null,
                    'image_url' => null,
                    'file_3d_url' => url('/demo/logo.glb'),
                    'bgm_url' => url('/demo/future.mp3'),
                    
                    // Default transform untuk demo
                    'scale' => 1,
                    'position' => '[0,0,0]',
                    'rotation' => '[0,0,0]',
                    'orbit_active' => false,
                    'anim_clip' => '*'
                ]
            ]);
        }

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
        
        $userRole = strtolower($user->role);

        if ($userRole !== 'admin') {
            
            $roleMap = [
                'free' => 1,
                'starter' => 2,
                'professional' => 3,
                'business' => 4
            ];
            $packageId = $roleMap[$userRole] ?? 1;
            $package = \App\Models\PricingPackage::find($packageId);
            
            $isUnlimited = false;
            $maxScans = 0;

            if ($package && is_array($package->features) && isset($package->features[2])) {
                $featureText = strtolower($package->features[2]);
                if (str_contains($featureText, 'tak terbatas') || str_contains($featureText, 'unlimited')) {
                    $isUnlimited = true;
                } else {
                    $maxScans = (int) filter_var($package->features[2], FILTER_SANITIZE_NUMBER_INT);
                }
            }

            if (!$isUnlimited && $user->scan >= $maxScans) {
                return response()->json([
                    'status' => 'limit_reached',
                    'message' => 'Akun pembuat QR ini telah mencapai batas maksimal scan.'
                ], 403);
            }

            DB::table('users')->where('id', $user->id)->increment('scan');
        }

        DB::table('qr_codes')->where('id', $qr->id)->increment('scan_count');

        $bgmUrl = null;
        if (!empty($qr->bgm_path)) {
            $path = $qr->bgm_path;
            
            $path = str_replace('/minio-proxy/bg_sounds/minio-proxy/bg_sounds/', '/minio-proxy/bg_sounds/', $path);
            
            if (str_starts_with($path, 'http')) {
                $bgmUrl = $path;
            } 
            elseif (str_starts_with($path, '/minio-proxy')) {
                $bgmUrl = url($path);
            } 
            else {
                $parts = explode('#t=', $path);
                $filename = basename($parts[0]);
                $crop = isset($parts[1]) ? '#t=' . $parts[1] : '';
                $bgmUrl = url('/minio-proxy/bg_sounds/' . $filename) . $crop;
            }
        }

        // 2. PERBAIKAN: Masukkan atribut transform ke array output
        $arData = [
            'title' => $qr->title,
            'ar_type' => $qr->ar_type ?? '2d',
            'narration' => $qr->narration,
            'ai_voice' => $qr->ai_voice,
            'custom_audio_url' => $qr->custom_audio_path,
            'image_url' => $qr->image_path ? asset('storage/' . $qr->image_path) : null,
            'file_3d_url' => null,
            'bgm_url' => $bgmUrl,
            
            // Atribut Transform & Animasi
            'scale' => $qr->scale ?? 1,
            'position' => $qr->position ?? '[0,0,0]',
            'rotation' => $qr->rotation ?? '[0,0,0]',
            'orbit_active' => $qr->orbit_active ?? 0,
            'orbit_speed' => $qr->orbit_speed ?? 0.5,
            'orbit_radius' => $qr->orbit_radius ?? 1.5,
            'orbit_dir' => $qr->orbit_dir ?? 1,
            'anim_clip' => $qr->anim_clip ?? '*',
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