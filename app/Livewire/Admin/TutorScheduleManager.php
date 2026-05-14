<?php

namespace App\Livewire\Admin;

use App\Models\Tutor;
use App\Models\Schedule;
use Livewire\Component;

class TutorScheduleManager extends Component
{
    public Tutor $tutor;

    // Estructura de la matriz de horarios
    // [day_of_week => [time_slot => boolean]]
    public array $scheduleMatrix = [];

    // Definición de días y bloques para la interfaz
    public $days = [
        1 => 'LUNES',
        2 => 'MARTES',
        3 => 'MIÉRCOLES',
        4 => 'JUEVES',
        5 => 'VIERNES',
        6 => 'SÁBADO',
        7 => 'DOMINGO'
    ];

    public $hours = [
        '08:00:00', '09:00:00', '10:00:00', '11:00:00',
        '12:00:00', '13:00:00', '14:00:00', '15:00:00',
        '16:00:00', '17:00:00', '18:00:00'
    ];

    public function mount(Tutor $tutor)
    {
        $this->tutor = $tutor;
        $this->loadSchedule();
    }

    /**
     * Genera los slots de 15 minutos basados en una hora (ej: '08:00:00')
     */
    public function getSubSlots(string $hour): array
    {
        $base = substr($hour, 0, 5); // '08:00'
        $h = substr($hour, 0, 2);
        return [
            "{$h}:00 - {$h}:15",
            "{$h}:15 - {$h}:30",
            "{$h}:30 - {$h}:45",
            "{$h}:45 - " . sprintf('%02d', (int)$h + 1) . ":00",
        ];
    }

    /**
     * Carga el horario desde la base de datos a la matriz del componente.
     */
    public function loadSchedule()
    {
        // Inicializar matriz en false
        foreach ($this->days as $dayId => $dayName) {
            foreach ($this->hours as $hour) {
                foreach ($this->getSubSlots($hour) as $slot) {
                    $this->scheduleMatrix[$dayId][$slot] = false;
                }
            }
        }

        // Marcar los slots existentes
        $schedules = Schedule::where('tutor_id', $this->tutor->id)->get();
        foreach ($schedules as $s) {
            if (isset($this->scheduleMatrix[$s->day_of_week][$s->time_slot])) {
                $this->scheduleMatrix[$s->day_of_week][$s->time_slot] = true;
            }
        }
    }

    /**
     * Selecciona o deselecciona una hora completa (todos sus 4 sub-slots) para un día.
     */
    public function toggleFullHour($dayId, $hour)
    {
        $slots = $this->getSubSlots($hour);

        // Si todos están activos, los desactivamos. Si no, los activamos todos.
        $allActive = true;
        foreach ($slots as $slot) {
            if (!$this->scheduleMatrix[$dayId][$slot]) {
                $allActive = false;
                break;
            }
        }

        $newState = !$allActive;
        foreach ($slots as $slot) {
            $this->scheduleMatrix[$dayId][$slot] = $newState;
        }
    }

    /**
     * Guarda la matriz en la base de datos.
     */
    public function saveSchedule()
    {
        // Limpiar horario actual
        Schedule::where('tutor_id', $this->tutor->id)->delete();

        $inserts = [];
        foreach ($this->scheduleMatrix as $dayId => $slots) {
            foreach ($slots as $slot => $isActive) {
                if ($isActive) {
                    $inserts[] = [
                        'tutor_id'   => $this->tutor->id,
                        'day_of_week' => $dayId,
                        'time_slot'   => $slot,
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ];
                }
            }
        }

        if (!empty($inserts)) {
            Schedule::insert($inserts);
        }

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Horario guardado',
            'text'  => 'El horario del tutor se ha actualizado correctamente.',
        ]);

        return redirect()->route('admin.tutors.index');
    }

    public function render()
    {
        return view('livewire.admin.tutor-schedule-manager');
    }
}
