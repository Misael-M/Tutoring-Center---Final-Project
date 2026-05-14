<x-admin-layout title="Horarios" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Tutores', 'href' => route('admin.tutors.index')],
    ['name' => 'Horarios']
]">

<div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    @livewire('admin.tutor-schedule-manager', ['tutor' => $tutor])
</div>

</x-admin-layout>
