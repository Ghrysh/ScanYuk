<?php

namespace App\Http\Controllers;

use App\Models\PricingPackage;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentSuccessMail;

class PaymentController extends Controller
{
    public function autoCheckout($package_id)
    {
        $request = new Request(['package_id' => $package_id]);
        return $this->checkout($request);
    }

public function checkout(Request $request)
    {
        $request->validate(['package_id' => 'required|exists:pricing_packages,id']);
        
        $user = Auth::user();
        $package = PricingPackage::findOrFail($request->package_id);
        
        $transactionId = 'TRX-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        $ppn = $package->price * 0.11;
        $totalAmount = $package->price + $ppn;

        Transaction::create([
            'id' => $transactionId,
            'user_id' => $user->id,
            'pricing_package_id' => $package->id,
            'amount' => $totalAmount,
            'status' => 'Pending' 
        ]);

        $body = [
            'product' => ['Paket ' . $package->name . ' - ScanYuk AR', 'PPN (11%)'],
            'qty' => ['1', '1'],
            'price' => [$package->price, $ppn],
            'returnUrl' => route('user.dashboard', ['payment' => 'success']), 
            'cancelUrl' => route('payment.cancel', ['id' => $transactionId]),
            'notifyUrl' => url('/api/ipaymu/callback'),
            'referenceId' => $transactionId,
            'buyerName' => $user->name,
            'buyerEmail' => $user->email,
        ];

        return $this->sendToIpaymu($body);
    }

    public function cancel($id)
    {
        $transaction = Transaction::find($id);
        if ($transaction && $transaction->status == 'Pending') {
            $transaction->update(['status' => 'Batal']);
        }

        return redirect()->route('user.dashboard')->withErrors(['error' => 'Pembayaran Dibatalkan. Anda saat ini menggunakan paket Gratis.']);
    }

    private function sendToIpaymu($body)
    {
        $va = config('services.ipaymu.va') ?? env('IPAYMU_VA');
        $apiKey = config('services.ipaymu.api_key') ?? env('IPAYMU_API_KEY');
        $envMode = config('services.ipaymu.env') ?? env('IPAYMU_ENV', 'production');
        
        if (empty($va) || empty($apiKey)) dd('ERROR: IPAYMU_VA atau API_KEY kosong di .env!');

        $url = $envMode === 'sandbox' ? 'https://sandbox.ipaymu.com/api/v2/payment' : 'https://my.ipaymu.com/api/v2/payment';
        
        $jsonBody = json_encode($body, JSON_UNESCAPED_SLASHES);
        $signature = hash_hmac('sha256', "POST:" . $va . ":" . hash('sha256', $jsonBody) . ":" . $apiKey, $apiKey);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json', 'signature' => $signature, 'va' => $va, 'timestamp' => date('YmdHis')
        ])->post($url, $body);

        $resData = $response->json();
        
        if ($response->successful() && isset($resData['Status']) && $resData['Status'] == 200) {
            return redirect($resData['Data']['Url']);
        }
        return back()->withErrors(['error' => 'Gagal terhubung ke gateway pembayaran iPaymu.']);
    }

    public function callback(Request $request)
    {
        $transaction = Transaction::find($request->reference_id);
        
        if ($transaction) {
            $statusStr = strtolower($request->status ?? '');
            
            if ($request->status_code == 1 || in_array($statusStr, ['berhasil', 'success', 'paid', 'unsettled'])) {
                
                if ($transaction->status !== 'Berhasil') {
                    $transaction->update(['status' => 'Berhasil']);

                    $user = User::find($transaction->user_id);
                    $package = PricingPackage::find($transaction->pricing_package_id);
                    
                    $roleMap = ['Bisnis' => User::ROLE_BUSINESS, 'Profesional' => User::ROLE_PROFESSIONAL, 'Pemula' => User::ROLE_STARTER, 'Gratis' => User::ROLE_FREE];
                    $newRole = $roleMap[$package->name] ?? strtolower($package->name);

                    \App\Models\QrCode::where('user_id', $user->id)->delete();
                    $user->update(['role' => $newRole, 'image' => 0, 'voice' => 0, 'scan' => 0]);

                    Mail::to($user->email)->send(new PaymentSuccessMail($user, $package, $transaction));
                }

            } elseif ($request->status_code == -1 || in_array($statusStr, ['expired', 'batal', 'failed', 'cancel'])) {
                $transaction->update(['status' => 'Batal']);
            }
        }
        
        return response()->json(['status' => 'OK']);
    }
}
