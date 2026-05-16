<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class DailyReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appointments;
    public $reportTitle;

    public function __construct(Collection $appointments, string $reportTitle)
    {
        $this->appointments = $appointments;
        $this->reportTitle  = $reportTitle;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Red Apple — ' . $this->reportTitle . ' (' . now()->format('d/m/Y') . ')',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.daily_report',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
