<?php

namespace App\Http\Controllers;

use App\Models\QrCode as QrCodeModel;
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
            'image' => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'narration' => 'required|string',
        ]);

        $user = Auth::user();

        $currentPackage = \App\Models\PricingPackage::where('name', 'ilike', $user->role)->first();
        $imgLimit = (int) filter_var($currentPackage->features[0] ?? 0, FILTER_SANITIZE_NUMBER_INT);
        
        if ($user->image >= $imgLimit && $imgLimit > 0) {
            return back()->withErrors(['limit' => 'Kuota Image AR Anda sudah habis. Silakan Upgrade Paket.']);
        }

        $imagePath = $request->file('image')->store('ar_images', 'public');
        
        $uuid = (string) Str::uuid();
        $apiUrl = url('/api/scan/' . $uuid);

        $qrFileName = 'qrcodes/' . $uuid . '.svg';
        $qrImage = QrCode::size(500)->margin(2)->generate($apiUrl);
        Storage::disk('public')->put($qrFileName, $qrImage);

        QrCodeModel::create([
            'user_id' => $user->id,
            'uuid' => $uuid,
            'title' => $request->title,
            'image_path' => $imagePath,
            'narration' => $request->narration,
            'qr_image_path' => $qrFileName,
            'status' => 'Aktif',
            'scan_count' => 0
        ]);

        $user->increment('image');
        $user->increment('voice');

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