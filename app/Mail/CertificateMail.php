<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CertificateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $certificate;
    public $event;

    public function __construct($certificate, $event)
    {
        $this->certificate = $certificate;
        $this->event = $event;
    }

    public function build()
    {
        return $this->subject(
                'Certificate - ' . $this->event->nama_event
            )
            ->view('emails.certificate')
            ->attach(
                storage_path(
                    'app/public/' . $this->certificate->file_url
                ),
                [
                    'as' => 'certificate.png',
                    'mime' => 'image/png',
                ]
            );
    }
}