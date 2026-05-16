<?php

namespace App\Jobs;

use App\Mail\AppointmentCreatedMail;
use App\Models\Appointment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendAppointmentCreatedNotifications implements ShouldQueue
{
    use Queueable;

    public $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function handle(): void
    {
        // Recargar relaciones para asegurar que estén presentes en la cola
        $this->appointment->load(['student', 'tutor', 'tutor.tutor']);

        // 1. Generar PDF
        $pdf = Pdf::loadView('pdf.appointment', ['appointment' => $this->appointment]);
        $pdfContent = $pdf->output();

        // 2. Enviar Correo al estudiante (CC al tutor)
        $studentEmail = $this->appointment->student->email;
        $tutorEmail   = $this->appointment->tutor->email;

        Mail::to($studentEmail)
            ->cc($tutorEmail)
            ->send(new AppointmentCreatedMail($this->appointment, $pdfContent));

        // 3. Enviar WhatsApp al estudiante
        $this->sendWhatsAppMessage();
    }

    protected function sendWhatsAppMessage(): void
    {
        $token   = config('services.whatsapp.token');
        $phoneId = config('services.whatsapp.phone_id');
        $countryCode = $this->appointment->student->country_code ?? '52';
        $phone       = $this->appointment->student->phone;

        if (!$token || !$phoneId || !$phone) {
            Log::warning('WhatsApp: No se pudo enviar confirmación. Faltan credenciales o el estudiante no tiene teléfono registrado.');
            return;
        }

        $phone = $countryCode . preg_replace('/[^0-9]/', '', $phone);

        $tutorName   = $this->appointment->tutor->name;
        $date        = \Carbon\Carbon::parse($this->appointment->date)->format('d/m/Y');
        $startTime   = \Carbon\Carbon::parse($this->appointment->start_time)->format('H:i');
        $studentName = $this->appointment->student->name;

        $message = "Hola {$studentName}, tu cita de tutoría con {$tutorName} ha sido confirmada para el {$date} a las {$startTime}. ¡Te esperamos!";

        Log::info("WhatsApp: Enviando confirmación a {$phone}");

        $response = Http::withToken($token)
            ->post("https://graph.facebook.com/v17.0/{$phoneId}/messages", [
                'messaging_product' => 'whatsapp',
                'to'   => $phone,
                'type' => 'text',
                'text' => ['body' => $message],
            ]);

        if ($response->failed()) {
            Log::error('WhatsApp: Error enviando confirmación: ' . $response->body());
        } else {
            Log::info("WhatsApp: Confirmación enviada exitosamente a {$phone}");
        }
    }
}
