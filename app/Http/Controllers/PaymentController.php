<?php

namespace App\Http\Controllers;

use App\Models\PricingPackage;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function checkout(Request $request)
    {
        $request->validate(['package_id' => 'required|exists:pricing_packages,id']);
        
        $user = Auth::user();
        $package = PricingPackage::findOrFail($request->package_id);
        
        $transactionId = (string) Str::uuid();

        $transaction = Transaction::create([
            'id' => $transactionId,
            'user_id' => $user->id,
            'pricing_package_id' => $package->id,
            'amount' => $package->price,
            'status' => 'Pending'
        ]);

        $body = [
            'product' => ['Paket ' . $package->name . ' - ScanYuk AR'],
            'qty' => ['1'],
            'price' => [$package->price],
            'returnUrl' => route('user.dashboard'),
            'cancelUrl' => route('user.dashboard'),
            'notifyUrl' => url('/api/ipaymu/callback'),
            'referenceId' => $transactionId,
            'buyerName' => $user->name,
            'buyerEmail' => $user->email,
        ];

        $va = env('IPAYMU_VA');
        $apiKey = env('IPAYMU_API_KEY');
        $url = env('IPAYMU_ENV') === 'sandbox' ? 'https://sandbox.ipaymu.com/api/v2/payment' : 'https://my.ipaymu.com/api/v2/payment';
        
        $jsonBody = json_encode($body, JSON_UNESCAPED_SLASHES);
        $requestBodyHash = hash('sha256', $jsonBody);
        $stringToSign = "POST:" . $va . ":" . $requestBodyHash . ":" . $apiKey;
        $signature = hash_hmac('sha256', $stringToSign, $apiKey);
        $timestamp = date('YmdHis');

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'signature' => $signature,
            'va' => $va,
            'timestamp' => $timestamp
        ])->post($url, $body);

        $resData = $response->json();

        if ($response->successful() && isset($resData['Status']) && $resData['Status'] == 200) {
            return redirect($resData['Data']['Url']);
        }

        dd([
            'message' => 'GAGAL MENGHUBUNGI IPAYMU!',
            'Pesan dari iPaymu' => $resData, 
            'Cek VA Kamu' => $va, 
            'Cek API Key' => $apiKey
        ]);
    }

    public function callback(Request $request)
    {
        $status_code = $request->status_code;
        $transactionId = $request->reference_id;

        $transaction = Transaction::find($transactionId);

        if ($transaction) {
            if ($status_code == 1 || strtolower($request->status) == 'berhasil') {
                $transaction->update(['status' => 'Paid']);

                $user = User::find($transaction->user_id);
                $package = PricingPackage::find($transaction->pricing_package_id);
                
                $roleMap = [
                    'Bisnis' => User::ROLE_BUSINESS,
                    'Profesional' => User::ROLE_PROFESSIONAL,
                    'Pemula' => User::ROLE_STARTER,
                    'Gratis' => User::ROLE_FREE
                ];

                $newRole = $roleMap[$package->name] ?? strtolower($package->name);

                $user->update([
                    'role' => $newRole,
                    'image' => 0,
                    'voice' => 0,
                    'scan' => 0
                ]);
            } elseif ($status_code == -1 || strtolower($request->status) == 'expired') {
                $transaction->update(['status' => 'Failed']);
            }
        }

        return response()->json(['status' => 'OK']);
    }
}
