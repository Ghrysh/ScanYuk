<?php

namespace App\Http\Controllers;

use App\Models\QueueLocation;
use App\Models\QueueService;
use App\Models\QueueCounter;
use App\Models\QueueStaff;
use App\Models\QueueTicket;
use App\Models\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeFacade;

class QueueManagementController extends Controller
{
    public function requestAccess()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->queue_status === 'none') {
            $user->queue_status = 'pending';
            $user->save();
        }
        return back()->with('success', 'Permintaan akses Sistem Antrian berhasil dikirim. Menunggu persetujuan admin.');
    }

    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Handle queue access request view
        if ($user->queue_status === 'none') {
            return view('dashboard.queue.request');
        } elseif ($user->queue_status === 'pending') {
            return view('dashboard.queue.pending');
        }
        
        $locations = QueueLocation::where('user_id', $user->id)
            ->withCount([
                'todayTickets as waiting_count' => function($q) { $q->where('status', 'waiting'); },
                'todayTickets as serving_count' => function($q) { $q->where('status', 'serving'); },
                'todayTickets as completed_count' => function($q) { $q->where('status', 'completed'); }
            ])->get();
            
        $role = strtolower($user->role ?? 'free');
        $limit = $user->queue_location ?? (QueueLocation::LOCATION_LIMITS[$role] ?? 1);
        $canCreate = is_null($limit) ? true : ($locations->count() < $limit);

        // Analytics Data
        $selectedLocationId = $request->query('location_id');
        $dateFrom = $request->query('date_from', now()->subDays(30)->toDateString());
        $dateTo = $request->query('date_to', now()->toDateString());

        $query = QueueTicket::whereHas('location', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->whereBetween('date', [$dateFrom, $dateTo]);

        if ($selectedLocationId) {
            $query->where('queue_location_id', $selectedLocationId);
        }

        $tickets = $query->get();
        $totalRegistrations = $tickets->count();
        $totalServed = $tickets->where('status', 'completed')->count();
        $totalNoShow = $tickets->whereIn('status', ['skipped', 'no_show'])->count();

        $completedTickets = $tickets->where('status', 'completed')->filter(function($t) {
            return $t->serving_at && $t->completed_at;
        });

        $avgWaitMinutes = 0;
        $avgServiceMinutes = 0;

        if ($completedTickets->count() > 0) {
            $totalWait = 0;
            $totalService = 0;
            foreach ($completedTickets as $t) {
                $created = \Carbon\Carbon::parse($t->created_at);
                $serving = \Carbon\Carbon::parse($t->serving_at);
                $completed = \Carbon\Carbon::parse($t->completed_at);
                
                $totalWait += $serving->diffInMinutes($created);
                $totalService += $completed->diffInMinutes($serving);
            }
            $avgWaitMinutes = round($totalWait / $completedTickets->count());
            $avgServiceMinutes = round($totalService / $completedTickets->count());
        }

        $popularService = '';
        if ($tickets->count() > 0) {
            $serviceCounts = $tickets->groupBy('queue_service_id')->map->count();
            $topServiceId = $serviceCounts->sortDesc()->keys()->first();
            $topService = QueueService::find($topServiceId);
            if ($topService) {
                $popularService = $topService->name;
            }
        }

        $dailyData = [];
        $period = \Carbon\CarbonPeriod::create($dateFrom, $dateTo);
        foreach ($period as $date) {
            $dateString = $date->toDateString();
            $dailyData[$dateString] = $tickets->where('date', $dateString)->count();
        }

        // Global Staff Management Data
        $staffs = QueueStaff::whereHas('location', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with(['location', 'counter'])->get();

        return view('dashboard.queue.index', compact(
            'locations', 'canCreate',
            'selectedLocationId', 'dateFrom', 'dateTo',
            'totalRegistrations', 'totalServed', 'totalNoShow',
            'avgWaitMinutes', 'avgServiceMinutes', 'popularService', 'dailyData',
            'staffs'
        ));
    }

    public function createLocation()
    {
        $location = null;
        $arQrCodes = QrCode::where('user_id', Auth::id())->get();
        return view('dashboard.queue.manage', compact('location', 'arQrCodes'));
    }

    public function storeLocation(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $role = strtolower($user->role ?? 'free');
        $limit = $user->queue_location ?? (QueueLocation::LOCATION_LIMITS[$role] ?? 1);
        
        $currentCount = QueueLocation::where('user_id', $user->id)->count();
        if ($limit !== null && $currentCount >= $limit) {
            return back()->with('error', 'Batas maksimal lokasi antrian untuk paket Anda telah tercapai.')->with('showUpgrade', true);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'operational_hours' => 'nullable|string',
            'ar_qr_code_id' => 'nullable|exists:qr_codes,id',
            'daily_quota' => 'nullable|integer|min:1',
            'has_booths' => 'nullable|boolean'
        ]);

        if ($request->daily_quota && $user->queue_ticket !== null) {
            $totalUsedQuota = QueueLocation::where('user_id', $user->id)->sum('daily_quota');
            if ($totalUsedQuota + $request->daily_quota > $user->queue_ticket) {
                return back()->with('error', 'Melebihi total antrian. Total antrian saat ini sisa: ' . max(0, $user->queue_ticket - $totalUsedQuota));
            }
        }

        $location = QueueLocation::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'address' => $request->address,
            'operational_hours' => $request->operational_hours ? json_decode($request->operational_hours, true) : null,
            'ar_qr_code_id' => $request->ar_qr_code_id,
            'daily_quota' => $request->daily_quota,
            'has_booths' => $request->has_booths ?? false,
        ]);

        if ($request->has_booths && $request->booth_name && $request->booth_count) {
            $count = (int) $request->booth_count;
            for ($i = 1; $i <= $count; $i++) {
                \App\Models\QueueCounter::create([
                    'queue_location_id' => $location->id,
                    'name' => $request->booth_name . ' ' . $i,
                    'is_active' => true,
                ]);
            }
        }

        return redirect()->route('queue.index')->with('success', 'Lokasi antrian berhasil dibuat.');
    }

    public function manageLocation(QueueLocation $location)
    {
        if ($location->user_id !== Auth::id()) abort(403);

        $location->load(['services', 'counters', 'staff.counter']);
        $arQrCodes = QrCode::where('user_id', Auth::id())->get();

        return view('dashboard.queue.manage', compact('location', 'arQrCodes'));
    }

    public function updateLocation(Request $request, QueueLocation $location)
    {
        if ($location->user_id !== Auth::id()) abort(403);
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'operational_hours' => 'nullable|string',
            'ar_qr_code_id' => 'nullable|exists:qr_codes,id',
            'daily_quota' => 'nullable|integer|min:1',
            'has_booths' => 'nullable|boolean'
        ]);

        if ($request->daily_quota && $user->queue_ticket !== null) {
            $totalUsedQuota = QueueLocation::where('user_id', $user->id)
                ->where('id', '!=', $location->id)->sum('daily_quota');
            if ($totalUsedQuota + $request->daily_quota > $user->queue_ticket) {
                return back()->with('error', 'Melebihi total antrian. Total antrian saat ini sisa: ' . max(0, $user->queue_ticket - $totalUsedQuota));
            }
        }

        $hasBooths = $request->has_booths ?? false;

        $location->update([
            'name' => $request->name,
            'address' => $request->address,
            'operational_hours' => $request->operational_hours ? json_decode($request->operational_hours, true) : $location->operational_hours,
            'ar_qr_code_id' => $request->ar_qr_code_id,
            'daily_quota' => $request->daily_quota,
            'has_booths' => $hasBooths,
        ]);
        
        if ($hasBooths) {
            // Jika form edit mengirimkan booth_name dan booth_count untuk generate massal
            if ($request->filled('booth_name') && $request->filled('booth_count') && $request->booth_count > 0) {
                for ($i = 1; $i <= $request->booth_count; $i++) {
                    $location->counters()->create([
                        'name' => $request->booth_name . ' ' . $i,
                        'is_active' => true
                    ]);
                }
            }
        } else {
            // Jika dinonaktifkan, hapus semua loket/booth beserta relasinya
            // (Staff yang terkait dengan loket ini akan menjadi null queue_counter_id nya karena nullOnDelete di database)
            $location->counters()->delete();
        }

        return back()->with('success', 'Lokasi antrian berhasil diperbarui.');
    }

    public function deleteLocation(QueueLocation $location)
    {
        if ($location->user_id !== Auth::id()) abort(403);
        $location->delete();
        return redirect()->route('queue.index')->with('success', 'Lokasi antrian berhasil dihapus.');
    }

    public function storeService(Request $request, QueueLocation $location)
    {
        if ($location->user_id !== Auth::id()) abort(403);

        $request->validate([
            'name' => 'required|string',
            'prefix' => 'required|string|max:5',
            'estimated_duration_minutes' => 'required|integer|min:1',
            'daily_quota' => 'nullable|integer|min:1'
        ]);

        $location->services()->create($request->only([
            'name', 'prefix', 'estimated_duration_minutes', 'daily_quota'
        ]));

        return back()->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function updateService(Request $request, QueueService $service)
    {
        if ($service->location->user_id !== Auth::id()) abort(403);

        $request->validate([
            'name' => 'required|string',
            'prefix' => 'required|string|max:5',
            'estimated_duration_minutes' => 'required|integer|min:1',
            'daily_quota' => 'nullable|integer|min:1'
        ]);

        $service->update($request->only([
            'name', 'prefix', 'estimated_duration_minutes', 'daily_quota'
        ]));

        return back()->with('success', 'Layanan berhasil diperbarui.');
    }

    public function deleteService(QueueService $service)
    {
        if ($service->location->user_id !== Auth::id()) abort(403);
        $service->delete();
        return back()->with('success', 'Layanan berhasil dihapus.');
    }

    public function storeCounter(Request $request, QueueLocation $location)
    {
        if ($location->user_id !== Auth::id()) abort(403);

        $request->validate([
            'name' => 'required|string'
        ]);

        $location->counters()->create($request->only('name'));

        return back()->with('success', 'Loket / Booth berhasil ditambahkan.');
    }

    public function updateCounter(Request $request, QueueCounter $counter)
    {
        if ($counter->location->user_id !== Auth::id()) abort(403);

        $request->validate([
            'name' => 'required|string'
        ]);

        $counter->update($request->only('name'));

        return back()->with('success', 'Loket / Booth berhasil diperbarui.');
    }

    public function deleteCounter(QueueCounter $counter)
    {
        if ($counter->location->user_id !== Auth::id()) abort(403);
        $counter->delete();
        return back()->with('success', 'Loket / Booth berhasil dihapus.');
    }

    public function storeStaff(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $request->validate([
            'queue_location_id' => 'required|exists:queue_locations,id',
            'queue_counter_id' => 'nullable|exists:queue_counters,id',
            'name' => 'required|string',
            'username' => 'required|string|unique:queue_staff,username',
            'password' => 'required|string|min:4'
        ]);

        $location = QueueLocation::where('id', $request->queue_location_id)->where('user_id', $user->id)->firstOrFail();

        $location->staff()->create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => $request->password,
            'queue_counter_id' => $request->queue_counter_id
        ]);

        return back()->with('success', 'Pegawai berhasil ditambahkan.');
    }

    public function updateStaff(Request $request, QueueStaff $staff)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($staff->location->user_id !== $user->id) abort(403);

        $request->validate([
            'queue_location_id' => 'required|exists:queue_locations,id',
            'queue_counter_id' => 'nullable|exists:queue_counters,id',
            'username' => 'required|string|unique:queue_staff,username,' . $staff->id,
            'password' => 'nullable|string|min:4'
        ]);

        $location = QueueLocation::where('id', $request->queue_location_id)->where('user_id', $user->id)->firstOrFail();

        $data = $request->only(['name', 'username', 'queue_counter_id']);
        $data['queue_location_id'] = $location->id;
        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        $staff->update($data);

        return back()->with('success', 'Pegawai berhasil diperbarui.');
    }

    public function deleteStaff(QueueStaff $staff)
    {
        if ($staff->location->user_id !== Auth::id()) abort(403);
        $staff->delete();
        return back()->with('success', 'Pegawai berhasil dihapus.');
    }

    public function downloadQr(QueueLocation $location)
    {
        if ($location->user_id !== Auth::id()) abort(403);

        if ($location->ar_qr_code_id && $location->qrCode) {
            // Jika menggunakan AR, QR code akan diarahkan ke scanner AR,
            // dan kita sisipkan parameter queue_location_uuid agar scanner
            // bisa mengarahkan user ke halaman antrian setelah AR selesai.
            if ($location->qrCode->ar_project_id) {
                $url = route('ar.view', ['project' => $location->qrCode->ar_project_id]) . '?queue_uuid=' . $location->uuid;
            } else {
                $url = url('/scan-ar?id=' . $location->qrCode->uuid . '&queue_uuid=' . $location->uuid);
            }
        } else {
            // Tanpa AR, langsung ke halaman registrasi antrian
            $url = route('queue.register', $location->uuid);
        }
        $imageContent = QrCodeFacade::format('svg')->size(500)->margin(2)->generate($url);
        $fileName = 'Queue-QR-' . Str::slug($location->name) . '.svg';

        return response($imageContent)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    public function leaderboard(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $customers = \App\Models\QueueCustomer::where('user_id', $user->id)
            ->orderBy('points', 'desc')
            ->orderBy('visits', 'desc')
            ->get();
            
        return view('dashboard.queue.leaderboard', compact('customers'));
    }

    public function addViews(Request $request, \App\Models\QueueCustomer $customer)
    {
        if ($customer->user_id !== Auth::id()) abort(403);
        $request->validate(['views' => 'required|integer|min:1']);
        $customer->increment('views', $request->views);
        return back()->with('success', 'Viewers berhasil ditambahkan ke ' . $customer->name);
    }

    public function addPoints(Request $request, \App\Models\QueueCustomer $customer)
    {
        if ($customer->user_id !== Auth::id()) abort(403);

        $request->validate([
            'points' => 'required|integer|min:1'
        ]);

        $customer->increment('points', $request->points);

        return back()->with('success', 'Poin berhasil ditambahkan ke ' . $customer->name);
    }
}
