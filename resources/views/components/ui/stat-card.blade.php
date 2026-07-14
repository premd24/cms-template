@props([
    'label',
    'value',
    'icon',
    'trend' => null,
    'trendType' => 'up', // up, down
    'variant' => 'primary', // primary, blue, purple, amber, etc.
    'href' => null,
    'compact' => false,
])

@php
    $trendColors = [
        'up' => 'text-green-600 bg-green-50',
        'down' => 'text-red-600 bg-red-50',
    ];

    $trendClass = $trendColors[$trendType] ?? $trendColors['up'];

    // Interactive classes applied to the card if href is present
    $interactiveCardClasses = $href
        ? 'cursor-pointer active:scale-[0.98] transition-all duration-300 hover:border-brand-primary'
        : '';
@endphp

@if ($href)
    <a href="{{ $href }}" class="block no-underline">
@endif

<x-ui.card padding="{{ $compact ? 'p-4' : 'p-6' }}" {{ $attributes->merge(['class' => $interactiveCardClasses]) }}>
    @if ($compact)
        <div class="flex items-center gap-3.5">
            <div class="p-2 border border-{{ $variant }}-100 bg-{{ $variant }}-50 text-{{ $variant }}-600 rounded-lg shrink-0">
                <x-dynamic-component :component="$icon" class="h-5 w-5" />
            </div>
            <div class="min-w-0 flex-1">
                <div class="text-xs font-bold text-gray-400 uppercase tracking-wider truncate leading-none">{{ $label }}</div>
                <div class="text-2xl font-black text-gray-900 mt-1 leading-none">{{ $value }}</div>
            </div>
        </div>
    @else
        <div class="flex items-center justify-between mb-4">
            <div
                class="p-2 border border-{{ $variant }}-100 bg-{{ $variant }}-50 text-{{ $variant }}-600 rounded-lg">
                <x-dynamic-component :component="$icon" class="h-6 w-6" />
            </div>
            @if ($trend)
                <span class="text-xs font-bold {{ $trendClass }} px-2 py-1 rounded-full">
                    {{ $trendType === 'up' ? '+' : '-' }}{{ $trend }}
                </span>
            @endif
        </div>
        <div class="text-2xl font-bold text-gray-900">{{ $value }}</div>
        <div class="text-sm text-gray-500 mt-1">{{ $label }}</div>
    @endif
</x-ui.card>

@if ($href)
    </a>
@endif
