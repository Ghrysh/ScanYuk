<?php

namespace App\Http\Controllers;

use App\Models\PricingPackage;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $packages = PricingPackage::all();
        $users = User::where('role', '!=', 'admin')->get();
        $transactions = Transaction::with(['user', 'package'])->latest()->get();

        $totalUsers = User::where('role', '!=', 'admin')->count();
        
        $totalQrCodes = User::sum('image'); 
        
        $totalScans = User::sum('scan'); 
        
        $totalRevenue = Transaction::where('status', 'Success')->sum('amount');

        return view('admin.dashboard', compact(
            'packages', 
            'users', 
            'transactions', 
            'totalUsers', 
            'totalQrCodes', 
            'totalScans', 
            'totalRevenue'
        ));
    }

    public function toggleStatus(User $user)
    {
        $currentStatus = $user->status ?? 'active';
        $user->status = $currentStatus === 'active' ? 'suspended' : 'active';
        $user->save();

        return back()->with([
            'success' => "Status user {$user->name} berhasil diubah menjadi " . ucfirst($user->status),
            'active_tab' => 'users'
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->get('query');
        
        $users = User::where('role', '!=', 'admin')
            ->where(function($q) use ($query) {
                $q->where('name', 'ilike', "%{$query}%")
                ->orWhere('email', 'ilike', "%{$query}%");
            })
            ->get();

        return view('admin.partials._user_table', compact('users'))->render();
    }

    public function updatePackage(Request $request, \App\Models\PricingPackage $package)
    {
        $request->validate([
            'price' => 'required|numeric|min:0',
            'image_limit' => 'required|integer|min:0',
            'voice_limit' => 'required|integer|min:0',
            'scan_limit' => 'required|integer|min:0',
        ]);

        $package->price = $request->price;
        
        $package->features = [
            $request->image_limit . " AR Image",
            $request->voice_limit . " Voice Narration",
            $request->scan_limit . " Total Scans",
            $package->features[3] ?? 'Basic analytics',
            $package->features[4] ?? 'Download QR',
        ];

        $package->save();

        return back()->with([
            'success' => "Paket {$package->name} berhasil diperbarui!",
            'active_tab' => 'paket'
        ]);
    }
}