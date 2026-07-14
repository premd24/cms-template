@props([
    'show' => null,
    'title',
    'description' => null,
    'icon' => null,
])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center py-12 text-center border border-dashed border-gray-200']) }}
    @if ($show) x-show="{{ $show }}" x-cloak @endif>

    @if ($icon)
        <div class="p-3 bg-white text-gray-400 rounded-2xl inline-block border border-dashed">
            <x-dynamic-component :component="$icon" class="h-8 w-8 text-gray-300" />
        </div>
    @elseif(isset($customIcon))
        {{ $customIcon }}
    @else
        <x-heroicon-o-squares-plus class="h-12 w-12 text-gray-300" />
    @endif

    <h4 class="text-sm font-bold text-gray-900 mt-4">{{ $title }}</h4>

    @if ($description)
        <p class="text-xs font-semibold text-gray-400 mt-1 max-w-xs leading-relaxed">{{ $description }}</p>
    @endif

    @if (isset($action))
        <div class="mt-4">
            {{ $action }}
        </div>
    @endif
</div>
