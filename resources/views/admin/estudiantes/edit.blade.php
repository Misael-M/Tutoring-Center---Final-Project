<x-admin-layout title="Estudiantes" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Estudiantes',
        'href' => route('admin.estudiantes.index'),
    ],
    [
        'name' => 'Editar',
    ],
]">

    @livewire('admin.estudiantes.student-form', ['student' => $student])

</x-admin-layout>