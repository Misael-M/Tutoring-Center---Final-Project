<x-admin-layout title="Editar Tutor" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Tutores', 'href' => route('admin.tutors.index')],
    ['name' => 'Editar']
]">

<div class="max-w-3xl mx-auto py-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-500 px-6 py-5">
            <h2 class="text-white text-lg font-semibold flex items-center gap-2">
                <i class="fa-solid fa-user-doctor"></i>
                Editar Perfil de Tutor
            </h2>
            <p class="text-indigo-100 text-sm mt-0.5">{{ $tutor->user->name }}</p>
        </div>

        <form action="{{ route('admin.tutors.update', $tutor) }}" method="POST" class="px-6 py-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="specialty" class="block text-sm font-medium text-gray-700 mb-1.5">Especialidad</label>
                    <input type="text" id="specialty" name="specialty" value="{{ old('specialty', $tutor->specialty) }}"
                        class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('specialty') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="license_number" class="block text-sm font-medium text-gray-700 mb-1.5">ID Cédula Profesional (Opcional)</label>
                    <input type="text" id="license_number" name="license_number" value="{{ old('license_number', $tutor->license_number) }}"
                        class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('license_number') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.tutors.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancelar
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2 rounded-lg shadow-sm">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>

</x-admin-layout>
