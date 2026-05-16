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
        'name' => 'Edición'
    ]
]">
    
    @livewire('admin.appointment-manager', ['appointment' => $appointment])

</x-admin-layout>
