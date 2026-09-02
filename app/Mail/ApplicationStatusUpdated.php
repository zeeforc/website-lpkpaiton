<?php

namespace App\Mail;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Application $application,
        public ?string $note = null,
        public ?string $password = null
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Update Status Pendaftaran Praktik Kerja Lapangan',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.application-status',
            with: [
                'application' => $this->application,
                'note' => $this->note,
                'password' => $this->password,
            ],
        );
    }
}
