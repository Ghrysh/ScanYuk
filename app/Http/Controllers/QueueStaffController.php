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
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        $staff = \App\Models\QueueStaff::where('username', $request->username)->first();

        if (!$staff || !$staff->verifyPassword($request->password)) {
            return back()->with('error', 'Username atau password salah.');
        }

        if (!$staff->is_active) {
            return back()->with('error', 'Akun petugas tidak aktif.');
        }
        
        session([
            'queue_staff_id' => $staff->id,
            'queue_location_id' => $staff->queue_location_id
        ]);
        
        return redirect()->route('queue.staff.dashboard');
    }

    public function dashboard()
    {
        $staffId = session('queue_staff_id');
        $staff = \App\Models\QueueStaff::find($staffId);
        
        if (!$staff) {
            return redirect()->route('queue.staff.login');
        }

        $locationId = $staff->queue_location_id;

        $currentTicket = QueueTicket::where('queue_location_id', $locationId)
            ->where('queue_counter_id', $staff->queue_counter_id)
            ->whereIn('status', ['called', 'serving'])
            ->where('date', now()->toDateString())
            ->first();

        $waitingTickets = QueueTicket::where('queue_location_id', $locationId)
            ->where('status', 'waiting')
            ->where('date', now()->toDateString())
            ->with('service')
            ->orderBy('id', 'asc')
            ->get();

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

        if ($ticket->customer_name) {
            $userId = $ticket->location->user_id;
            
            $customer = \App\Models\QueueCustomer::where('user_id', $userId)
                ->where(function($q) use ($ticket) {
                    if ($ticket->customer_phone) {
                        $q->where('phone', $ticket->customer_phone);
                    } else {
                        $q->where('name', $ticket->customer_name)->whereNull('phone');
                    }
                })->first();

            if (!$customer) {
                \App\Models\QueueCustomer::create([
                    'user_id' => $userId,
                    'name' => $ticket->customer_name,
                    'phone' => $ticket->customer_phone,
                    'points' => 0,
                    'visits' => 1
                ]);
            } else {
                $customer->increment('visits');
            }
        }

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

    public function callSpecific(QueueTicket $ticket)
    {
        $staffId = session('queue_staff_id');
        $staff = QueueStaff::find($staffId);
        
        $ticket->update([
            'status' => 'called',
            'called_at' => now(),
            'queue_counter_id' => $staff->queue_counter_id
        ]);
        return back()->with('success', 'Antrian ' . $ticket->queue_number . ' dipanggil.');
    }
}
