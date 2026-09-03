<?php

namespace App\Http\Controllers;

use App\Models\QueueLocation;
use App\Models\QueueTicket;
use Illuminate\Http\Request;

class QueuePublicController extends Controller
{
    public function register($uuid)
    {
        $location = QueueLocation::where('uuid', $uuid)->firstOrFail();
        
        if (!$location->is_active) {
            abort(404, 'Lokasi antrian sedang tidak aktif.');
        }

        $location->load(['services' => function($q) {
            $q->where('is_active', true)->withCount(['tickets as today_registrations_count' => function($query) { $query->where('date', now()->toDateString()); }])->orderBy('sort_order');
        }]);

        $arQrCode = null;
        if ($location->ar_qr_code_id) {
            $arQrCode = $location->arQrCode;
        }

        return view('queue.register', compact('location', 'arQrCode'));
    }

    public function store(Request $request, $uuid)
    {
        $location = QueueLocation::where('uuid', $uuid)->firstOrFail();
        
        if (!$location->is_active) {
            return back()->with('error', 'Lokasi antrian sedang tidak aktif.');
        }

        $request->validate([
            'queue_service_id' => 'required|exists:queue_services,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20'
        ]);

        $service = $location->services()->where('id', $request->queue_service_id)->firstOrFail();

        if (!$service->is_active) {
            return back()->with('error', 'Layanan ini sedang tidak aktif.');
        }

        if ($service->isQuotaFull()) {
            return back()->with('error', 'Kuota untuk layanan ini sudah penuh hari ini.');
        }

        $queueNumber = $service->generateNextQueueNumber();

        $ticket = QueueTicket::create([
            'queue_location_id' => $location->id,
            'queue_service_id' => $service->id,
            'queue_number' => $queueNumber,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'status' => 'waiting',
            'date' => now()->toDateString(),
        ]);

        return redirect()->route('queue.ticket', $ticket->id);
    }

    public function ticket($id)
    {
        $ticket = QueueTicket::with(['service', 'location', 'counter'])->findOrFail($id);
        $position = $ticket->getPositionInQueue();
        $estimatedWait = $ticket->getEstimatedWaitMinutes();

        return view('queue.ticket', compact('ticket', 'position', 'estimatedWait'));
    }

    public function display($uuid)
    {
        $location = QueueLocation::where('uuid', $uuid)->firstOrFail();
        
        $todayTickets = QueueTicket::where('queue_location_id', $location->id)
            ->where('date', now()->toDateString())
            ->with(['service', 'counter'])
            ->get();

        $calledTickets = $todayTickets->whereIn('status', ['called', 'serving'])
            ->sortByDesc('updated_at')
            ->values();
            
        $waitingTickets = $todayTickets->where('status', 'waiting')
            ->sortBy('id')
            ->values();

        $services = $location->services()->where('is_active', true)->orderBy('sort_order')->get();

        return view('queue.display', compact('location', 'calledTickets', 'waitingTickets', 'services'));
    }

    public function displayLeaderboard($userId)
    {
        $customers = \App\Models\QueueCustomer::where('user_id', $userId)
            ->orderBy('points', 'desc')
            ->orderBy('visits', 'desc')
            ->limit(10)
            ->get();
            
        return view('queue.display-leaderboard', compact('customers', 'userId'));
    }
    
    public function displayLeaderboardData($userId)
    {
        $customers = \App\Models\QueueCustomer::where('user_id', $userId)
            ->orderBy('points', 'desc')
            ->orderBy('visits', 'desc')
            ->limit(10)
            ->get();
            
        return response()->json([
            'customers' => $customers
        ]);
    }

    public function ticketStatus($id)
    {
        $ticket = QueueTicket::with('counter')->find($id);
        
        if (!$ticket) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return response()->json([
            'status' => $ticket->status,
            'position' => $ticket->getPositionInQueue(),
            'estimated_wait' => $ticket->getEstimatedWaitMinutes(),
            'queue_number' => $ticket->queue_number,
            'counter_name' => $ticket->counter ? $ticket->counter->name : null,
            'called_at' => $ticket->called_at ? $ticket->called_at->toIso8601String() : null
        ]);
    }

    public function displayData($uuid)
    {
        $location = QueueLocation::where('uuid', $uuid)->first();
        
        if (!$location) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $todayTickets = QueueTicket::where('queue_location_id', $location->id)
            ->where('date', now()->toDateString())
            ->with(['service', 'counter'])
            ->get();

        $calledTickets = $todayTickets->whereIn('status', ['called', 'serving'])
            ->sortByDesc('updated_at')
            ->values()
            ->map(function($t) {
                return [
                    'id' => $t->id,
                    'queue_number' => $t->queue_number,
                    'status' => $t->status,
                    'service_name' => $t->service->name,
                    'counter_name' => $t->counter ? $t->counter->name : null,
                ];
            });
            
        $waitingTickets = $todayTickets->where('status', 'waiting')
            ->sortBy('id')
            ->values()
            ->map(function($t) {
                return [
                    'id' => $t->id,
                    'queue_number' => $t->queue_number,
                    'service_name' => $t->service->name,
                ];
            });

        return response()->json([
            'called' => $calledTickets,
            'waiting' => $waitingTickets,
        ]);
    }
}
