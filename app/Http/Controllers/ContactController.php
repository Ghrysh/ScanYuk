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
            'name' => 'required|string',
            'company' => 'required|string',
            'email' => 'required|email',
            'industry' => 'required|string',
            'volume' => 'required|string',
            'message' => 'required|string',
        ]);

        $recipient = env('MAIL_SALES_TO', 'admin@scanyuk.com');
        
        Mail::to($recipient)->send(new ContactSalesMail($request->all()));

        return back()->with('success', 'Pesan Anda telah terkirim! Tim kami akan segera menghubungi Anda.');
    }
}