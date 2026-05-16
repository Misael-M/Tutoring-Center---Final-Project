<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $pdfContent;

    public function __construct(Appointment $appointment, string $pdfContent)
    {
        $this->appointment = $appointment;
        $this->pdfContent  = $pdfContent;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmación de Cita de Tutoría — Centro Red Apple',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.appointment_created',
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->pdfContent, 'Comprobante_Cita_' . $this->appointment->id . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
