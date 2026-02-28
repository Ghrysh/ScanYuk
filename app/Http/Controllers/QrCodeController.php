<?php

namespace App\Http\Controllers;

use App\Models\QrCode as QrCodeModel;
use App\Models\ArAsset;
use App\Models\ArTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class QrCodeController extends Controller
{
    public function create()
    {
        try {
            $allFiles = Storage::disk('s3')->allFiles();
            
            $glbFiles = array_filter($allFiles, function($file) {
                return Str::endsWith(strtolower($file), '.glb');
            });

            foreach ($glbFiles as $file) {
                $fileUrl = url('/minio-proxy/' . ltrim($file, '/'));

                $filename = basename($file);
                $cleanName = preg_replace('/^[0-9]+_/', '', $filename);
                $cleanName = ucwords(str_replace(['-', '_', '.glb'], [' ', ' ', ''], $cleanName));

                ArAsset::updateOrCreate(
                    ['name' => $cleanName],
                    [
                        'user_id' => auth()->id() ?? 1, 
                        'file_path' => $fileUrl,
                        'is_public' => true,
                    ]
                );
            }
        } catch (\Exception $e) {
            // Error handling
        }

        $library3dList = ArAsset::where('is_public', true)->get(['id', 'name', 'file_path as path'])->map(function($item) {
            if (empty($item->name)) {
                $filename = basename($item->path);
                $cleanName = preg_replace('/^[0-9]+_/', '', $filename);
                $item->name = ucwords(str_replace(['-', '_', '.glb'], [' ', ' ', ''], $cleanName));
            }
            return $item;
        })->unique('name')->values();

        $templates = ArTemplate::all();

        $musicList = [];
        try {
            $musicFiles = Storage::disk('s3')->files('bg_sounds');
            foreach ($musicFiles as $file) {
                $fileName = basename($file);
                $cleanName = ucwords(str_replace(['-', '.mp3', '_', '.wav', '.ogg'], [' ', '', ' ', '', ''], $fileName));
                $musicList[] = ['name' => $cleanName, 'path' => $fileName];
            }
        } catch (\Exception $e) {
        }

        return view('dashboard.create-ar', compact('library3dList', 'musicList', 'templates'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'ar_type' => 'required|in:2d,3d',
                'image' => 'required_if:ar_type,2d|image|mimes:jpeg,png,jpg|max:5120', 
                'file_3d' => 'nullable|file|max:51200',
                'asset_name' => 'nullable|string|max:100', 
                
                'narration_mode' => 'nullable|in:text,audio',
                'narration' => 'nullable|string',
                'ai_voice' => 'nullable|string',
                'custom_audio' => 'nullable|file|max:10240',
                
                'custom_bgm' => 'nullable|file|mimes:mp3,wav,ogg,m4a|max:15360',
                'bgm_start' => 'nullable|numeric',
                'bgm_end' => 'nullable|numeric',
            ]);

            $qrCode = new QrCodeModel(); 
            $qrCode->user_id = auth()->id();
            $qrCode->title = $request->title;
            $qrCode->ar_type = $request->ar_type;

            $bgmPathToSave = $request->bgm_path; 

            if ($request->hasFile('custom_bgm')) {
                $bgmFile = $request->file('custom_bgm');
                $ext = $bgmFile->getClientOriginalExtension() ?: 'mp3';
                $bgmName = time() . '_bgm_' . Str::random(5) . '.' . $ext;
                
                $bgmContent = file_get_contents($bgmFile->getRealPath());
                $uploadBgm = Storage::disk('s3')->put('bg_sounds/' . $bgmName, $bgmContent);
                if (!$uploadBgm) throw new \Exception("Gagal menyimpan BGM ke MinIO.");
                
                $bgmPathToSave = $bgmName; 
            }

            if ($bgmPathToSave && $request->filled('bgm_start') && $request->filled('bgm_end')) {
                $bgmPathToSave .= '#t=' . $request->bgm_start . ',' . $request->bgm_end;
            }
            $qrCode->bgm_path = $bgmPathToSave;

            if ($request->narration_mode === 'audio' && $request->hasFile('custom_audio')) {
                $audioFile = $request->file('custom_audio');
                $ext = $audioFile->getClientOriginalExtension() ?: 'webm';
                $audioName = time() . '_voice_' . Str::random(5) . '.' . $ext;
                
                $audioContent = file_get_contents($audioFile->getRealPath());
                $uploadAudio = Storage::disk('s3')->put('custom_voices/' . $audioName, $audioContent);
                if (!$uploadAudio) throw new \Exception("Gagal menyimpan rekaman ke MinIO.");
                
                $qrCode->custom_audio_path = url('/minio-proxy/custom_voices/' . $audioName);
                $qrCode->narration = null;
                $qrCode->ai_voice = null;
            } else {
                $qrCode->narration = $request->narration;
                $qrCode->ai_voice = $request->ai_voice;
                $qrCode->custom_audio_path = null;
            }

            if ($request->ar_type == '2d') {
                $imagePath = $request->file('image')->store('ar_images', 'public');
                $qrCode->image_path = $imagePath;
            } else {
                if ($request->hasFile('file_3d')) {
                    $file = $request->file('file_3d');
                    $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.glb';

                    $fileContent = file_get_contents($file->getRealPath());
                    $upload = Storage::disk('s3')->put('3D/' . $filename, $fileContent);
                    if (!$upload) throw new \Exception("Gagal menyimpan file 3D ke MinIO.");

                    $fileUrl = url('/minio-proxy/3D/' . $filename);

                    $finalAssetName = $request->asset_name;
                    if (empty($finalAssetName)) {
                        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                        $cleanName = preg_replace('/^[0-9]+_/', '', $originalName);
                        $finalAssetName = ucwords(str_replace(['-', '_'], ' ', $cleanName));
                    }

                    $arAsset = ArAsset::create([
                        'user_id' => auth()->id(),
                        'name' => $finalAssetName,
                        'file_path' => $fileUrl,
                        'is_public' => true,
                    ]);
                    $qrCode->ar_asset_id = $arAsset->id;
                } 
                elseif ($request->filled('selected_3d_id')) {
                    $qrCode->ar_asset_id = $request->selected_3d_id;
                } else {
                    throw new \Exception("Pilih atau upload objek 3D terlebih dahulu.");
                }
            }

            $qrCode->uuid = Str::uuid();
            $qrUrl = url('/scan-ar'); 
            $qrImage = base64_encode(QrCode::format('svg')->size(300)->margin(2)->generate($qrUrl));
            $qrCode->qr_image_path = $qrImage;

            $qrCode->save();

            $user = auth()->user();
            $user->increment('image');
            if (!empty($request->narration) || $request->hasFile('custom_audio')) {
                $user->increment('voice');
            }

            if ($request->ajax() || $request->wantsJson()) {
                session()->flash('success', 'AR Experience berhasil dibuat!');
                return response()->json(['status' => 'success', 'redirect_url' => route('user.dashboard')]);
            }
            return redirect()->route('user.dashboard')->with('success', 'AR Experience dibuat!');

        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function toggleStatus(QrCodeModel $qrCode)
    {
        if ($qrCode->user_id !== Auth::id()) abort(403);
        $qrCode->status = $qrCode->status === 'Aktif' ? 'Nonaktif' : 'Aktif';
        $qrCode->save();
        return back()->with('success', 'Status QR diubah.');
    }

    public function download(QrCodeModel $qrCode)
    {
        if ($qrCode->user_id !== Auth::id()) abort(403);
        
        $apiUrl = url('/scan-ar'); 
        
        $imageContent = QrCode::size(500)->margin(2)->generate($apiUrl);
        $fileName = 'ScanYuk-AR-' . Str::slug($qrCode->title) . '.svg';

        return response($imageContent)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }
}