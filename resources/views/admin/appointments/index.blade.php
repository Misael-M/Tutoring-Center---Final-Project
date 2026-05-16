<x-admin-layout :breadcrumb="[
    [
        'name' => 'Dashboard',
        'url' => route('admin.dashboard')
    ],
    [
        'name' => 'Citas'
    ]
]">
    <div class="mb-4 flex justify-between items-center">
        <a href="{{ route('admin.appointments.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            <i class="fa-solid fa-plus mr-2"></i> Nuevo
        </a>

        @if(request('student_id'))
            <a href="{{ route('admin.appointments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none transition">
                <i class="fa-solid fa-xmark mr-2"></i> Limpiar Filtro
            </a>
        @endif
    </div>

    @livewire('admin.datatables.appointment-table', ['studentIdFilter' => request('student_id')])

</x-admin-layout>
