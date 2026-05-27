<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendCertificate extends Mailable 
{
    use Queueable, SerializesModels;

    public $certificate;
    public $eventName;

    public function __construct($certificate, $eventName)
    {
        $this->certificate = $certificate;
        $this->eventName = $eventName;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Certificate - ' . $this->eventName,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mails.send-certificate',
        );
    }

    public function attachments(): array
    {
        $attachments = [];

        if ($this->certificate->file_url && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->certificate->file_url)) {
            $attachments[] = \Illuminate\Mail\Mailables\Attachment::fromPath(
                storage_path('app/public/' . $this->certificate->file_url)
            )->as('certificate.png');
        }

        return $attachments;
    }
}
