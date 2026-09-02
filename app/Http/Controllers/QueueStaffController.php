<?php

namespace App\Http\Controllers;

use App\Models\QueueLocation;
use App\Models\QueueStaff;
use App\Models\QueueTicket;
use Illuminate\Http\Request;

class QueueStaffController extends Controller
{
    public function loginForm()
    {
        $locations = QueueLocation::where('is_active', true)->get();
        return view('queue.staff-login', compact('locations'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'location_id' => 'required|exists:queue_locations,id',
            'staff_id' => 'required|exists:queue_staff,id',
            'pin' => 'required|string'
        ]);

        $staff = QueueStaff::find($request->staff_id);

        if (!$staff || $staff->queue_location_id != $request->location_id) {
            return back()->with('error', 'Data staff tidak valid untuk lokasi ini.');
        }

        if (!$staff->verifyPin($request->pin)) {
            return back()->with('error', 'PIN salah.');
        }

        session([
            'queue_staff_id' => $staff->id,
            'queue_location_id' => $request->location_id
        ]);

        return redirect()->route('queue.staff.dashboard');
    }

    public function dashboard()
    {
        $staffId = session('queue_staff_id');
        $staff = QueueStaff::with(['counter', 'location'])->find($staffId);
        
        if (!$staff) {
            return redirect()->route('queue.staff.logout');
        }

        $locationId = $staff->queue_location_id;

        $currentTicket = QueueTicket::where('queue_location_id', $locationId)
            ->where('queue_counter_id', $staff->queue_counter_id)
            ->whereIn('status', ['called', 'serving'])
            ->where('date', now()->toDateString())
            ->first();

        $waitingTicketsQuery = QueueTicket::where('queue_location_id', $locationId)
            ->where('status', 'waiting')
            ->where('date', now()->toDateString())
            ->orderBy('id', 'asc')
            ->get();

        $waitingTickets = $waitingTicketsQuery->groupBy('queue_service_id');

        $completedCount = QueueTicket::where('queue_location_id', $locationId)
            ->where('queue_counter_id', $staff->queue_counter_id)
            ->where('status', 'completed')
            ->where('date', now()->toDateString())
            ->count();

        $services = $staff->location->services()->where('is_active', true)->get();

        return view('queue.staff-dashboard', compact('staff', 'currentTicket', 'waitingTickets', 'completedCount', 'services'));
    }

    public function logout()
    {
        session()->forget(['queue_staff_id', 'queue_location_id']);
        return redirect()->route('queue.staff.login');
    }

    public function callNext(Request $request)
    {
        $staffId = session('queue_staff_id');
        $staff = QueueStaff::find($staffId);

        $query = QueueTicket::where('queue_location_id', $staff->queue_location_id)
            ->where('status', 'waiting')
            ->where('date', now()->toDateString());

        if ($request->filled('service_id')) {
            $query->where('queue_service_id', $request->service_id);
        }

        $nextTicket = $query->orderBy('id', 'asc')->first();

        if ($nextTicket) {
            $nextTicket->update([
                'status' => 'called',
                'called_at' => now(),
                'queue_counter_id' => $staff->queue_counter_id
            ]);
            return back()->with('success', 'Antrian dipanggil.');
        }

        return back()->with('error', 'Tidak ada antrian menunggu.');
    }

    public function startServing(QueueTicket $ticket)
    {
        $ticket->update([
            'status' => 'serving',
            'serving_at' => now()
        ]);
        return back()->with('success', 'Mulai melayani.');
    }

    public function complete(QueueTicket $ticket)
    {
        $ticket->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);
        return back()->with('success', 'Pelayanan selesai.');
    }

    public function skip(QueueTicket $ticket)
    {
        $ticket->update([
            'status' => 'skipped',
            'completed_at' => now()
        ]);
        return back()->with('success', 'Antrian dilewati.');
    }

    public function recall(QueueTicket $ticket)
    {
        $staffId = session('queue_staff_id');
        $staff = QueueStaff::find($staffId);
        
        $ticket->update([
            'status' => 'called',
            'called_at' => now(),
            'queue_counter_id' => $staff->queue_counter_id
        ]);
        return back()->with('success', 'Antrian dipanggil ulang.');
    }
}
