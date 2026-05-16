<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Jobs\SendAppointmentCreatedNotifications;
use App\Models\Appointment;
use App\Models\Schedule;
use App\Models\User;

class AppointmentManager extends Component
{
    public $appointment;
    public $isEdit = false;

    // Form fields
    public $student_id;
    public $tutor_id;
    public $date;
    public $start_time;
    public $end_time;
    public $reason;
    public $status = 'Programada';

    // Schedule selector
    public $selectedStartTime = '';  // Ej. "08:00"
    public $selectedEndTime   = '';  // Ej. "09:00"
    public $availableSlots    = [];  // Slots libres del día del tutor [{start,end}]
    public $availableEndTimes = [];  // Fin-times consecutivos desde el start elegido
    public $tutorDays         = [];  // Días con disponibilidad del tutor

    public function mount(Appointment $appointment = null)
    {
        if ($appointment && $appointment->exists) {
            $this->appointment       = $appointment;
            $this->isEdit            = true;
            $this->student_id        = $appointment->student_id;
            $this->tutor_id          = $appointment->tutor_id;
            $this->date              = $appointment->date;
            $this->start_time        = \Carbon\Carbon::parse($appointment->start_time)->format('H:i');
            $this->end_time          = \Carbon\Carbon::parse($appointment->end_time)->format('H:i');
            $this->reason            = $appointment->reason;
            $this->status            = $appointment->status;
            $this->selectedStartTime = $this->start_time;
            $this->selectedEndTime   = $this->end_time;

            $this->loadTutorDays();
            $this->loadAvailableSlots();
            // Pre-cargar los end-times para el start guardado
            $this->availableEndTimes = $this->calculateEndTimes();
        }
    }

    // ── Cuando cambia el tutor ──────────────────────────────────────────
    public function updatedTutorId(): void
    {
        $this->selectedStartTime = '';
        $this->selectedEndTime   = '';
        $this->availableSlots    = [];
        $this->availableEndTimes = [];
        $this->start_time        = '';
        $this->end_time          = '';
        $this->loadTutorDays();
        if ($this->date) { $this->loadAvailableSlots(); }
    }

    // ── Cuando cambia la fecha ─────────────────────────────────────────
    public function updatedDate(): void
    {
        $this->selectedStartTime = '';
        $this->selectedEndTime   = '';
        $this->availableSlots    = [];
        $this->availableEndTimes = [];
        $this->start_time        = '';
        $this->end_time          = '';
        if ($this->tutor_id && $this->date) { $this->loadAvailableSlots(); }
    }

    // ── Cuando elige hora de inicio ──────────────────────────────────
    public function updatedSelectedStartTime(): void
    {
        $this->selectedEndTime   = '';
        $this->end_time          = '';
        $this->start_time        = $this->selectedStartTime;
        $this->availableEndTimes = $this->calculateEndTimes();
    }

    // ── Cuando elige hora de fin ────────────────────────────────────
    public function updatedSelectedEndTime(): void
    {
        $this->end_time = $this->selectedEndTime;
    }

    // ── Helpers internos ─────────────────────────────────────────────────
    protected function loadTutorDays(): void
    {
        if (!$this->tutor_id) { $this->tutorDays = []; return; }
        $tutorProfile = User::find($this->tutor_id)?->tutor;
        if (!$tutorProfile) { $this->tutorDays = []; return; }
        $this->tutorDays = Schedule::where('tutor_id', $tutorProfile->id)
            ->distinct()->pluck('day_of_week')->toArray();
    }

    protected function loadAvailableSlots(): void
    {
        if (!$this->tutor_id || !$this->date) { $this->availableSlots = []; return; }
        $tutorProfile = User::find($this->tutor_id)?->tutor;
        if (!$tutorProfile) { $this->availableSlots = []; return; }

        $dayOfWeek = \Carbon\Carbon::parse($this->date)->isoWeekday();

        $allSlots = Schedule::where('tutor_id', $tutorProfile->id)
            ->where('day_of_week', $dayOfWeek)
            ->orderBy('time_slot')
            ->pluck('time_slot')
            ->map(function ($slot) {
                [$s, $e] = array_map('trim', explode(' - ', $slot));
                return ['start' => $s, 'end' => $e];
            })->toArray();

        // Citas existentes del tutor ese día (excluye la actual si es edición)
        $bookedQuery = Appointment::where('tutor_id', $this->tutor_id)
            ->whereDate('date', $this->date)
            ->whereNotIn('status', ['Cancelada']);
        if ($this->isEdit && $this->appointment) {
            $bookedQuery->where('id', '!=', $this->appointment->id);
        }
        $bookedRanges = $bookedQuery->get()->map(fn($apt) => [
            'start' => \Carbon\Carbon::parse($apt->start_time)->format('H:i'),
            'end'   => \Carbon\Carbon::parse($apt->end_time)->format('H:i'),
        ])->toArray();

        // Un slot está bloqueado si su inicio cae dentro de cualquier rango reservado
        $this->availableSlots = array_values(array_filter($allSlots, function ($slot) use ($bookedRanges) {
            foreach ($bookedRanges as $booked) {
                if ($slot['start'] >= $booked['start'] && $slot['start'] < $booked['end']) {
                    return false;
                }
            }
            return true;
        }));
    }

    /**
     * Calcula los end-times consecutivos disponibles desde $selectedStartTime.
     * Se detiene si hay un hueco (slot no consecutivo) o si un slot está bloqueado.
     */
    protected function calculateEndTimes(): array
    {
        if (!$this->selectedStartTime || empty($this->availableSlots)) {
            return [];
        }

        $endTimes = [];
        $expectedStart = $this->selectedStartTime;
        $started = false;

        foreach ($this->availableSlots as $slot) {
            if (!$started) {
                if ($slot['start'] === $expectedStart) {
                    $started = true;
                    $endTimes[]    = $slot['end'];
                    $expectedStart = $slot['end'];
                }
            } else {
                // Solo continuar si el slot es consecutivo
                if ($slot['start'] === $expectedStart) {
                    $endTimes[]    = $slot['end'];
                    $expectedStart = $slot['end'];
                } else {
                    break; // Hueco detectado
                }
            }
        }

        return $endTimes;
    }

    public function save()
    {
        $this->validate([
            'student_id' => 'required|exists:users,id',
            'tutor_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'reason' => 'required|string',
            'status' => 'required|in:Programada,Completada,Cancelada',
        ]);

        if ($this->isEdit) {
            $this->appointment->update([
                'student_id' => $this->student_id,
                'tutor_id' => $this->tutor_id,
                'date' => $this->date,
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
                'reason' => $this->reason,
                'status' => $this->status,
            ]);
            $message = 'Cita actualizada exitosamente.';
        } else {
            $appointment = Appointment::create([
                'student_id' => $this->student_id,
                'tutor_id'   => $this->tutor_id,
                'date'       => $this->date,
                'start_time' => $this->start_time,
                'end_time'   => $this->end_time,
                'reason'     => $this->reason,
                'status'     => $this->status,
            ]);

            // Despachar Job de notificaciones (correo + PDF + WhatsApp)
            SendAppointmentCreatedNotifications::dispatch($appointment);

            $message = 'Cita creada exitosamente. Se ha enviado confirmación por correo y WhatsApp.';
        }

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Éxito!',
            'text' => $message,
        ]);

        return redirect()->route('admin.appointments.index');
    }

    public function render()
    {
        $students = User::role('Estudiante')->get();
        $tutors = User::role('Tutor')->with('tutor')->get();

        return view('livewire.admin.appointment-manager', compact('students', 'tutors'));
    }
}
