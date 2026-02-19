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
        $plan = $request->query('plan', 'free');

        $allowedPlans = ['free', 'starter', 'professional', 'business'];
        
        if (!in_array($plan, $allowedPlans)) {
            $plan = 'free';
        }

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
            'otp_combined' => 'required|numeric|digits:6' 
        ]);

        $verification = DB::table('otp_verifications')
            ->where('email', $request->email)
            ->where('otp_code', $request->otp_combined)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$verification) {
            return back()->withErrors(['otp_combined' => 'Kode OTP salah atau sudah kadaluarsa.'])->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        DB::table('otp_verifications')->where('email', $request->email)->delete();

        Auth::login($user);

        return redirect('/')->with('success', 'Akun berhasil dibuat!');
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
                User::ROLE_ADMIN        => '/admin/dashboard',
                User::ROLE_FREE         => '/dashboard',
                User::ROLE_STARTER      => '/dashboard',
                User::ROLE_PROFESSIONAL => '/dashboard',
                User::ROLE_BUSINESS     => '/dashboard',
            ];

            $redirectUrl = $dashboards[$user->role] ?? '/';
            return redirect()->intended($redirectUrl);
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
