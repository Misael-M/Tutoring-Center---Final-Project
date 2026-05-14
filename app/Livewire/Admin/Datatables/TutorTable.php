<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class TutorTable extends DataTableComponent
{
    public function builder(): Builder
    {
        return User::role('Tutor')->with('tutor');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->sortable(),

            Column::make('Nombre', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Email', 'email')
                ->sortable()
                ->searchable(),

            Column::make('DNI', 'id_number')
                ->sortable()
                ->searchable(),

            Column::make('Teléfono', 'phone')
                ->sortable(),

            Column::make('Especialidad', 'tutor.specialty')
                ->sortable()
                ->searchable(),

            Column::make('Acciones')
                ->label(function ($row) {
                    $tutor = $row->tutor;
                    if (!$tutor) {
                        $tutor = $row->tutor()->create([]);
                    }
                    return view('admin.tutors.actions', ['tutor' => $tutor]);
                }),
        ];
    }
}
