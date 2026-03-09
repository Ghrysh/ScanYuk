<?php

namespace App\Http\Controllers;

use App\Models\PricingPackage;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        Transaction::where('status', 'Pending')
            ->where('created_at', '<', now()->subHours(24))
            ->update(['status' => 'Batal']);

        $packages = PricingPackage::all();
        $users = User::where('role', '!=', 'admin')->latest()->paginate(10, ['*'], 'users_page');
        $transactions = Transaction::with(['user', 'package'])->latest()->paginate(10, ['*'], 'txn_page');

        $totalUsers = User::where('role', '!=', 'admin')->count();
        $totalQrCodes = User::sum('image'); 
        $totalScans = User::sum('scan'); 
        $grossRevenue = Transaction::whereIn('status', ['Berhasil', 'Paid', 'success'])->sum('amount');
        $totalRevenue = round($grossRevenue / 1.11);

        $usersByRole = User::select('role', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
                            ->groupBy('role')->pluck('total', 'role')->toArray();
        
        $countFree = $usersByRole['free'] ?? 0;
        $countStarter = $usersByRole['starter'] ?? 0;
        $countPro = $usersByRole['professional'] ?? 0;
        $countBusiness = $usersByRole['business'] ?? 0;

        $txnSuccess = Transaction::whereIn('status', ['Berhasil', 'Paid', 'success'])->count();
        $txnFailed = Transaction::whereIn('status', ['Batal', 'Failed', 'Pending'])->count();

        $webVisitors = 0;
        $demoScans = 0;
        $contactMessages = [];

        return view('admin.dashboard', compact(
            'packages', 'users', 'transactions', 'totalUsers', 'totalQrCodes', 'totalScans', 'totalRevenue',
            'countFree', 'countStarter', 'countPro', 'countBusiness', 'txnSuccess', 'txnFailed',
            'webVisitors', 'demoScans', 'contactMessages'
        ));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'package_id' => 'required|exists:pricing_packages,id'
        ]);

        $package = PricingPackage::find($request->package_id);
        $role = strtolower($package->name);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role === 'pemula' ? 'starter' : ($role === 'profesional' ? 'professional' : ($role === 'bisnis' ? 'business' : 'free')),
            'status' => 'active',
            'email_verified_at' => now(),
            'image' => 0,
            'voice' => 0,
            'scan' => 0,
        ]);

        return back()->with(['success' => "Akun {$request->name} berhasil ditambahkan!", 'active_tab' => 'users']);
    }

    public function destroyUser(User $user)
    {
        $name = $user->name;
        $user->delete();
        return back()->with(['success' => "Akun {$name} beserta datanya berhasil dihapus permanen.", 'active_tab' => 'users']);
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
            })->paginate(10);

        return view('admin.partials._user_table', compact('users'))->render();
    }

    public function updatePackage(Request $request, \App\Models\PricingPackage $package)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image_limit' => 'required|integer|min:0',
            'voice_limit' => 'required|integer|min:0',
            'scan_limit' => 'required|integer|min:0',
        ]);

        $package->name = $request->name;
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

    public function searchTransactions(Request $request)
    {
        $query = $request->get('query');
        
        $transactions = Transaction::with(['user', 'package'])
            ->where(function($q) use ($query) {
                $q->where('id', 'ilike', "%{$query}%")
                  ->orWhere('status', 'ilike', "%{$query}%")
                  ->orWhereHas('user', function($userQuery) use ($query) {
                      $userQuery->where('name', 'ilike', "%{$query}%");
                  });
            })
            ->latest()
            ->paginate(10, ['*'], 'txn_page');

        return view('admin.partials._transaction_table', compact('transactions'))->render();
    }
}
