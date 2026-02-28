<?php

namespace App\Http\Controllers;

use App\Models\PricingPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $packages = PricingPackage::all();

        $roleMap = [
            'business' => 'Bisnis',
            'professional' => 'Profesional',
            'starter' => 'Pemula',
            'free' => 'Gratis'
        ];
        
        $searchRole = $roleMap[strtolower($user->role)] ?? $user->role;
        
        $currentPackage = PricingPackage::where('name', 'like', "%{$searchRole}%")->first();

        if (!$currentPackage && $packages->isNotEmpty()) {
            $currentPackage = $packages->where('price', 0)->first() ?? $packages->first();
        }

        $qrCodes = $user->qrCodes()->latest()->get();

        return view('dashboard.user', compact('user', 'packages', 'currentPackage', 'qrCodes'));
    }
}