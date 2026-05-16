<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Appointment;
use Illuminate\Database\Eloquent\Builder;

class AppointmentTable extends DataTableComponent
{
    public $studentIdFilter = null;

    public function builder(): Builder
    {
        $query = Appointment::query()
            ->with(['student', 'tutor']);

        if ($this->studentIdFilter) {
            $query->where('student_id', $this->studentIdFilter);
        }

        return $query;
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Estudiante", "student.name")
                ->sortable()
                ->searchable(),
            Column::make("Tutor", "tutor.name")
                ->sortable()
                ->searchable(),
            Column::make("Fecha", "date")
                ->sortable(),
            Column::make("Hora Inicio", "start_time")
                ->sortable(),
            Column::make("Hora Fin", "end_time")
                ->sortable(),
            Column::make("Estado", "status")
                ->sortable(),
            Column::make("Acciones")
                ->label(function($row){
                return view('admin.appointments.actions', ['appointment' => $row])->render();
            })
            ->html(),
        ];
    }
}
