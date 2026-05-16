<div>
    <div class="bg-white shadow sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="text-xl leading-6 font-semibold text-gray-900 flex items-center">
                    {{ $appointment->student->name }}
                    <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        DNI: {{ $appointment->student->id_number ?? 'N/A' }}
                    </span>
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    <i class="fa-regular fa-calendar mr-1"></i> Cita del {{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }} &middot; {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }} &middot; Tutor: {{ $appointment->tutor->name }}
                </p>
            </div>
            
            <div class="flex space-x-3">
                <a href="{{ $appointment->student->student ? route('admin.estudiantes.show', $appointment->student->student->id) : '#' }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fa-solid fa-file-lines mr-2 text-indigo-600"></i> Ver datos del estudiante
                </a>
                
                <button wire:click="openPreviousAppointments" type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fa-solid fa-clock-rotate-left mr-2 text-indigo-600"></i> Citas anteriores
                </button>
            </div>
        </div>

        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                <button wire:click="setTab('session')" class="{{ $tab === 'session' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center">
                    <i class="fa-solid fa-chalkboard-user mr-2"></i> Sesión
                </button>

                <button wire:click="setTab('materials')" class="{{ $tab === 'materials' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center">
                    <i class="fa-solid fa-file-image mr-2"></i> Material de Repaso
                </button>
            </nav>
        </div>

        <div class="px-4 py-5 sm:p-6">
            @if($tab === 'session')
                <div class="space-y-6">
                    <div>
                        <label for="student_performance" class="block text-sm font-medium text-gray-700">Desempeño del estudiante *</label>
                        <div class="mt-1">
                            <textarea id="student_performance" wire:model="student_performance" rows="4" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md" placeholder="Describa el desempeño del estudiante aquí..."></textarea>
                            @error('student_performance') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="topics_to_improve" class="block text-sm font-medium text-gray-700">Temas a mejorar *</label>
                        <div class="mt-1">
                            <textarea id="topics_to_improve" wire:model="topics_to_improve" rows="4" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md" placeholder="Describa los temas a mejorar aquí..."></textarea>
                            @error('topics_to_improve') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">Notas</label>
                        <div class="mt-1">
                            <textarea id="notes" wire:model="notes" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md" placeholder="Agregue notas adicionales sobre la sesión..."></textarea>
                            @error('notes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            @endif

            @if($tab === 'materials')
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Añadir nuevo material (Imágenes)</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md relative">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                        <span>Subir archivos</span>
                                        <input id="file-upload" wire:model="newMaterials" type="file" class="sr-only" multiple accept="image/*">
                                    </label>
                                    <p class="pl-1">o arrastrar y soltar</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, WEBP hasta 5MB</p>
                            </div>
                            <div wire:loading wire:target="newMaterials" class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center">
                                <span class="text-indigo-600 font-medium">Subiendo...</span>
                            </div>
                        </div>
                        @error('newMaterials.*') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    @if($newMaterials)
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Archivos listos para subir:</h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @foreach($newMaterials as $index => $material)
                                    <div class="relative group rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                                        <img src="{{ $material->temporaryUrl() }}" class="object-cover w-full h-32" alt="Preview">
                                        <button wire:click="removeNewMaterial({{ $index }})" type="button" class="absolute top-1 right-1 bg-red-600 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition">
                                            <i class="fa-solid fa-xmark text-xs"></i>
                                        </button>
                                        <div class="bg-gray-50 px-2 py-1 text-xs text-gray-500 truncate">
                                            {{ $material->getClientOriginalName() }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4 flex justify-end">
                                <button wire:click="uploadMaterials" type="button" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <i class="fa-solid fa-upload mr-2"></i> Subir Materiales
                                </button>
                            </div>
                        </div>
                    @endif

                    @if($existingMaterials && count($existingMaterials) > 0)
                        <div class="mt-8 border-t border-gray-200 pt-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Materiales Guardados</h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                                @foreach($existingMaterials as $material)
                                    <div class="relative group rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                                        <a href="{{ Storage::disk('public')->url($material->file_path) }}" target="_blank">
                                            <img src="{{ Storage::disk('public')->url($material->file_path) }}" class="object-cover w-full h-40" alt="Material">
                                        </a>
                                        <button wire:click="deleteMaterial({{ $material->id }})" type="button" onclick="return confirm('¿Está seguro de eliminar este material?') || event.stopImmediatePropagation()" class="absolute top-2 right-2 bg-red-600 text-white rounded-full w-8 h-8 flex items-center justify-center opacity-0 group-hover:opacity-100 transition shadow">
                                            <i class="fa-solid fa-trash text-sm"></i>
                                        </button>
                                        <div class="bg-gray-50 px-3 py-2 text-sm text-gray-700 truncate" title="{{ $material->original_name }}">
                                            <i class="fa-solid fa-image text-gray-400 mr-1"></i> {{ $material->original_name ?? 'Imagen' }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @elseif(empty($newMaterials))
                        <div class="mt-8 text-center text-gray-500 py-10 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                            <i class="fa-regular fa-images text-4xl mb-3 text-gray-300"></i>
                            <p>No hay material de repaso adjunto a esta sesión.</p>
                        </div>
                    @endif
                </div>
            @endif
            
            <!-- Botón de Guardar Sesión global para ambas pestañas -->
            <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end">
                <button wire:click="saveSession" type="button" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fa-solid fa-save mr-2"></i> Guardar Sesión
                </button>
            </div>
        </div>
    </div>

    <!-- Modal de Citas Anteriores -->
    @if($showPreviousAppointmentsModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closePreviousAppointments"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex justify-between items-center mb-5 border-b pb-3">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Citas Anteriores
                            </h3>
                            <button wire:click="closePreviousAppointments" type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                <i class="fa-solid fa-xmark text-xl"></i>
                            </button>
                        </div>
                        
                        <div class="space-y-4 max-h-[60vh] overflow-y-auto pr-2">
                            @forelse($previousAppointments as $prevAppointment)
                                <div class="border border-indigo-100 rounded-lg p-4 bg-white shadow-sm hover:shadow transition">
                                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-3">
                                        <div class="mb-2 sm:mb-0">
                                            <h4 class="font-bold text-gray-900 flex items-center">
                                                <i class="fa-regular fa-calendar text-indigo-600 mr-2"></i> 
                                                {{ \Carbon\Carbon::parse($prevAppointment->date)->format('d/m/Y') }} a las {{ \Carbon\Carbon::parse($prevAppointment->start_time)->format('H:i') }}
                                            </h4>
                                            <p class="text-sm text-gray-500 mt-1 ml-6">
                                                Atendido por: Tutor(a) {{ $prevAppointment->tutor->name }}
                                            </p>
                                        </div>
                                        <a href="{{ route('admin.appointments.detail', $prevAppointment) }}" class="inline-flex items-center px-3 py-1.5 border border-indigo-200 text-sm font-medium rounded-md text-indigo-700 bg-indigo-50 hover:bg-indigo-100 transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            Consultar Detalle
                                        </a>
                                    </div>
                                    
                                    @if($prevAppointment->session)
                                        <div class="mt-3 bg-gray-50 rounded p-3 text-sm text-gray-700">
                                            <p class="mb-1"><span class="font-semibold text-gray-900">Desempeño:</span> {{ Str::limit($prevAppointment->session->student_performance, 100) ?: 'No especificado' }}</p>
                                            <p class="mb-1"><span class="font-semibold text-gray-900">Temas a mejorar:</span> {{ Str::limit($prevAppointment->session->topics_to_improve, 100) ?: 'No especificado' }}</p>
                                            <p><span class="font-semibold text-gray-900">Notas:</span> {{ Str::limit($prevAppointment->session->notes, 100) ?: 'Ninguna' }}</p>
                                        </div>
                                    @else
                                        <div class="mt-3 bg-gray-50 rounded p-3 text-sm text-gray-500 italic">
                                            La sesión no fue registrada.
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-500">
                                    <i class="fa-solid fa-clock-rotate-left text-3xl mb-3 text-gray-300"></i>
                                    <p>No se encontraron citas anteriores para este estudiante.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
