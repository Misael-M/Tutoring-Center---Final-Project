@props(['tab', 'error' => false])
<li class="me-2">
    <style>
        .tab-link-red {
            color: #6b7280; /* text-gray-500 */
            border-bottom-width: 2px;
            border-color: transparent;
            transition: all 0.2s ease-in-out;
        }
        .tab-link-red:hover {
            color: #ff6b6b !important;
            border-color: #e5e7eb !important;
        }
        .tab-link-red.active {
            color: #ff6b6b !important;
            border-color: #ff6b6b !important;
            font-weight: 600;
        }
    </style>
    <a href="#" x-on:click.prevent="tab = '{{ $tab }}'"
        :class="tab === '{{ $tab }}' ? 'active' : ''"
        class="tab-link-red inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg group"
        :aria-current="tab === '{{ $tab }}' ? 'page' : undefined">     
        {{ $slot }}   
        @if ($error)
            <i class="fa-solid fa-circle-exclamation ms-2 animate-pulse"></i>
        @endif
    </a>
</li>