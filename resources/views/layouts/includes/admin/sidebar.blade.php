@php
    $links = [
        [
            'name'   => 'Dashboard',
            'icon'   => 'fa-solid fa-gauge',
            'href'   => route('admin.dashboard'),
            'active' => request()->routeIs('admin.dashboard'),
        ],
        [
            'header' => 'Gestión',
        ],
        [
        'name'=>'Roles y permisos',
        'icon'=>'fa-solid fa-shield-halved',
        'href'=>route('admin.roles.index'),
        'active'=> request()->routeIs('admin.roles.*')
        ] ,
        [
        'name'=>'Usuarios',
        'icon'=>'fa-solid fa-users',
        'href'=>route('admin.usuarios.index'),
        'active'=> request()->routeIs('admin.usuarios.*')
        ] ,
        [
        'name'=>'Estudiantes',
        'icon'=>'fa-solid fa-user-graduate',
        'href'=>route('admin.estudiantes.index'),
        'active'=> request()->routeIs('admin.estudiantes.*')
        ] ,
        [
        'name'=>'Tutores',
        'icon'=>'fa-solid fa-chalkboard-user',
        'href'=>route('admin.tutors.index'),
        'active'=> request()->routeIs('admin.tutors.*')
        ],
        [
        'name'=>'Citas',
        'icon'=>'fa-solid fa-calendar-check',
        'href'=>route('admin.appointments.index'),
        'active'=> request()->routeIs('admin.appointments.*')
        ] ,
        [
        'name'=>'Soporte',
        'icon'=>'fa-solid fa-headset',
        'href'=>'#',
        'active'=> false
        ] ,
        // Aquí irán los demás ítems del sidebar más adelante
    ];
@endphp

<aside id="top-bar-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-full transition-transform -translate-x-full sm:translate-x-0"
    aria-label="Sidebar">

    <div class="h-full px-3 pt-24 pb-4 overflow-y-auto bg-neutral-primary-soft border-e border-default">

        <ul class="space-y-1 font-medium">
            @foreach ($links as $link)
                <li>
                    {{-- Encabezado de sección --}}
                    @isset($link['header'])
                        <div class="px-2 pt-4 pb-1 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            {{ $link['header'] }}
                        </div>

                    @else
                        {{-- Enlace con submenu --}}
                        @isset($link['submenu'])
                            <button type="button"
                                class="flex items-center w-full justify-between px-2 py-1.5 text-body rounded-base hover:bg-neutral-tertiary hover:text-fg-brand group"
                                aria-controls="dropdown-{{ $loop->index }}"
                                data-collapse-toggle="dropdown-{{ $loop->index }}">
                                <span class="w-6 h-6 inline-flex items-center justify-center text-gray-500">
                                    <i class="{{ $link['icon'] }}"></i>
                                </span>
                                <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">{{ $link['name'] }}</span>
                                <svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/>
                                </svg>
                            </button>
                            <ul id="dropdown-{{ $loop->index }}" class="hidden py-2 space-y-1">
                                @foreach ($link['submenu'] as $item)
                                    <li>
                                        <a href="{{ $item['href'] }}"
                                            class="pl-10 flex items-center px-2 py-1.5 text-body rounded-base hover:bg-neutral-tertiary hover:text-fg-brand group">
                                            {{ $item['name'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>

                        @else
                            {{-- Enlace simple --}}
                            <a href="{{ $link['href'] }}"
                                class="flex items-center px-2 py-1.5 text-body rounded-base hover:bg-neutral-tertiary hover:text-fg-brand group {{ $link['active'] ? 'bg-gray-100 text-gray-900 font-semibold' : '' }}">
                                <span class="w-6 h-6 inline-flex items-center justify-center text-gray-500">
                                    <i class="{{ $link['icon'] }}"></i>
                                </span>
                                <span class="ms-3">{{ $link['name'] }}</span>
                            </a>
                        @endisset
                    @endisset
                </li>
            @endforeach
        </ul>

    </div>
</aside>