<?php

namespace App\Http\Controllers;

use App\Models\QrCode as QrCodeModel;
use App\Models\ArAsset;
use App\Models\ArTemplate;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class QrCodeController extends Controller
{
    public function create()
    {
        $library3dList = ArAsset::where('is_public', true)->get(['id', 'name', 'file_path as path']);

        $templates = ArTemplate::all();

        $musicFiles = File::files(public_path('bg_sounds'));
        $musicList = [];
        foreach ($musicFiles as $file) {
            $fileName = $file->getFilename();
            $cleanName = ucwords(str_replace(['-', '.mp3', '_'], [' ', '', ' '], $fileName));
            $musicList[] = ['name' => $cleanName, 'path' => $fileName];
        }

        return view('dashboard.create-ar', compact('library3dList', 'musicList', 'templates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'ar_type' => 'required|in:2d,3d',
            'narration' => 'required|string',
            'image' => 'required_if:ar_type,2d|image|mimes:jpeg,png,jpg|max:5120', 
            'file_3d' => 'nullable|file|max:10240',
            'asset_name' => 'required_with:file_3d|string|max:100',
        ], [
            'image.required_if' => 'Gambar 2D wajib diunggah jika Anda memilih tipe AR 2D.',
            'asset_name.required_with' => 'Nama objek 3D wajib diisi jika Anda mengunggah file .glb.',
        ]);

        $qrCode = new QrCodeModel(); 
        $qrCode->user_id = auth()->id();
        $qrCode->title = $request->title;
        $qrCode->ar_type = $request->ar_type;
        $qrCode->narration = $request->narration;
        $qrCode->bgm_path = $request->bgm_path;

        if ($request->ar_type == '2d') {
            $imagePath = $request->file('image')->store('ar_images', 'public');
            $qrCode->image_path = $imagePath;
        } 
        else {
            if ($request->hasFile('file_3d')) {
                $file = $request->file('file_3d');
                $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.glb';

                Storage::disk('s3')->putFileAs('', $file, $filename);

                $arAsset = ArAsset::create([
                    'user_id' => auth()->id(),
                    'name' => $request->asset_name,
                    'file_path' => url('/ar-models/' . $filename), 
                    'is_public' => true,
                ]);

                $qrCode->ar_asset_id = $arAsset->id;
            }
            elseif ($request->filled('selected_3d_id')) {
                $qrCode->ar_asset_id = $request->selected_3d_id;
            } 
            else {
                return back()->withErrors(['error' => 'Silakan pilih objek 3D dari library atau upload file .glb sendiri.'])->withInput();
            }
        }

        $qrCode->uuid = Str::uuid();
        $qrUrl = url('/api/scan/' . $qrCode->uuid); 
        
        $qrImage = base64_encode(QrCode::format('svg')->size(300)->margin(2)->generate($qrUrl));
        $qrCode->qr_image_path = $qrImage;

        $qrCode->save();

        $user = auth()->user();

        $user->increment('image');

        if (!empty($request->narration)) {
            $user->increment('voice');
        }

        return redirect()->route('user.dashboard')->with('success', 'AR Experience berhasil dibuat!');
    }

    public function toggleStatus(QrCodeModel $qrCode)
    {
        if ($qrCode->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $qrCode->status = $qrCode->status === 'Aktif' ? 'Nonaktif' : 'Aktif';
        $qrCode->save();

        return back()->with('success', 'Status QR Code berhasil diubah.');
    }

    public function download(QrCodeModel $qrCode)
    {
        if ($qrCode->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $identifier = $qrCode->uuid ?? $qrCode->id;
        $apiUrl = url('/api/scan/' . $identifier);

        $imageContent = QrCode::size(500)->margin(2)->generate($apiUrl);

        $fileName = 'ScanYuk-AR-' . Str::slug($qrCode->title) . '.svg';

        return response($imageContent)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }
}