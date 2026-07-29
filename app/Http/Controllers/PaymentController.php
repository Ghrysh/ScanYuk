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
        $request->validate([
            'package_id' => 'required|exists:pricing_packages,id',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:5120'
        ], [
            'payment_proof.required' => 'Bukti pembayaran wajib diunggah.',
            'payment_proof.image' => 'File harus berupa gambar.',
            'payment_proof.max' => 'Ukuran maksimal gambar adalah 5MB.'
        ]);
        
        $user = Auth::user();
        $package = PricingPackage::findOrFail($request->package_id);
        
        $transactionId = 'TRX-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        $ppn = $package->price * 0.11;
        $totalAmount = $package->price + $ppn;

        $path = $request->file('payment_proof')->store('payments', 'public');

        Transaction::create([
            'id' => $transactionId,
            'user_id' => $user->id,
            'pricing_package_id' => $package->id,
            'amount' => $totalAmount,
            'status' => 'Pending',
            'payment_proof' => $path
        ]);

        return redirect()->route('user.dashboard')->with('success', 'Bukti pembayaran berhasil diunggah. Silakan tunggu konfirmasi dari admin.');
    }

    public function cancel($id)
    {
        $transaction = Transaction::find($id);
        if ($transaction && $transaction->status == 'Pending') {
            $transaction->update(['status' => 'Batal']);
        }

        return redirect()->route('user.dashboard')->withErrors(['error' => 'Pembayaran Dibatalkan. Anda saat ini menggunakan paket Gratis.']);
    }
}
