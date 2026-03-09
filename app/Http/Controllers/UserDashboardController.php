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
            'free' => 1,
            'starter' => 2,
            'professional' => 3,
            'business' => 4
        ];
        
        $packageId = $roleMap[strtolower($user->role)] ?? 1;
        $currentPackage = PricingPackage::find($packageId);

        if (!$currentPackage && $packages->isNotEmpty()) {
            $currentPackage = $packages->first();
        }

        $qrCodes = $user->qrCodes()->latest()->get();

        return view('dashboard.user', compact('user', 'packages', 'currentPackage', 'qrCodes'));
    }
}