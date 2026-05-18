<div>
    <form wire:submit.prevent="save">
        
        {{-- Encabezado con foto y acciones --}}
        <x-wire-card class="mb-8">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <img src="{{ $student->user->profile_photo_url }}" alt="{{ $student->user->name }}" class="h-20 w-20 rounded-full object-cover object-center">
                    <div>
                        <p class="text-2xl font-bold text-gray-900 ml-4">{{ $student->user->name }}</p>
                    </div>
                </div>
                <div class="flex gap-3 mt-6 lg:mt-0">
                    <x-wire-button outline gray href="{{ route('admin.estudiantes.index') }}">Volver</x-wire-button>
                    <x-wire-button type="submit" red spinner="save">
                        <i class="fa-solid fa-check mr-2"></i>
                        Guardar cambios 
                    </x-wire-button>
                </div>
            </div>
        </x-wire-card>

        {{-- Tabs de navegación --}}
        <x-wire-card>
            <x-tabs active="datos-personales">
                
                {{-- Menú de pestañas --}}
                <x-slot name="header">
                    <x-tab-link tab="datos-personales">
                        <i class="fa-solid fa-user me-2"></i> Datos personales
                    </x-tab-link>

                    <x-tab-link tab="situacion-escolar">
                        <i class="fa-solid fa-school me-2"></i> Situación escolar
                    </x-tab-link>

                    <x-tab-link tab="informacion-general">
                        <i class="fa-solid fa-circle-info me-2"></i> Información general
                    </x-tab-link>

                    <x-tab-link tab="adulto-cargo">
                        <i class="fa-solid fa-user-shield me-2"></i> Adulto a cargo
                    </x-tab-link>
                </x-slot>

                {{-- Contenido de los Tabs --}}
                
                {{-- Tab 1: Datos personales --}}
                <x-tab-content tab="datos-personales">
                    <div class="p-4 mb-6 rounded-r-lg shadow-sm" style="background: #fff8f8; border-left: 4px solid #ff6b6b;">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <i class="fa-solid fa-user-gear text-xl mt-1" style="color: #ff6b6b;"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-bold" style="color: #b33b3b;">Edición de cuenta de usuario</h3>
                                    <div class="mt-1 text-sm text-gray-600">
                                        <p>La <strong>información de acceso</strong> (nombre, email y contraseña) debe de gestionarse desde la cuenta de usuario asociada.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <x-wire-button red sm href="{{ route('admin.usuarios.edit', $student->user) }}" target="_blank">
                                    Editar usuario <i class="fa-solid fa-arrow-up-right-from-square ms-2"></i>
                                </x-wire-button>
                            </div>
                        </div>
                    </div>
                    <div class="grid lg:grid-cols-2 gap-4">
                        <div>
                            <span class="text-gray-500 font-semibold">Teléfono: </span>
                            <span class="text-gray-900 text-sm ml-1">{{ $student->user->phone ?? 'No registrado' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 font-semibold">Email: </span>
                            <span class="text-gray-900 text-sm ml-1">{{ $student->user->email }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 font-semibold">Dirección: </span>
                            <span class="text-gray-900 text-sm ml-1">{{ $student->user->address ?? 'No registrada' }}</span>
                        </div>
                    </div>
                </x-tab-content>

                {{-- Tab 2: Situación Escolar --}}
                <x-tab-content tab="situacion-escolar">
                    <div class="grid lg:grid-cols-2 gap-6">
                        <div class="lg:col-span-2">
                            <x-wire-native-select label="Grado Escolar" wire:model="scholargrade_id">
                                <option value="">Selecciona un grado escolar</option>
                                @foreach ($scholarGrades as $grade)
                                    <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                                @endforeach
                            </x-wire-native-select>
                        </div>
                        <x-wire-input label="Escuela de procedencia" wire:model="school_name" placeholder="Nombre de la escuela..." />
                        <x-wire-input label="Dirección de la escuela" wire:model="school_address" placeholder="Ubicación de la escuela..." />
                    </div>
                </x-tab-content>

                {{-- Tab 3: Información general --}}
                <x-tab-content tab="informacion-general">
                    <div class="space-y-6">
                        <x-wire-textarea label="Temas con los que necesita ayuda" wire:model="topics_needed" placeholder="Ej. Matemáticas (álgebra), Inglés..." rows="4"></x-wire-textarea>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Imágenes de referencia (tareas, temarios, etc.)</h4>
                            
                            {{-- Vista previa de imágenes existentes --}}
                            @if(count($existingImages) > 0)
                            <div class="flex flex-wrap gap-4 mb-4">
                                @foreach($existingImages as $image)
                                    <div class="relative group">
                                        <img src="{{ Storage::url($image->image_path) }}" class="h-32 w-32 object-cover rounded-lg border shadow-sm">
                                        <button type="button" wire:click="removeImage({{ $image->id }})" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow-lg opacity-0 group-hover:opacity-100 transition-opacity">
                                            <i class="fa-solid fa-times w-4 h-4 text-center"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                            @endif

                            {{-- Subir nuevas imágenes --}}
                            <div class="mt-2">
                                <input type="file" wire:model="newImages" multiple class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 transition" />
                                <div wire:loading wire:target="newImages" class="text-sm text-red-500 mt-2">Subiendo imágenes...</div>
                            </div>
                            
                            {{-- Vista previa de nuevas imágenes --}}
                            @if ($newImages)
                                <div class="flex flex-wrap gap-4 mt-4">
                                    @foreach ($newImages as $image)
                                        <img src="{{ $image->temporaryUrl() }}" class="h-32 w-32 object-cover rounded-lg border border-blue-300 shadow-sm opacity-80">
                                    @endforeach
                                </div>
                            @endif
                            @error('newImages.*') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </x-tab-content>

                {{-- Tab 4: Adulto a cargo --}}
                <x-tab-content tab="adulto-cargo">
                    <div class="space-y-6">
                        @foreach($contacts as $index => $contact)
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <div class="flex justify-between items-center mb-4">
                                    <h4 class="font-semibold text-gray-700">Contacto #{{ $index + 1 }}</h4>
                                    @if(count($contacts) > 1)
                                        <button type="button" wire:click="removeContact({{ $index }})" class="text-red-500 hover:text-red-700 transition">
                                            <i class="fa-solid fa-trash"></i> Eliminar
                                        </button>
                                    @endif
                                </div>
                                <div class="grid lg:grid-cols-2 gap-4">
                                    <x-wire-input label="Nombre del contacto" wire:model="contacts.{{ $index }}.name" placeholder="Ej. María Pérez" required />
                                    <x-wire-input label="Teléfono" wire:model="contacts.{{ $index }}.phone" placeholder="(999) 999 9999" required />
                                    <x-wire-input label="Relación con el estudiante" wire:model="contacts.{{ $index }}.relationship" placeholder="Ej. Madre, Padre, Tutor..." class="lg:col-span-2" required />
                                </div>
                            </div>
                        @endforeach

                        <x-wire-button type="button" wire:click="addContact" outline red>
                            <i class="fa-solid fa-plus mr-2"></i> Añadir otro contacto
                        </x-wire-button>
                    </div>
                </x-tab-content>

            </x-tabs>
        </x-wire-card>
    </form>
</div>
