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
    public function index()
    {
        $user = Auth::user();
        $locations = QueueLocation::where('user_id', $user->id)
            ->withCount([
                'todayTickets as waiting_count' => function($q) { $q->where('status', 'waiting'); },
                'todayTickets as serving_count' => function($q) { $q->where('status', 'serving'); },
                'todayTickets as completed_count' => function($q) { $q->where('status', 'completed'); }
            ])->get();
            
        $role = strtolower($user->role ?? 'free');
        $limit = QueueLocation::LOCATION_LIMITS[$role] ?? 1;
        $canCreate = is_null($limit) ? true : ($locations->count() < $limit);

        return view('dashboard.queue.index', compact('locations', 'canCreate'));
    }

    public function createLocation()
    {
        $location = null;
        $arQrCodes = QrCode::where('user_id', Auth::id())->get();
        return view('dashboard.queue.manage', compact('location', 'arQrCodes'));
    }

    public function storeLocation(Request $request)
    {
        $user = Auth::user();
        $role = strtolower($user->role ?? 'free');
        $limit = QueueLocation::LOCATION_LIMITS[$role] ?? 1;
        $currentCount = QueueLocation::where('user_id', $user->id)->count();

        if (!is_null($limit) && $currentCount >= $limit) {
            return back()->with('error', 'Batas maksimal lokasi antrian untuk paket Anda telah tercapai.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'operational_hours' => 'nullable|array',
            'ar_qr_code_id' => 'nullable|exists:qr_codes,id'
        ]);

        QueueLocation::create([
            'user_id' => $user->id,
            'uuid' => Str::uuid(),
            'name' => $request->name,
            'address' => $request->address,
            'operational_hours' => $request->operational_hours,
            'ar_qr_code_id' => $request->ar_qr_code_id,
            'is_active' => true,
        ]);

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

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'operational_hours' => 'nullable|array',
            'ar_qr_code_id' => 'nullable|exists:qr_codes,id'
        ]);

        $location->update([
            'name' => $request->name,
            'address' => $request->address,
            'operational_hours' => $request->operational_hours,
            'ar_qr_code_id' => $request->ar_qr_code_id,
        ]);

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

        return back()->with('success', 'Loket berhasil ditambahkan.');
    }

    public function updateCounter(Request $request, QueueCounter $counter)
    {
        if ($counter->location->user_id !== Auth::id()) abort(403);

        $request->validate([
            'name' => 'required|string'
        ]);

        $counter->update($request->only('name'));

        return back()->with('success', 'Loket berhasil diperbarui.');
    }

    public function deleteCounter(QueueCounter $counter)
    {
        if ($counter->location->user_id !== Auth::id()) abort(403);
        $counter->delete();
        return back()->with('success', 'Loket berhasil dihapus.');
    }

    public function storeStaff(Request $request, QueueLocation $location)
    {
        if ($location->user_id !== Auth::id()) abort(403);

        $request->validate([
            'name' => 'required|string',
            'pin' => 'required|string|min:4|max:6',
            'queue_counter_id' => 'nullable|exists:queue_counters,id'
        ]);

        $location->staff()->create($request->only(['name', 'pin', 'queue_counter_id']));

        return back()->with('success', 'Staff berhasil ditambahkan.');
    }

    public function updateStaff(Request $request, QueueStaff $staff)
    {
        if ($staff->location->user_id !== Auth::id()) abort(403);

        $request->validate([
            'name' => 'required|string',
            'pin' => 'nullable|string|min:4|max:6',
            'queue_counter_id' => 'nullable|exists:queue_counters,id'
        ]);

        $data = $request->only(['name', 'queue_counter_id']);
        if ($request->filled('pin')) {
            $data['pin'] = $request->pin;
        }

        $staff->update($data);

        return back()->with('success', 'Staff berhasil diperbarui.');
    }

    public function deleteStaff(QueueStaff $staff)
    {
        if ($staff->location->user_id !== Auth::id()) abort(403);
        $staff->delete();
        return back()->with('success', 'Staff berhasil dihapus.');
    }

    public function downloadQr(QueueLocation $location)
    {
        if ($location->user_id !== Auth::id()) abort(403);

        $url = route('queue.register', $location->uuid);
        $imageContent = QrCodeFacade::format('svg')->size(500)->margin(2)->generate($url);
        $fileName = 'Queue-QR-' . Str::slug($location->name) . '.svg';

        return response($imageContent)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    public function analytics(Request $request)
    {
        $locations = QueueLocation::where('user_id', Auth::id())->get();
        $selectedLocationId = $request->query('location_id');
        
        $dateFrom = $request->query('date_from', now()->subDays(30)->toDateString());
        $dateTo = $request->query('date_to', now()->toDateString());

        $query = QueueTicket::whereHas('location', function($q) {
            $q->where('user_id', Auth::id());
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

        return view('dashboard.queue.analytics', compact(
            'locations', 'selectedLocationId', 'dateFrom', 'dateTo',
            'totalRegistrations', 'totalServed', 'totalNoShow',
            'avgWaitMinutes', 'avgServiceMinutes', 'popularService', 'dailyData'
        ));
    }
}
