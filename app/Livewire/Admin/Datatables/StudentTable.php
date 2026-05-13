<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder; 

class StudentTable extends DataTableComponent
{
    public function builder(): Builder
    {
        //Devuelve todos los usuarios que tengan el rol Estudiante
        return User::role('Estudiante')->with('student');
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
            Column::make("Nombre", "name")
                ->sortable()
                ->searchable(),
            Column::make("Email", "email")
                ->sortable()
                ->searchable(),
            Column::make("Numero de ID", "id_number")
                ->sortable(),
            Column::make("Telefono", "phone")
                ->sortable(),
            Column::make("Acciones")
                ->label(function($row){
                    // Si por alguna razón no tiene perfil de estudiante creado, lo creamos
                    $student = $row->student;
                    if (!$student) {
                        $student = $row->student()->create([]);
                    }
                    return view('admin.estudiantes.actions',
                    ['student' => $student]);
                })
        ];
    }
}
