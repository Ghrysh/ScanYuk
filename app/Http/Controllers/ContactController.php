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

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'company' => 'nullable|string|max:255',
            'interest' => 'required|string',
            'message' => 'required|string',
        ]);

        Mail::to('ptbtt01@gmail.com')->send(new ContactSalesMail($validated));

        return back()->with('success', 'Pesan Anda telah berhasil dikirim! Tim sales kami akan segera menghubungi Anda.');
    }
}