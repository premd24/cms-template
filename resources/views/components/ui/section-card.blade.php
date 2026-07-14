@props(['title', 'subtitle' => null, 'errorName' => null])

<x-ui.card {{ $attributes->merge(['class' => 'space-y-2']) }}>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4">
        <div>
            <h3 class="text-lg font-bold text-gray-900">{{ $title }}</h3>
            @if ($subtitle)
                <p class="text-xs font-semibold text-gray-400 mt-1 uppercase tracking-wider">{{ $subtitle }}</p>
            @endif
        </div>

        @if (isset($action))
            <div>
                {{ $action }}
            </div>
        @endif
    </div>

    @if ($errorName)
        <p class="text-sm text-red-500 font-semibold error-message hidden mt-1" data-error-for="{{ $errorName }}"></p>
    @endif

    <div class="space-y-4">
        {{ $slot }}
    </div>
</x-ui.card>
