<?php

namespace App\Http\Controllers;

use App\Jobs\ConvertBlenderJob;
use App\Models\ArProject;
use App\Models\Marker;
use App\Models\Template;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Symfony\Component\Process\Process;

class ArProjectController extends Controller
{
    /**
     * Tampilkan wizard 4-step
     * GET /create
     */
    public function create()
    {
        // whereNotNull mencegah pengambilan data yang filenya kosong/rusak
        $library3dList = \App\Models\ArAsset::whereNotNull('file_path')->get()->map(function ($asset) {
            return [
                'id'            => $asset->id,
                'name'          => $asset->name,
                'model_url'     => Storage::url($asset->file_path), 
                'thumbnail_url' => null,
            ];
        });

        return view('create', compact('library3dList'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // API: Upload .blend → dispatch job → return project_id untuk polling
    // POST /api/blend-upload
    // ─────────────────────────────────────────────────────────────────────────
    public function blendUpload(Request $request): JsonResponse
    {
        $request->validate([
            // 'extensions' rule lebih reliable dari 'mimes' untuk .blend
            'model' => ['required', 'file', 'max:512000'], // 500MB
        ]);

        $file = $request->file('model');

        // Pastikan ekstensi .blend secara manual (mimes tidak reliable untuk blend)
        if (strtolower($file->getClientOriginalExtension()) !== 'blend') {
            return response()->json(['message' => 'File harus berekstensi .blend'], 422);
        }

        $tmpPath = $file->store('tmp-models', 'local');

        // Buat project sementara — marker_id nullable sampai final store.
        // PENTING: Jalankan migration: $table->foreignId('marker_id')->nullable()->...
        $project = ArProject::create([
            'marker_id'  => null,
            'type'       => 'blend',
            'status'     => 'processing',
            'model_path' => null,
            'scale'      => 1.0,
            'position'   => [0, 0, 0],   // $casts array → auto json_encode
            'rotation'   => [0, 0, 0],
            'config'     => [],
        ]);

        ConvertBlenderJob::dispatch($project, $tmpPath);

        return response()->json([
            'project_id' => $project->id,
            'status'     => 'processing',
        ], 201);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // API: Cek status konversi blend
    // GET /api/blend-status/{project}
    // ─────────────────────────────────────────────────────────────────────────
    public function blendStatus(ArProject $project): JsonResponse
    {
        // PENTING: fresh() agar tidak pakai cached model dari route binding.
        // Tanpa ini, job sudah update DB ke 'ready' tapi controller
        // masih return status lama yang di-cache saat request masuk.
        $project = $project->fresh();

        return response()->json([
            'project_id' => $project->id,
            'status'     => $project->status,
            'model_url'  => $project->status === 'ready' ? $project->model_url : null,
            'model_path' => $project->model_path, // untuk debug
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // POST /store — simpan project final & redirect ke result
    // ─────────────────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'title'        => ['required', 'string', 'max:255'], // Wajib diisi!
            'marker_id'    => ['required', 'exists:markers,id'],
            'type'         => ['required', 'in:template,gltf,blend'],
            'scale'        => ['nullable', 'numeric', 'min:0.05', 'max:10'],
            'position'     => ['nullable', 'string'],
            'rotation'     => ['nullable', 'string'],
            'orbit_active' => ['nullable', 'in:0,1'],
            'orbit_speed'  => ['nullable', 'numeric', 'min:0.1', 'max:10'],
            'orbit_radius' => ['nullable', 'numeric', 'min:0.1', 'max:10'],
            'orbit_dir'    => ['nullable', 'in:-1,1'],
            'anim_clip'    => ['nullable', 'string', 'max:100'],
        ]);

        $marker = Marker::findOrFail($request->marker_id);

        if (!$marker->isReady()) {
            return response()->json(['error' => 'Marker belum siap.'], 422);
        }

        $position = $request->filled('position') ? json_decode($request->position, true) : [0, 0, 0];
        $rotation = $request->filled('rotation') ? json_decode($request->rotation, true) : [0, 0, 0];

        $data = [
            'user_id'   => auth()->id(), // Masukkan ID user yang login
            'marker_id' => $marker->id,
            'type'      => $request->type,
            'scale'     => $request->scale ?? 1.0,
            'position'  => $position,
            'rotation'  => $rotation,
            'status'    => 'ready',
            'config'    => [
                'orbit_active' => (bool) ($request->orbit_active ?? false),
                'orbit_speed'  => (float) ($request->orbit_speed  ?? 0.5),
                'orbit_radius' => (float) ($request->orbit_radius ?? 1.5),
                'orbit_dir'    => (int)   ($request->orbit_dir    ?? 1),
                'anim_clip'    => $request->anim_clip ?? '*',
            ],
        ];

        // Buat record Project berdasarkan tipe konten
        if ($request->type === 'template') {
            $request->validate([
                // UBAH VALIDASI DI BAWAH INI MENJADI ar_assets
                'template_id' => ['required', 'exists:ar_assets,id'],
                'config'      => ['nullable', 'array'],
            ]);
            $data['template_id'] = $request->template_id;
            $data['config']      = $request->config ?? [];
            $project = ArProject::create($data);

        } elseif ($request->type === 'gltf') {
            $request->validate([
                'model' => ['required', 'file', 'mimes:glb,gltf', 'max:102400'],
            ]);
            $modelPath = $request->file('model')->store('models', 'public');
            $data['model_path'] = $modelPath;
            $project = ArProject::create($data);

        } elseif ($request->type === 'blend') {
            $request->validate([
                'blend_project_id' => ['required', 'exists:ar_projects,id'],
            ]);

            $project = ArProject::findOrFail($request->blend_project_id);

            if ($project->status !== 'ready') {
                return response()->json(['error' => 'Konversi blend belum selesai.'], 422);
            }

            $project->update([
                'user_id'   => auth()->id(),
                'marker_id' => $marker->id,
                'scale'     => $data['scale'],
                'position'  => $data['position'],
                'rotation'  => $data['rotation'],
            ]);
        }

        // ─── GENERATE QR CODE SECARA OTOMATIS BERDASARKAN PROYEK ───
        $uuid = (string) \Illuminate\Support\Str::uuid();
        // Arahkan QR scan ke link view AR viewer bawaan Anda
        $qrUrl = route('ar.view', ['project' => $project->id]); 
        $qrImage = base64_encode(QrCode::format('svg')->size(300)->margin(2)->generate($qrUrl));

        // Daftarkan ke tabel qr_codes agar tampil di list dashboard user utama
        \App\Models\QrCode::create([
            'user_id'       => auth()->id(),
            'uuid'          => $uuid,
            'title'         => $request->title,
            'ar_type'       => 'marker', // Bedakan tipe kontennya menjadi 'marker'
            'ar_project_id' => $project->id,
            'qr_image_path' => $qrImage,
            'status'        => 'Aktif',
            'scan_count'    => 0,
        ]);

        // Tambah limit counter user (Gunakan logic yang sama seperti QrCodeController)
        $user = auth()->user();
        if ($user) {
            $user->increment('image'); // Sesuaikan counter penggunaan limit di dashboard
        }

        // Return AJAX response untuk memicu animasi progress bar di create.blade.php
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'redirect_url' => route('user.dashboard')
            ]);
        }

        return redirect()->route('user.dashboard')->with('success', 'Project AR berhasil dibuat!');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Helpers (blend sync — tetap ada sebagai fallback internal)
    // ─────────────────────────────────────────────────────────────────────────
    protected function convertBlendToGlb(string $sourcePath, string $destinationPath): void
    {
        if (!file_exists($sourcePath)) {
            throw new \RuntimeException('File .blend tidak ditemukan untuk konversi.');
        }

        $blender = $this->resolveBlenderExecutable();
        $source  = realpath($sourcePath);

        $pythonExpr = sprintf(
            "import bpy; " .
            "bpy.ops.wm.open_mainfile(filepath=r'%s'); " .
            "bpy.ops.export_scene.gltf(filepath=r'%s', export_format='GLB', export_copyright='WebAR', export_apply=True)",
            $source,
            $destinationPath
        );

        $process = new Process([$blender, '--background', '--factory-startup', '--python-expr', $pythonExpr]);
        $process->setTimeout(300);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException('Blender Error: ' . $process->getErrorOutput());
        }

        clearstatcache();

        if (!file_exists($destinationPath)) {
            $output = $process->getOutput();
            Log::error("Blender Output Log: " . $output);
            throw new \RuntimeException('Konversi selesai tapi file .glb tidak ditemukan. Log: ' . substr($output, -200));
        }
    }

    protected function resolveBlenderExecutable(): string
    {
        $candidate = env('BLENDER_PATH', 'blender');
        $path = trim(shell_exec('command -v ' . escapeshellarg($candidate)) ?: '');

        if (!$path) {
            throw new \RuntimeException('Blender tidak ditemukan. Install Blender atau set BLENDER_PATH di .env.');
        }

        return $path;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Result, View, apiShow — tidak berubah
    // ─────────────────────────────────────────────────────────────────────────
    public function result(ArProject $project)
    {
        $arUrl  = route('ar.view', ['project' => $project->id]);
        $qrCode = QrCode::format('svg')->size(250)->errorCorrection('H')->generate($arUrl);
        return view('dashboard.result', compact('project', 'arUrl', 'qrCode'));
    }

    public function view(ArProject $project)
    {
        $project->load(['marker', 'template']);

        $config   = is_array($project->config) ? $project->config : [];

        $mindUrl      = $project->marker->mind_url;
        $modelUrl     = $project->model_url;
        $scale        = $project->scale;
        $position     = $project->position  ?? [0, 0, 0];
        $rotation     = $project->rotation  ?? [0, 0, 0];
        $isTemplate   = $project->isTemplate();
        $placeholders = $isTemplate && $project->template
            ? ($project->template->placeholders ?? [])
            : [];

        // Orbit & animasi config
        $orbitActive = (bool) ($config['orbit_active'] ?? false);
        $orbitSpeed  = (float) ($config['orbit_speed']  ?? 0.5);
        $orbitRadius = (float) ($config['orbit_radius'] ?? 1.5);
        $orbitDir    = (int)   ($config['orbit_dir']    ?? 1);
        $animClip    = $config['anim_clip'] ?? '*';

        return view('dashboard.ar-viewer', compact(
            'project', 'mindUrl', 'modelUrl', 'config',
            'scale', 'position', 'rotation', 'isTemplate', 'placeholders',
            'orbitActive', 'orbitSpeed', 'orbitRadius', 'orbitDir', 'animClip'
        ));
    }

    public function apiShow(ArProject $project): JsonResponse
    {
        $project->load(['marker', 'template']);

        return response()->json([
            'id'        => $project->id,
            'type'      => $project->type,
            'scale'     => $project->scale,
            'position'  => $project->position,
            'rotation'  => $project->rotation,
            'config'    => $project->config,
            'model_url' => $project->model_url,
            'marker'    => [
                'id'        => $project->marker->id,
                'status'    => $project->marker->status,
                'image_url' => $project->marker->image_url,
                'mind_url'  => $project->marker->mind_url,
            ],
            'template'  => $project->template ? [
                'id'           => $project->template->id,
                'name'         => $project->template->name,
                'placeholders' => $project->template->placeholders,
            ] : null,
        ]);
    }
}