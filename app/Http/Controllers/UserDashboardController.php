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
        
        $currentPackage = PricingPackage::where('name', 'ilike', $user->role)->first();

        $qrCodes = $user->qrCodes()->latest()->get();

        return view('dashboard.user', compact('user', 'packages', 'currentPackage', 'qrCodes'));
    }
}