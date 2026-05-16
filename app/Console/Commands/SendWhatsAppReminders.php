<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendWhatsAppReminders extends Command
{
    protected $signature   = 'appointments:send-reminders';
    protected $description = 'Envía recordatorios de WhatsApp para las citas que ocurrirán exactamente en 24 horas.';

    public function handle(): void
    {
        $targetDate = now()->addDay()->toDateString();

        $appointments = Appointment::with(['student', 'tutor'])
            ->whereDate('date', $targetDate)
            ->where('status', 'Programada')
            ->get();

        if ($appointments->isEmpty()) {
            $this->info('No hay citas programadas para mañana. No se enviaron recordatorios.');
            return;
        }

        $sent = 0;

        foreach ($appointments as $appointment) {
            $token       = config('services.whatsapp.token');
            $phoneId     = config('services.whatsapp.phone_id');
            $countryCode = $appointment->student->country_code ?? '52';
            $phone       = $appointment->student->phone;

            if (!$token || !$phoneId || !$phone) {
                $this->warn("WhatsApp: Sin teléfono para {$appointment->student->name}, se omite.");
                continue;
            }

            $phone = $countryCode . preg_replace('/[^0-9]/', '', $phone);

            $studentName = $appointment->student->name;
            $tutorName   = $appointment->tutor->name;
            $date        = \Carbon\Carbon::parse($appointment->date)->format('d/m/Y');
            $startTime   = \Carbon\Carbon::parse($appointment->start_time)->format('H:i');

            $message = "Hola {$studentName} 👋, te recordamos que mañana ({$date}) a las {$startTime} tienes una cita de tutoría con {$tutorName} en el Centro Red Apple. ¡No olvides asistir!";

            Log::info("WhatsApp Recordatorio: enviando a {$phone}");

            $response = Http::withToken($token)
                ->post("https://graph.facebook.com/v17.0/{$phoneId}/messages", [
                    'messaging_product' => 'whatsapp',
                    'to'   => $phone,
                    'type' => 'text',
                    'text' => ['body' => $message],
                ]);

            if ($response->successful()) {
                $sent++;
            } else {
                Log::error("WhatsApp Recordatorio: Error para {$phone}: " . $response->body());
            }
        }

        $this->info("Recordatorios WhatsApp enviados: {$sent} de {$appointments->count()}.");
    }
}
