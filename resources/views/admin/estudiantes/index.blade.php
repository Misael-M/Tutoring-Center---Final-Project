<x-admin-layout title="Estudiantes" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Estudiantes',
    ],
 
]">

	@livewire('admin.datatables.student-table')

	
</x-admin-layout>
