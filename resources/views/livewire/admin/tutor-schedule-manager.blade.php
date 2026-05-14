<div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- Header --}}
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-800">Gestor de horarios</h2>
            <button wire:click="saveSchedule"
                class="bg-indigo-500 hover:bg-indigo-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors shadow-sm">
                Guardar horario
            </button>
        </div>

        {{-- Tabla de Horarios --}}
        <div class="overflow-x-auto p-6">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider w-32 border-b-2 border-gray-100">
                            DÍA/HORA
                        </th>
                        @foreach($days as $dayId => $dayName)
                            <th class="py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center border-b-2 border-gray-100">
                                {{ $dayName }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($hours as $hour)
                        <tr>
                            {{-- Celda de la hora (Ej: 08:00:00) --}}
                            <td class="py-4 px-4 align-top">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-medium text-gray-700">{{ substr($hour, 0, 5) }}</span>
                                </div>
                            </td>

                            {{-- Celdas por día --}}
                            @foreach($days as $dayId => $dayName)
                                <td class="py-4 px-4 align-top border-l border-gray-50">
                                    <div class="flex flex-col space-y-2">
                                        {{-- Botón Todos (Seleccionar hora completa) --}}
                                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer font-medium mb-1 hover:text-indigo-600 transition-colors">
                                            <input type="checkbox"
                                                wire:click="toggleFullHour({{ $dayId }}, '{{ $hour }}')"
                                                class="rounded border-gray-300 text-indigo-500 focus:ring-indigo-500 cursor-pointer"
                                                {{-- Lógica visual para ver si todos están activos (muy básica para el ejemplo) --}}
                                            >
                                            Todos
                                        </label>

                                        {{-- Sub slots de 15 mins --}}
                                        @foreach($this->getSubSlots($hour) as $slot)
                                            <label class="flex items-center gap-2 text-sm text-gray-500 cursor-pointer hover:text-gray-900 transition-colors">
                                                <input type="checkbox"
                                                    wire:model="scheduleMatrix.{{ $dayId }}.{{ $slot }}"
                                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                                                {{ $slot }}
                                            </label>
                                        @endforeach
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
