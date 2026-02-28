<?php

namespace App\Http\Controllers;

use App\Models\PricingPackage;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentSuccessMail;

class PaymentController extends Controller
{
    public function registerCheckout($refId)
    {
        $userData = Cache::get($refId);

        if (!$userData) {
            dd('ERROR CACHE: Data pendaftaran gagal disimpan sementara. Cek CACHE_DRIVER di .env kamu!');
        }
        $package = PricingPackage::findOrFail($userData['package_id']);

        $body = [
            'product' => ['Paket ' . $package->name . ' - ScanYuk AR'],
            'qty' => ['1'],
            'price' => [$package->price],
            'returnUrl' => route('login', ['payment' => 'success']),
            'cancelUrl' => route('register'),
            'notifyUrl' => url('/api/ipaymu/callback'),
            'referenceId' => $refId,
            'buyerName' => $userData['name'],
            'buyerEmail' => $userData['email'],
        ];

        return $this->sendToIpaymu($body);
    }

    public function checkout(Request $request)
    {
        $request->validate(['package_id' => 'required|exists:pricing_packages,id']);
        
        $user = Auth::user();
        $package = PricingPackage::findOrFail($request->package_id);
        $transactionId = (string) Str::uuid();

        Transaction::create([
            'id' => $transactionId, 'user_id' => $user->id, 'pricing_package_id' => $package->id,
            'amount' => $package->price, 'status' => 'Pending'
        ]);

        $body = [
            'product' => ['Paket ' . $package->name . ' - ScanYuk AR'],
            'qty' => ['1'], 'price' => [$package->price],
            'returnUrl' => route('user.dashboard'), 'cancelUrl' => route('user.dashboard'),
            'notifyUrl' => url('/api/ipaymu/callback'),
            'referenceId' => $transactionId,
            'buyerName' => $user->name, 'buyerEmail' => $user->email,
        ];

        return $this->sendToIpaymu($body);
    }

    private function sendToIpaymu($body)
    {
        $va = config('services.ipaymu.va') ?? env('IPAYMU_VA');
        $apiKey = config('services.ipaymu.api_key') ?? env('IPAYMU_API_KEY');
        $envMode = config('services.ipaymu.env') ?? env('IPAYMU_ENV', 'sandbox');

        if (empty($va) || empty($apiKey)) {
            dd('SISTEM BERHENTI: File .env kamu tidak terbaca atau IPAYMU_VA / IPAYMU_API_KEY masih kosong!');
        }

        $url = $envMode === 'sandbox' ? 'https://sandbox.ipaymu.com/api/v2/payment' : 'https://my.ipaymu.com/api/v2/payment';
        
        $jsonBody = json_encode($body, JSON_UNESCAPED_SLASHES);
        $signature = hash_hmac('sha256', "POST:" . $va . ":" . hash('sha256', $jsonBody) . ":" . $apiKey, $apiKey);

        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'Content-Type' => 'application/json', 
            'signature' => $signature, 
            'va' => $va, 
            'timestamp' => date('YmdHis')
        ])->post($url, $body);

        $resData = $response->json();
        
        if ($response->successful() && isset($resData['Status']) && $resData['Status'] == 200) {
            return redirect($resData['Data']['Url']);
        }

        return back()->withErrors(['error' => 'Gagal terhubung ke gateway pembayaran iPaymu.']);
    }

    public function callback(Request $request)
    {
        $status_code = $request->status_code;
        $referenceId = $request->reference_id;

        if (str_starts_with($referenceId, 'reg_')) {
            $userData = Cache::get($referenceId);
            if ($userData && ($status_code == 1 || strtolower($request->status) == 'berhasil')) {

                $user = User::firstOrCreate(
                    ['email' => $userData['email']],
                    ['name' => $userData['name'], 'password' => $userData['password'], 'role' => $userData['role']]
                );

                $package = PricingPackage::find($userData['package_id']);

                $transaction = Transaction::create([
                    'id' => (string) Str::uuid(), 'user_id' => $user->id, 'pricing_package_id' => $package->id,
                    'amount' => $package->price, 'status' => 'Paid'
                ]);

                Mail::to($user->email)->send(new PaymentSuccessMail($user, $package, $transaction));
                
                Cache::forget($referenceId);
            }
            return response()->json(['status' => 'OK']);
        }

        $transaction = Transaction::find($referenceId);
        if ($transaction) {
            if ($status_code == 1 || strtolower($request->status) == 'berhasil') {
                $transaction->update(['status' => 'Paid']);

                $user = User::find($transaction->user_id);
                $package = PricingPackage::find($transaction->pricing_package_id);
                
                $roleMap = ['Bisnis' => User::ROLE_BUSINESS, 'Profesional' => User::ROLE_PROFESSIONAL, 'Pemula' => User::ROLE_STARTER, 'Gratis' => User::ROLE_FREE];
                $newRole = $roleMap[$package->name] ?? strtolower($package->name);

                \App\Models\QrCode::where('user_id', $user->id)->delete();
                $user->update(['role' => $newRole, 'image' => 0, 'voice' => 0, 'scan' => 0]);

                Mail::to($user->email)->send(new PaymentSuccessMail($user, $package, $transaction));
            } elseif ($status_code == -1 || strtolower($request->status) == 'expired') {
                $transaction->update(['status' => 'Failed']);
            }
        }
        return response()->json(['status' => 'OK']);
    }
}