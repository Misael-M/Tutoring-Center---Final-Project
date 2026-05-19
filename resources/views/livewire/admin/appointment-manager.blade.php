<div>
    <div class="bg-white shadow sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                {{ $isEdit ? 'Modifique los datos de la cita seleccionada.' : 'Complete el formulario para agendar una cita.' }}
            </h3>

            <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                
                <div class="sm:col-span-6">
                    <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">PARTICIPANTES</h4>
                </div>

                <div class="sm:col-span-3">
                    <label for="student_id" class="block text-sm font-medium text-gray-700">Estudiante *</label>
                    <div class="mt-1">
                        <select id="student_id" wire:model="student_id" class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <option value="">-- Seleccionar estudiante --</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})</option>
                            @endforeach
                        </select>
                        @error('student_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label for="tutor_id" class="block text-sm font-medium text-gray-700">Tutor *</label>
                    <div class="mt-1">
                        <select id="tutor_id" wire:model.live="tutor_id" class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <option value="">-- Seleccionar tutor --</option>
                            @foreach($tutors as $tutor)
                                <option value="{{ $tutor->id }}">{{ $tutor->name }} ({{ $tutor->tutor->specialty ?? 'Sin especialidad' }})</option>
                            @endforeach
                        </select>
                        @error('tutor_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="sm:col-span-6 mt-4">
                    <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">FECHA Y HORARIO</h4>
                </div>

                <div class="sm:col-span-6">
                    <label for="date" class="block text-sm font-medium text-gray-700">Fecha *</label>
                    <div class="mt-1">
                        <input type="date" id="date" wire:model.live="date"
                            class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md"
                            @if($tutor_id && count($tutorDays) > 0)
                                {{-- Opcional: hint visual de qué días tiene el tutor --}}
                            @endif
                        >
                        @error('date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

                        {{-- Indicador de días disponibles del tutor --}}
                        @if($tutor_id && count($tutorDays) > 0)
                            @php
                                $dayNames = [1=>'Lunes',2=>'Martes',3=>'Miércoles',4=>'Jueves',5=>'Viernes',6=>'Sábado',7=>'Domingo'];
                            @endphp
                            <p class="mt-1 text-xs text-red-600">
                                <i class="fa-solid fa-circle-info mr-1"></i>
                                Este tutor atiende los:
                                <strong>{{ implode(', ', array_map(fn($d) => $dayNames[$d] ?? $d, $tutorDays)) }}</strong>
                            </p>
                        @elseif($tutor_id)
                            <p class="mt-1 text-xs text-amber-600">
                                <i class="fa-solid fa-triangle-exclamation mr-1"></i>
                                Este tutor no tiene horarios registrados.
                            </p>
                        @endif
                    </div>
                </div>


                {{-- ── HORA DE INICIO ── --}}
                <div class="sm:col-span-3">
                    <label for="selected_start_time" class="block text-sm font-medium text-gray-700">Hora de Inicio *</label>
                    <div class="mt-1">
                        @if(!$tutor_id)
                            <div class="flex items-center gap-2 text-sm text-gray-400 bg-gray-50 border border-gray-200 rounded-md px-4 py-3">
                                <i class="fa-solid fa-user-clock"></i>
                                <span>Selecciona un tutor primero.</span>
                            </div>
                        @elseif(!$date)
                            <div class="flex items-center gap-2 text-sm text-gray-400 bg-gray-50 border border-gray-200 rounded-md px-4 py-3">
                                <i class="fa-regular fa-calendar"></i>
                                <span>Selecciona una fecha primero.</span>
                            </div>
                        @elseif(count($availableSlots) === 0)
                            <div class="flex items-center gap-2 text-sm text-amber-700 bg-amber-50 border border-amber-200 rounded-md px-4 py-3">
                                <i class="fa-solid fa-calendar-xmark"></i>
                                <span>Sin disponibilidad en esta fecha.</span>
                            </div>
                        @else
                            <select id="selected_start_time" wire:model.live="selectedStartTime"
                                class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                <option value="">-- Hora de inicio --</option>
                                @foreach($availableSlots as $slot)
                                    <option value="{{ $slot['start'] }}">{{ $slot['start'] }}</option>
                                @endforeach
                            </select>
                        @endif
                        @error('start_time') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- ── HORA DE FIN ── --}}
                <div class="sm:col-span-3">
                    <label for="selected_end_time" class="block text-sm font-medium text-gray-700">Hora de Fin *</label>
                    <div class="mt-1">
                        @if(!$selectedStartTime)
                            <div class="flex items-center gap-2 text-sm text-gray-400 bg-gray-50 border border-gray-200 rounded-md px-4 py-3">
                                <i class="fa-solid fa-clock"></i>
                                <span>Selecciona hora de inicio primero.</span>
                            </div>
                        @elseif(count($availableEndTimes) === 0)
                            <div class="flex items-center gap-2 text-sm text-amber-700 bg-amber-50 border border-amber-200 rounded-md px-4 py-3">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                <span>No hay duración disponible desde este horario.</span>
                            </div>
                        @else
                            <select id="selected_end_time" wire:model.live="selectedEndTime"
                                class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                <option value="">-- Hora de fin --</option>
                                @foreach($availableEndTimes as $endTime)
                                    <option value="{{ $endTime }}">{{ $endTime }}</option>
                                @endforeach
                            </select>
                        @endif
                        @error('end_time') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Confirmación visual del rango --}}
                @if($start_time && $end_time)
                    <div class="sm:col-span-6">
                        <p class="text-xs text-green-700 flex items-center gap-1 bg-green-50 border border-green-200 rounded-md px-4 py-2">
                            <i class="fa-solid fa-circle-check"></i>
                            Horario confirmado: <strong class="ml-1">{{ $start_time }} – {{ $end_time }}</strong>
                            @php
                                $mins = \Carbon\Carbon::parse($start_time)->diffInMinutes(\Carbon\Carbon::parse($end_time));
                            @endphp
                            <span class="ml-2 text-green-600">({{ $mins }} min)</span>
                        </p>
                    </div>
                @endif

                {{-- Campos ocultos para mantener la validación --}}
                <input type="hidden" wire:model="start_time">
                <input type="hidden" wire:model="end_time">


                <div class="sm:col-span-6 mt-4">
                    <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">DETALLES Y ESTADO</h4>
                </div>

                <div class="sm:col-span-6">
                    <label for="reason" class="block text-sm font-medium text-gray-700">Motivo de la tutoría *</label>
                    <div class="mt-1">
                        <textarea id="reason" wire:model="reason" rows="3" class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border border-gray-300 rounded-md" placeholder="Describa el motivo de la tutoría..."></textarea>
                        @error('reason') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                @if($isEdit)
                <div class="sm:col-span-6">
                    <label for="status" class="block text-sm font-medium text-gray-700">Estado</label>
                    <div class="mt-1">
                        <select id="status" wire:model="status" class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <option value="Programada">Programada</option>
                            <option value="Completada">Completada</option>
                            <option value="Cancelada">Cancelada</option>
                        </select>
                        @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
                @endif
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <x-wire-button outline gray href="{{ route('admin.appointments.index') }}">
                    <i class="fa-solid fa-xmark mr-1"></i> Cancelar
                </x-wire-button>
                <x-wire-button red wire:click="save" spinner="save">
                    <i class="fa-solid {{ $isEdit ? 'fa-save' : 'fa-calendar-check' }} mr-1"></i> {{ $isEdit ? 'Guardar Cambios' : 'Confirmar Cita' }}
                </x-wire-button>
            </div>
        </div>
    </div>
</div>
