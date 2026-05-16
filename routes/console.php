<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ── Recordatorio WhatsApp 24h antes de cada cita (corre cada hora)
Schedule::command('appointments:send-reminders')->hourly();

// ── Reporte diario por correo a las 08:00 AM para Admin y Tutores
Schedule::command('appointments:daily-report')->dailyAt('08:00');
