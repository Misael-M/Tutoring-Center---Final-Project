<div class="flex items-center gap-x-2">

    {{-- Botón editar --}}
    <x-wire-button href="{{ route('admin.tutors.edit', $tutor) }}" blue xs>
        <i class="fa-solid fa-pen-to-square"></i>
    </x-wire-button>

    {{-- Botón gestionar horario --}}
    <x-wire-button href="{{ route('admin.tutors.schedule', $tutor) }}" green xs>
        <i class="fa-solid fa-clock"></i>
    </x-wire-button>

</div>
