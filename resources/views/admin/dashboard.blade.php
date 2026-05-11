@php
    $Breadcrumbs = [
        [
            'name' => 'Dashboard',
            'href' => route('admin.dashboard'),
        ],
        [
            'name' => 'Ejemplo',
        ],
    ];
@endphp

<x-admin-layout title="Dashboard" :breadcrumbs="$Breadcrumbs">
    Hola desde el panel de control administrativo.
</x-admin-layout>

