<x-admin-layout title="Tutores" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Tutores']
]">

    @livewire('admin.datatables.tutor-table')

</x-admin-layout>
