<?php

namespace App\Console\Commands;

use App\Mail\DailyReportMail;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDailyReports extends Command
{
    protected $signature   = 'appointments:daily-report';
    protected $description = 'Envía reportes diarios de citas al Administrador y a cada Tutor.';

    public function handle(): void
    {
        // 1. Citas del día
        $appointments = Appointment::with(['student', 'tutor'])
            ->whereDate('date', today())
            ->whereIn('status', ['Programada', 'Completada'])
            ->orderBy('start_time')
            ->get();

        // 2. Reporte general → Administradores
        $adminEmails = User::role('Administrador')->pluck('email')->toArray();

        if (!empty($adminEmails)) {
            Mail::to($adminEmails)->send(new DailyReportMail($appointments, 'Reporte General de Citas'));
            $this->info('Reporte general enviado a los administradores.');
            sleep(2); // Pausa para no exceder límite de Mailtrap
        } else {
            $this->warn('No se encontraron administradores para enviar el reporte.');
        }

        // 3. Reporte individual → cada Tutor con citas hoy
        $groupedByTutor = $appointments->groupBy('tutor_id');

        foreach ($groupedByTutor as $tutorId => $tutorAppointments) {
            $tutorUser = $tutorAppointments->first()->tutor;

            if ($tutorUser && $tutorUser->email) {
                sleep(2); // Evitar límite de Mailtrap
                Mail::to($tutorUser->email)->send(new DailyReportMail($tutorAppointments, 'Reporte de Mis Citas del Día'));
                $this->info("Reporte enviado a: {$tutorUser->name}.");
            }
        }

        $this->info('Proceso de reportes diarios finalizado.');
    }
}
