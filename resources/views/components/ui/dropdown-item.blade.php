@props([
    'color' => null,
])

@php
    /**
     * Tailwind Safelist for dynamic dropdown item colors:
     * bg-sky-500 hover:bg-sky-50 hover:text-sky-700
     * bg-indigo-500 hover:bg-indigo-50 hover:text-indigo-700
     * bg-emerald-500 hover:bg-emerald-50 hover:text-emerald-700
     * bg-pink-500 hover:bg-pink-50 hover:text-pink-700
     * bg-purple-500 hover:bg-purple-50 hover:text-purple-700
     */
    $dotColor = $color ? "bg-{$color}-500" : 'bg-gray-400';
    $hoverBg = $color ? "hover:bg-{$color}-50" : 'hover:bg-gray-50';
    $hoverText = $color ? "hover:text-{$color}-700" : 'hover:text-gray-700';
@endphp

<button type="button"
    {{ $attributes->merge([
        'class' => "w-full text-left px-4 py-2.5 text-sm font-bold text-gray-700 {$hoverBg} {$hoverText} flex items-center gap-2.5 transition",
    ]) }}>
    @if ($color)
        <span class="h-2 w-2 rounded-full {{ $dotColor }} shrink-0"></span>
    @endif
    {{ $slot }}
</button>
