<div class="flex items-center space-x-2">
    <a href="{{ route('admin.appointments.edit', $appointment) }}" class="inline-flex items-center p-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150" title="Editar Cita">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>
    
    <a href="{{ route('admin.appointments.session', $appointment) }}" class="inline-flex items-center p-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150" title="Atender Sesión">
        <i class="fa-solid fa-chalkboard-user"></i>
    </a>

    <form action="{{ route('admin.appointments.destroy', $appointment) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta cita?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="inline-flex items-center p-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150" title="Eliminar Cita">
            <i class="fa-solid fa-trash"></i>
        </button>
    </form>
</div>
