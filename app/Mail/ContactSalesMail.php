<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactSalesMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Sales Inquiry: ' . $this->data['company'],
        );
    }

    public function content(): Content
    {
        return new Content(
            htmlString: '
                <h1>New Demo Request</h1>
                <p><strong>Nama:</strong> '. $this->data['name'] .'</p>
                <p><strong>Perusahaan:</strong> '. $this->data['company'] .'</p>
                <p><strong>Email:</strong> '. $this->data['email'] .'</p>
                <p><strong>Industri:</strong> '. $this->data['industry'] .'</p>
                <p><strong>Volume:</strong> '. $this->data['volume'] .'</p>
                <p><strong>Pesan:</strong> <br>'. nl2br($this->data['message']) .'</p>
            ',
        );
    }
}