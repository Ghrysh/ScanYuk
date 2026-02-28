<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user, $package, $transaction;

    public function __construct($user, $package, $transaction)
    {
        $this->user = $user;
        $this->package = $package;
        $this->transaction = $transaction;
    }

    public function build()
    {
        return $this->subject('Invoice Pembayaran ScanYuk - Paket ' . $this->package->name)
                    ->view('emails.payment-success');
    }
}