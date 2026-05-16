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
        'name' => 'Nuevo'
    ]
]">
    
    @livewire('admin.appointment-manager')

</x-admin-layout>
