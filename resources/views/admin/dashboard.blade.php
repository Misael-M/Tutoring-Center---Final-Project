@php
    $Breadcrumbs = [
        [
            'name' => 'Dashboard',
            'href' => route('admin.dashboard'),
        ],
        [
            'name' => 'Panel de Bienvenida',
        ],
    ];
@endphp

<x-admin-layout title="Dashboard" :breadcrumbs="$Breadcrumbs">
    ¡Hola! Bienvenidos al centro de tutorías Red Apple donde tu aprendizaje es nuestra prioridad
</x-admin-layout>

