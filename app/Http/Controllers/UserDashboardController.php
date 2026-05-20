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
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'mode' => 'required|in:ai,extrude'
        ]);

        $imagePath = $request->file('image')->store('ai_inputs', 'public');
        $fullInputPath = storage_path('app/public/' . $imagePath);
        $mode = $request->mode;

        $job = \App\Models\AiConversion::create([
            'user_id' => Auth::id() ?? 1,
            'status' => 'processing',
            'progress' => 0,
        ]);

        $outputDir = storage_path('app/public/ai_outputs');
        if (!file_exists($outputDir)) mkdir($outputDir, 0777, true);
        
        $fullOutputPath = $outputDir . '/job_' . $job->id . '.glb';
        $scriptDir = base_path('TripoSR');
        
        // Pilih script berdasarkan opsi mode dari user
        $scriptName = ($mode === 'extrude') ? 'run_extrude.py' : 'run_triposr.py';
        $scriptPath = $scriptDir . '/' . $scriptName;

        $logPath = storage_path('logs/python_ai.log');
        $hfHome = storage_path('app/public/ai_models');
        if (!file_exists($hfHome)) mkdir($hfHome, 0777, true);

        $command = "export U2NET_HOME=" . escapeshellarg($hfHome) . " && export HF_HOME=" . escapeshellarg($hfHome) . " && cd " . escapeshellarg($scriptDir) . " && nohup python3 " . escapeshellarg($scriptPath) . " " . escapeshellarg($fullInputPath) . " " . escapeshellarg($fullOutputPath) . " > " . escapeshellarg($logPath) . " 2>&1 &";
        
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
        $progressPath = $fullOutputPath . '.progress';

        if (file_exists($fullOutputPath) && filesize($fullOutputPath) > 0) {
            $job->status = 'completed';
            $job->progress = 100;
            $job->result_url = asset('storage/ai_outputs/job_' . $job->id . '.glb');
            $job->save();

            if (file_exists($progressPath)) @unlink($progressPath);

            return response()->json([
                'status' => 'completed', 'progress' => 100,
                'time_remaining' => 'Selesai!', 'result_url' => $job->result_url
            ]);
        }

        if (file_exists($errorOutputPath)) {
            $job->status = 'failed';
            $job->save();
            return response()->json([
                'status' => 'failed', 'progress' => 0, 'time_remaining' => 'Terjadi kesalahan sistem.'
            ]);
        }

        $detikBerjalan = now()->timestamp - $job->created_at->timestamp;
        if ($detikBerjalan < 1) $detikBerjalan = 1; 

        $progress = 0;
        $statusText = "Memulai sistem...";

        if (file_exists($progressPath)) {
            $progData = json_decode(file_get_contents($progressPath), true);
            if ($progData) {
                $progress = $progData['progress'] ?? 5;
                $statusText = $progData['text'] ?? $statusText;
            }
        } else {
            $progress = min(5, round($detikBerjalan / 2));
        }

        if ($progress > 0 && $progress < 100) {
            $estimasiTotalDetik = ($detikBerjalan / $progress) * 100;
            $sisaDetik = round($estimasiTotalDetik - $detikBerjalan);
            if ($sisaDetik < 0) $sisaDetik = 0;

            if ($sisaDetik > 60) {
                $sisaWaktuFormat = $statusText . " (~" . floor($sisaDetik / 60) . "m " . ($sisaDetik % 60) . "s)";
            } else {
                $sisaWaktuFormat = $statusText . " (~" . $sisaDetik . " detik)";
            }
        } else {
            $sisaWaktuFormat = $statusText;
        }

        $job->progress = $progress;
        $job->save();

        return response()->json([
            'status' => 'processing',
            'progress' => $progress,
            'time_remaining' => $sisaWaktuFormat
        ]);
    }

    public function removeBackground(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $imagePath = $request->file('image')->store('bg_inputs', 'public');
        $fullInputPath = storage_path('app/public/' . $imagePath);

        $outputDir = storage_path('app/public/bg_outputs');
        if (!file_exists($outputDir)) mkdir($outputDir, 0777, true);

        $outputFilename = 'nobg_' . uniqid() . '.png';
        $fullOutputPath = $outputDir . '/' . $outputFilename;

        $scriptDir = base_path('TripoSR');
        $scriptPath = $scriptDir . '/remove_bg.py';
        $hfHome = storage_path('app/public/ai_models');

        $command = "export U2NET_HOME=" . escapeshellarg($hfHome) . 
                   " && export HF_HOME=" . escapeshellarg($hfHome) . 
                   " && export NUMBA_CACHE_DIR=/tmp " .
                   " && export MPLCONFIGDIR=/tmp " .
                   " && export XDG_CACHE_HOME=/tmp " .
                   " && cd " . escapeshellarg($scriptDir) . 
                   " && python3 " . escapeshellarg($scriptPath) . 
                   " " . escapeshellarg($fullInputPath) . 
                   " " . escapeshellarg($fullOutputPath) . " 2>&1";
        
        exec($command, $output, $return_var);

        if (file_exists($fullOutputPath)) {
            return response()->json([
                'success' => true,
                'image_url' => asset('storage/bg_outputs/' . $outputFilename)
            ]);
        }

        return response()->json([
            'success' => false, 
            'message' => 'Gagal menghapus background',
            'python_log' => $output,
            'exit_code' => $return_var
        ], 500);
    }
}