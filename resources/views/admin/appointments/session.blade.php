<x-admin-layout :breadcrumb="[
    [
        'name' => 'Dashboard',
        'url' => route('admin.dashboard')
    ],
    [
        'name' => 'Citas',
        'url' => route('admin.appointments.index')
    ],
    [
        'name' => 'Consulta'
    ]
]">
    
    @livewire('admin.tutoring-session-manager', ['appointment' => $appointment])

</x-admin-layout>
