<?php

namespace App\Http\Controllers;

use App\Models\PricingPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $packages = PricingPackage::orderBy('id', 'asc')->get();

        $roleMap = [
            'free' => 1,
            'starter' => 2,
            'professional' => 3,
            'business' => 4
        ];
        
        $packageId = $roleMap[strtolower($user->role)] ?? 1;
        $currentPackage = PricingPackage::find($packageId);

        if (!$currentPackage && $packages->isNotEmpty()) {
            $currentPackage = $packages->first();
        }

        $qrCodes = $user->qrCodes()->latest()->get();

        return view('dashboard.user', compact('user', 'packages', 'currentPackage', 'qrCodes'));
    }

    public function startConversion(Request $request)
    {
        $request->validate(['image' => 'required|image|mimes:jpeg,png,jpg|max:5120']);

        $imagePath = $request->file('image')->store('ai_inputs', 'public');
        $fullInputPath = storage_path('app/public/' . $imagePath);

        $job = \App\Models\AiConversion::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id() ?? 1,
            'status' => 'processing',
            'progress' => 0,
        ]);

        $outputDir = storage_path('app/public/ai_outputs');
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0777, true);
        }
        $fullOutputPath = $outputDir . '/job_' . $job->id . '.glb';

        $scriptDir = base_path('TripoSR');
        $scriptPath = $scriptDir . '/run_triposr.py';
        
        $command = "cd " . escapeshellarg($scriptDir) . " && nohup python3 " . escapeshellarg($scriptPath) . " " . escapeshellarg($fullInputPath) . " " . escapeshellarg($fullOutputPath) . " > /dev/null 2>&1 &";
        
        exec($command);

        return response()->json(['success' => true, 'job_id' => $job->id]);
    }

    public function checkStatus($id)
    {
        $job = \App\Models\AiConversion::findOrFail($id);
        
        if ($job->status === 'completed' || $job->status === 'failed') {
            return response()->json([
                'status' => $job->status, 'progress' => $job->progress,
                'time_remaining' => $job->status === 'completed' ? 'Selesai!' : 'Gagal',
                'result_url' => $job->result_url
            ]);
        }

        $fullOutputPath = storage_path('app/public/ai_outputs/job_' . $job->id . '.glb');
        $errorOutputPath = $fullOutputPath . '.error';

        if (file_exists($fullOutputPath)) {
            $job->status = 'completed';
            $job->progress = 100;
            $job->result_url = asset('storage/ai_outputs/job_' . $job->id . '.glb');
            $job->save();

            return response()->json([
                'status' => 'completed', 'progress' => 100,
                'time_remaining' => 'Selesai!', 'result_url' => $job->result_url
            ]);
        }

        if (file_exists($errorOutputPath)) {
            $job->status = 'failed';
            $job->save();
            return response()->json([
                'status' => 'failed', 'progress' => 0, 'time_remaining' => 'Terjadi kesalahan sistem AI.'
            ]);
        }

        $waktuRender = 600;

        $detikBerjalan = now()->timestamp - $job->created_at->timestamp;
        
        if ($detikBerjalan < 0) {
            $detikBerjalan = 0; 
        }

        $progress = min(99, round(($detikBerjalan / $waktuRender) * 100));
        
        $sisaDetik = max(0, $waktuRender - $detikBerjalan);
        $sisaWaktuFormat = sprintf('%02d:%02d', floor($sisaDetik / 60), $sisaDetik % 60);

        return response()->json([
            'status' => 'processing',
            'progress' => $progress,
            'time_remaining' => 'AI sedang merender (' . $sisaWaktuFormat . ')'
        ]);
    }
}