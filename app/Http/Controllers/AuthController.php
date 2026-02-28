<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\OtpVerification;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function showLogin() {
        return view('auth.login');
    }

    public function showRegister(Request $request) {
        $planParam = strtolower($request->query('plan', 'free'));

        $planMap = [
            'gratis'       => 'free',
            'free'         => 'free',
            'pemula'       => 'starter',
            'starter'      => 'starter',
            'profesional'  => 'professional',
            'professional' => 'professional',
            'bisnis'       => 'business',
            'business'     => 'business',
        ];

        $plan = $planMap[$planParam] ?? 'free';

        return view('auth.register', ['plan' => $plan]);
    }

    public function sendOtp(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        $email = $request->email;
        $otp = rand(100000, 999999);

        DB::table('otp_verifications')->updateOrInsert(
            ['email' => $email],
            [
                'otp_code' => $otp,
                'expires_at' => Carbon::now()->addMinutes(5),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        );

        Mail::to($email)->send(new OtpMail($otp));

        return response()->json(['status' => 'success', 'message' => 'OTP terkirim ke email Anda.']);
    }

    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'otp_combined' => 'required|numeric|digits:6',
            'plan' => 'nullable|string'
        ]);

        $verification = \Illuminate\Support\Facades\DB::table('otp_verifications')
            ->where('email', $request->email)
            ->where('otp_code', $request->otp_combined)
            ->where('expires_at', '>', \Carbon\Carbon::now())
            ->first();

        if (!$verification) {
            return back()->withErrors(['otp_combined' => 'Kode OTP salah atau sudah kadaluarsa.'])->withInput();
        }

        $planInput = strtolower($request->input('plan', 'free'));
        $planMap = ['gratis' => 'free', 'free' => 'free', 'pemula' => 'starter', 'starter' => 'starter', 'profesional' => 'professional', 'professional' => 'professional', 'bisnis' => 'business', 'business' => 'business'];
        $targetPlan = $planMap[$planInput] ?? 'free';

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'free', 
        ]);

        \Illuminate\Support\Facades\Auth::login($user);

        if ($targetPlan !== 'free') {
            $roleMap = ['starter' => 'Pemula', 'professional' => 'Profesional', 'business' => 'Bisnis'];
            $pkgName = $roleMap[$targetPlan] ?? '';
            $package = \App\Models\PricingPackage::where('name', 'like', "%{$pkgName}%")->first();
            
            if ($package && $package->price > 0) {
                return redirect()->route('payment.auto', ['package_id' => $package->id]);
            }
        }

        return redirect()->route('user.dashboard')->with('success', 'Registrasi berhasil!');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->status === 'suspended') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'suspended' => 'Akun Anda telah ditangguhkan (Suspended).'
                ])->onlyInput('email');
            }

            $request->session()->regenerate();
            
            $dashboards = [
                'admin'        => '/admin/dashboard',
                'free'         => '/dashboard',
                'starter'      => '/dashboard',
                'professional' => '/dashboard',
                'business'     => '/dashboard',
            ];

            $redirectUrl = $dashboards[$user->role] ?? '/dashboard';
            
            return redirect($redirectUrl);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
