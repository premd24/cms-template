@props([
    'align' => 'right',
    'width' => 'w-56',
])

@php
    $alignmentClasses = match ($align) {
        'left' => 'left-0',
        'right' => 'right-0',
        default => 'right-0',
    };
@endphp

<div class="relative" x-data="{ open: false }" {{ $attributes }}>
    {{-- Trigger --}}
    <div @click="open = !open">
        {{ $trigger }}
    </div>

    {{-- Dropdown Panel --}}
    <div x-show="open" @click.away="open = false"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute {{ $alignmentClasses }} mt-2 {{ $width }} bg-white border border-gray-100 rounded-2xl shadow-[0_10px_30px_rgba(0,0,0,0.08)] py-2 z-20 overflow-hidden"
        x-cloak>
        {{ $slot }}
    </div>
</div>
