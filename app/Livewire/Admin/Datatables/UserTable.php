<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class UserTable extends DataTableComponent
{
    //protected $model = User::class;

    //Este metodo define el modelo
    public function builder(): Builder
    {
        //Devuelve la relacion con roles
        return User::query()
        ->with('roles');
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
                ->sortable(),
            Column::make("Correo", "email")
                ->sortable(),
            Column::make("Numero de id", "id_number")
                ->sortable(),
            Column::make("Telefono", "phone")
                ->sortable(),
            Column::make("Role", "roles") //Columna para los roles
                ->label(function($row){
                    return $row->roles->first()->name ?? 'Sin rol';
                }),
            
                
            Column::make("Acciones")
                ->label(function($row){
                return view('admin.usuarios.actions', ['usuario' => $row])->render();
            })
            ->html(),
        ];
    }
}
