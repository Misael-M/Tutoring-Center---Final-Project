<div class="flex items-center gap-x-2">
    <x-wire-button href="{{ route('admin.usuarios.edit', $usuario) }}" blue xs>
        <i class="fa-solid fa-pen-to-square"></i>
    </x-wire-button>

    <form action="{{ route('admin.usuarios.destroy', $usuario) }}" method="POST" class="inline">
        @csrf
        @method('DELETE')
        <x-wire-button type="submit" red xs>
            <i class="fa-solid fa-trash"></i>
        </x-wire-button>
    </form>
</div>
