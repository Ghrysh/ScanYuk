<?php

namespace App\Http\Controllers;

use App\Models\QrCode;
use App\Models\PricingPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QrCodeController extends Controller
{
    public function create()
    {
        return view('dashboard.create-ar');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'narration' => 'required|string|max:1000',
        ]);

        $user = Auth::user();

        $currentPackage = PricingPackage::where('name', 'ilike', $user->role)->first();
        $imgLimit = (int) filter_var($currentPackage->features[0] ?? 0, FILTER_SANITIZE_NUMBER_INT);
        
        if ($user->image >= $imgLimit && $imgLimit > 0) {
            return back()->withErrors(['limit' => 'Kuota Image AR Anda sudah habis. Silakan Upgrade Paket.']);
        }

        $imagePath = $request->file('image')->store('ar_images', 'public');

        QrCode::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'image_path' => $imagePath,
            'narration' => $request->narration,
            'status' => 'Aktif',
            'scan_count' => 0,
        ]);

        $user->increment('image');
        $user->increment('voice');

        return redirect()->route('user.dashboard')->with('success', 'AR Experience berhasil dibuat!');
    }

    public function toggleStatus(QrCode $qrCode)
    {
        // Pastikan QR ini hanya bisa diubah oleh pemiliknya
        if ($qrCode->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Balikkan statusnya
        $qrCode->status = $qrCode->status === 'Aktif' ? 'Nonaktif' : 'Aktif';
        $qrCode->save();

        return back()->with('success', 'Status QR Code berhasil diubah.');
    }

    public function download(QrCode $qrCode)
    {
        if ($qrCode->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $targetUrl = url('/ar/' . $qrCode->id); 
        
        $apiUrl = "https://api.qrserver.com/v1/create-qr-code/?size=500x500&margin=10&data=" . urlencode($targetUrl);
        
        $imageContent = file_get_contents($apiUrl);
        
        $fileName = 'qr-' . \Illuminate\Support\Str::slug($qrCode->title) . '.png';

        return response($imageContent)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }
}