<?php

namespace App\Http\Controllers;

use App\Jobs\CompileMindARJob;
use App\Models\Marker;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MarkerController extends Controller
{
    /**
     * Upload gambar marker dan dispatch job compile
     *
     * POST /api/markers
     * Returns: JSON { marker_id, status, image_url }
     */
    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
        ]);

        $path = $request->file('image')->store('markers', 'public');

        $marker = Marker::create([
            'user_id'       => auth()->id(), // Set marker ini milik user yang login
            'image_path'    => $path,        // (Pastikan ini sudah tanpa 'public/' sesuai perbaikan sebelumnya)
            'status'        => Marker::STATUS_PROCESSING,
            'error_message' => null,
        ]);

        \App\Jobs\CompileMindARJob::dispatch($marker);

        return response()->json([
            'marker_id' => $marker->id,
            'status'    => $marker->status,
            'image_url' => Storage::url($path),
            'message'   => 'Marker sedang diproses. Mohon tunggu...',
        ], 201);
    }

    /**
     * Cek status marker (digunakan polling dari frontend)
     */
    public function status(Marker $marker): JsonResponse
    {
        // 1. Hapus baris Marker::findOrFail($id) karena $marker sudah terisi otomatis oleh Laravel
        
        // 2. Logika progress (bisa disesuaikan nanti)
        $progress = $marker->status === Marker::STATUS_READY ? 100 : 50; 
        $eta = $marker->status === Marker::STATUS_READY ? 0 : 30;

        return response()->json([
            'id'            => $marker->id,
            'status'        => $marker->status, // kunci status hanya perlu satu
            'image_url'     => $marker->image_url,
            'mind_url'      => $marker->mind_url,
            'error_message' => $marker->error_message,
            'progress'      => $progress,
            'eta'           => $eta,
        ]);
    }

    public function index(Request $request): JsonResponse
    {
        $markers = Marker::where('user_id', auth()->id())
            ->where('status', Marker::STATUS_READY)
            ->orderByDesc('id')
            ->get()
            ->map(fn (Marker $marker) => [
                'id'        => $marker->id,
                'image_url' => $marker->image_url,
                'status'    => $marker->status,
            ]);

        return response()->json($markers);
    }

    public function destroy(Marker $marker): JsonResponse
    {
        if ($marker->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($marker->image_path) Storage::disk('public')->delete($marker->image_path);
        if ($marker->mind_path) Storage::disk('public')->delete($marker->mind_path);

        $marker->delete();

        return response()->json(['message' => 'Marker berhasil dihapus']);
    }
}
