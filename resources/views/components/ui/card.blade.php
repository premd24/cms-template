@props([
    'padding' => 'p-6',
    'rounded' => 'rounded-2xl',
    'hover' => false,
    'overflow' => 'overflow-hidden',
])

@php
    $groupClass = $hover ? 'group' : '';
    $baseClasses = "bg-white relative $overflow border border-dashed $padding $rounded $groupClass transition-all duration-300";
    $hoverClasses = $hover ? 'cursor-pointer' : '';
@endphp

<div {{ $attributes->merge(['class' => "$baseClasses $hoverClasses"]) }}>
    {{ $slot }}
</div>
