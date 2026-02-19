<?php

namespace App\Http\Controllers;

use App\Mail\ContactSalesMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact');
    }

    public function send(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'email' => 'required|email:rfc,dns', 
            'industry' => 'required|string|max:255',
            'volume' => 'required|string|max:255',
            'message' => 'required|string',
        ], [
            'email.email' => 'Format email tidak valid.',
            'email.dns' => 'Domain email ini tidak dapat menerima pesan (tidak valid).',
        ]);

        $recipient = env('MAIL_SALES_TO', 'admin@scanyuk.com');
        
        Mail::to($recipient)->send(new ContactSalesMail($request->all()));

        return back()->with('success', 'Pesan Anda telah terkirim! Tim kami akan segera menghubungi Anda.');
    }
}