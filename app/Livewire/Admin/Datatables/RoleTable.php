<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Spatie\Permission\Models\Role;

class RoleTable extends DataTableComponent
{
    protected $model = Role::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setSearchPlaceholder('Buscar');
        $this->setToolBarAttributes(['class' => 'flex items-center gap-x-3']);
        $this->setToolsAttributes(['class' => 'flex items-center gap-x-2']);
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Name", "name")
                ->sortable(),
            Column::make("Fecha", "created_at")
                ->sortable()
                ->format(function($value){
                    return $value->format('d/m/Y');
                }),
            Column::make("Acciones")
                ->label(function($row){
                return view('admin.roles.actions', ['role' => $row]);
            })
            ->html(),
        ];
    }
}
