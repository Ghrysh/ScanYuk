<?php

use App\Models\PricingPackage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\Api\ScanController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/consumer', function () {
    $packages = PricingPackage::all();
    return view('consumer', compact('packages'));
})->name('consumer');

Route::get('/business', function () {
    return view('business');
})->name('business');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/solutions', function () {
    return view('solutions');
})->name('solutions');

Route::get('/pricing', function () {
    $packages = PricingPackage::all();
    return view('pricing', compact('packages'));
})->name('pricing');

Route::get('/demo', function () {
    return view('demo');
})->name('demo');

Route::get('/how-it-works', function () {
    return view('how-it-works');
})->name('how-it-works');

Route::get('/scan-ar', function () {
    return view('scanner');
})->name('scan-ar');

Route::get('/faq', function () {
    return view('faq');
})->name('faq');

Route::get('/security', function () {
    return view('security');
})->name('security');

Route::get('/terms', function () {
    return view('terms');
})->name('terms');

Route::get('/partners', function () {
    return view('partners');
})->name('partners');

Route::get('/refund-policy', function () {
    return view('refund-policy');
})->name('refund-policy');

Route::get('/privacy', function () {
    return view('privacy');
})->name('privacy');

Route::get('/case-studies', function () {
    return view('case-studies');
})->name('case-studies');

Route::get('/api/scan/{uuid}', [ScanController::class, 'scanQr']);

Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    Route::post('/send-otp', [AuthController::class, 'sendOtp'])->name('send-otp');
});

Route::middleware(['auth'])->group(function () {

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::patch('/admin/users/{user}/toggle-status', [AdminController::class, 'toggleStatus'])->name('admin.users.toggle-status');
        Route::get('/admin/users/search', [AdminController::class, 'search'])->name('admin.users.search');
        Route::patch('/admin/packages/{package}', [AdminController::class, 'updatePackage'])->name('admin.packages.update');
    });

    Route::middleware(['auth', 'role:free,starter,professional,business'])->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\UserDashboardController::class, 'index'])->name('user.dashboard');
        Route::get('/dashboard/ar/create', [\App\Http\Controllers\QrCodeController::class, 'create'])->name('user.ar.create');
        Route::post('/dashboard/ar/store', [\App\Http\Controllers\QrCodeController::class, 'store'])->name('user.ar.store');
        Route::patch('/dashboard/ar/{qrCode}/toggle-status', [\App\Http\Controllers\QrCodeController::class, 'toggleStatus'])->name('user.ar.toggle-status');
        Route::get('/dashboard/ar/{qrCode}/download', [\App\Http\Controllers\QrCodeController::class, 'download'])->name('user.ar.download');
    });

});

Route::get('/ar-models/{filename}', function ($filename) {
    $internalUrl = "http://minio:9000/scanyuk-3d-assets/" . $filename;

    return response()->stream(
        function () use ($internalUrl) {
            @readfile($internalUrl);
        },
        200,
        [
            'Content-Type' => 'model/gltf-binary',
            'Access-Control-Allow-Origin' => '*',
            'Cache-Control' => 'public, max-age=31536000, immutable',
            'Accept-Ranges' => 'bytes',
        ]
    );
})->name('ar.models');

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');