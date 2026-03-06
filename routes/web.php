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
use App\Http\Controllers\PaymentController;

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

    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::get('/checkout/register/{refId}', [\App\Http\Controllers\PaymentController::class, 'registerCheckout'])->name('payment.register_checkout');

Route::middleware(['auth'])->group(function () {

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
        Route::delete('/admin/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
        Route::patch('/admin/users/{user}/toggle-status', [AdminController::class, 'toggleStatus'])->name('admin.users.toggle-status');
        Route::get('/admin/users/search', [AdminController::class, 'search'])->name('admin.users.search');
        Route::get('/admin/transactions/search', [AdminController::class, 'searchTransactions'])->name('admin.transactions.search');
        Route::patch('/admin/packages/{package}', [AdminController::class, 'updatePackage'])->name('admin.packages.update');
    });

    Route::middleware(['auth', 'role:free,starter,professional,business'])->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\UserDashboardController::class, 'index'])->name('user.dashboard');
        Route::get('/dashboard/ar/create', [\App\Http\Controllers\QrCodeController::class, 'create'])->name('user.ar.create');
        Route::post('/dashboard/ar/store', [\App\Http\Controllers\QrCodeController::class, 'store'])->name('user.ar.store');
        Route::patch('/dashboard/ar/{qrCode}/toggle-status', [\App\Http\Controllers\QrCodeController::class, 'toggleStatus'])->name('user.ar.toggle-status');
        Route::get('/dashboard/ar/{qrCode}/download', [\App\Http\Controllers\QrCodeController::class, 'download'])->name('user.ar.download');
    });

    Route::post('/checkout', [\App\Http\Controllers\PaymentController::class, 'checkout'])->name('payment.checkout');

    Route::get('/checkout/auto/{package_id}', [\App\Http\Controllers\PaymentController::class, 'autoCheckout'])->name('payment.auto');

    Route::get('/checkout/cancel/{id}', [\App\Http\Controllers\PaymentController::class, 'cancel'])->name('payment.cancel');

});

Route::get('/minio-proxy/{any}', function ($any) {
    $disk = Storage::disk('s3');
    
    $target = $any;
    if (!$disk->exists($target)) {
        $target = urldecode($any);
        if (!$disk->exists($target)) {
            $target = str_replace('+', ' ', $target);
            if (!$disk->exists($target)) {
                abort(404, 'File 3D tidak ditemukan di MinIO.');
            }
        }
    }

    $headers = [
        'Access-Control-Allow-Origin' => '*',
        'Cache-Control' => 'no-transform, public, max-age=31536000, immutable', 
    ];

    if (\Illuminate\Support\Str::endsWith(strtolower($target), '.glb')) {
        $headers['Content-Type'] = 'model/gltf-binary';
    }

    return $disk->response($target, null, $headers);
})->where('any', '.*')->name('minio.proxy');

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
