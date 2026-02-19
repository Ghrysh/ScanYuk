<?php

use App\Models\PricingPackage;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;

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
        Route::get('/admin/dashboard', function () {
            return "Halaman Admin Dashboard";
        });
    });

    // Dashboard Free
    Route::middleware(['role:free'])->group(function () {
        Route::get('/dashboard/free', function () {
            return "Halaman Paket Free";
        });
    });

    // Dashboard Starter
    Route::middleware(['role:starter'])->group(function () {
        Route::get('/dashboard/starter', function () {
            return "Halaman Paket Starter";
        });
    });

    // Dashboard Professional
    Route::middleware(['role:professional'])->group(function () {
        Route::get('/dashboard/professional', function () {
            return "Halaman Paket Professional";
        });
    });

    // Dashboard Business
    Route::middleware(['role:business'])->group(function () {
        Route::get('/dashboard/business', function () {
            return "Halaman Paket Business";
        });
    });

});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');