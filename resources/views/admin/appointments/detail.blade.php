<x-admin-layout :breadcrumb="[
    ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['name' => 'Citas', 'url' => route('admin.appointments.index')],
    ['name' => 'Detalle']
]">

<div class="space-y-6">

    {{-- ── TARJETA SUPERIOR: Paciente + Doctor ─────────────────────────── --}}
    <div class="bg-white shadow sm:rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-700">Información de la cita y sesión</h2>
        </div>
        <div class="px-6 py-5 grid grid-cols-1 sm:grid-cols-2 gap-6">
            {{-- Estudiante --}}
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Estudiante</p>
                <p class="text-xl font-bold text-gray-900">{{ $appointment->student->name }}</p>
                <p class="text-sm text-gray-500">DNI: {{ $appointment->student->id_number ?? 'N/A' }}</p>
                <p class="text-sm text-gray-500">
                    <i class="fa-regular fa-calendar mr-1 text-indigo-400"></i>
                    {{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}
                    &middot; {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}
                    – {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}
                </p>
            </div>
            {{-- Tutor --}}
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Atendido por</p>
                <p class="text-xl font-bold text-gray-900">{{ $appointment->tutor->name }}</p>
                @if($appointment->tutor->tutor)
                    <p class="text-sm text-gray-500">Especialidad: {{ $appointment->tutor->tutor->specialty ?? '—' }}</p>
                @endif
                <span class="mt-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                    {{ $appointment->status === 'Completada' ? 'bg-green-100 text-green-800' : ($appointment->status === 'Cancelada' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                    {{ $appointment->status }}
                </span>
            </div>
        </div>
    </div>

    {{-- ── TARJETA SESIÓN ───────────────────────────────────────────────── --}}
    <div class="bg-white shadow sm:rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-700">
                <i class="fa-solid fa-chalkboard-user mr-2 text-indigo-500"></i>Detalles de la Sesión
            </h2>
        </div>

        @if($appointment->session)
            <div class="px-6 py-5 divide-y divide-gray-100 space-y-0">
                <div class="py-4">
                    <p class="text-sm font-semibold text-indigo-600 mb-1">Motivo de la tutoría</p>
                    <p class="text-sm text-gray-800">{{ $appointment->reason ?: '—' }}</p>
                </div>
                <div class="py-4">
                    <p class="text-sm font-semibold text-indigo-600 mb-1">Desempeño del estudiante</p>
                    <p class="text-sm text-gray-800">{{ $appointment->session->student_performance ?: '—' }}</p>
                </div>
                <div class="py-4">
                    <p class="text-sm font-semibold text-indigo-600 mb-1">Temas a mejorar</p>
                    <p class="text-sm text-gray-800">{{ $appointment->session->topics_to_improve ?: '—' }}</p>
                </div>
                <div class="py-4">
                    <p class="text-sm font-semibold text-indigo-600 mb-1">Notas Adicionales</p>
                    <p class="text-sm text-gray-800">{{ $appointment->session->notes ?: 'Ninguna' }}</p>
                </div>
            </div>
        @else
            <div class="px-6 py-8 text-center text-gray-400 italic">
                <i class="fa-solid fa-file-circle-xmark text-3xl mb-2"></i>
                <p>Esta sesión aún no ha sido registrada.</p>
            </div>
        @endif
    </div>

    {{-- ── TARJETA MATERIAL DE REPASO ───────────────────────────────────── --}}
    <div class="bg-white shadow sm:rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center">
            <i class="fa-solid fa-images mr-2 text-indigo-500"></i>
            <h2 class="text-base font-semibold text-gray-700">Material de Repaso</h2>
        </div>

        @if($appointment->session && $appointment->session->materials->count() > 0)
            <div class="px-6 py-5">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    @foreach($appointment->session->materials as $material)
                        <div class="group relative rounded-lg overflow-hidden border border-gray-200 shadow-sm hover:shadow-md transition">
                            <img src="{{ Storage::disk('public')->url($material->file_path) }}"
                                 class="object-cover w-full h-40"
                                 alt="{{ $material->original_name }}"
                                 onerror="this.style.display='none'">
                            {{-- Botón de abrir en pantalla completa --}}
                            <a href="{{ Storage::disk('public')->url($material->file_path) }}" target="_blank"
                               class="absolute top-2 right-2 bg-white bg-opacity-80 rounded-full p-1.5 opacity-0 group-hover:opacity-100 transition shadow" title="Ver imagen completa">
                                <i class="fa-solid fa-up-right-and-down-left-from-center text-gray-700 text-xs"></i>
                            </a>
                            <div class="bg-gray-50 px-3 py-2 text-xs text-gray-600 truncate flex items-center">
                                <i class="fa-solid fa-image text-gray-400 mr-1"></i>
                                {{ $material->original_name ?? 'Imagen' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="px-6 py-8 text-center text-gray-400">
                <i class="fa-regular fa-images text-3xl mb-2"></i>
                <p class="italic">No se adjuntó material de repaso en esta sesión.</p>
            </div>
        @endif
    </div>

    {{-- ── PIE: Botón volver ────────────────────────────────────────────── --}}
    <div class="flex justify-end pb-6">
        <a href="javascript:history.back()"
           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <i class="fa-solid fa-arrow-left mr-2"></i> Volver a Citas
        </a>
    </div>

</div>

</x-admin-layout>
