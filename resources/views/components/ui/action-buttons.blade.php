@props([
    'index' => 'index',
    'max' => 'blocks.length',
    'up' => 'moveUp',
    'down' => 'moveDown',
    'delete' => 'removeBlock',
])

<div {{ $attributes->merge(['class' => 'flex items-center gap-1.5']) }}>
    {{-- Move Up --}}
    <button type="button" @click="{{ $up }}({{ $index }})" :disabled="{{ $index }} === 0"
        class="h-9 w-9 cursor-pointer rounded-xl bg-gray-50 border border-gray-200 text-gray-500 hover:text-gray-900 hover:bg-gray-100 hover:border-gray-300 flex items-center justify-center transition-all disabled:opacity-30 disabled:pointer-events-none"
        title="Move Up">
        <x-heroicon-o-chevron-up class="h-4 w-4" />
    </button>
    {{-- Move Down --}}
    <button type="button" @click="{{ $down }}({{ $index }})"
        :disabled="{{ $index }} === {{ $max }} - 1"
        class="h-9 w-9 cursor-pointer rounded-xl bg-gray-50 border border-gray-200 text-gray-500 hover:text-gray-900 hover:bg-gray-100 hover:border-gray-300 flex items-center justify-center transition-all disabled:opacity-30 disabled:pointer-events-none"
        title="Move Down">
        <x-heroicon-o-chevron-down class="h-4 w-4" />
    </button>
    {{-- Delete --}}
    <button type="button" @click="{{ $delete }}({{ $index }})"
        class="h-9 w-9 cursor-pointer rounded-xl bg-red-50 border border-red-200 text-red-400 hover:text-red-600 hover:bg-red-100 hover:border-red-300 flex items-center justify-center transition-all"
        title="Delete">
        <x-heroicon-o-trash class="h-4 w-4" />
    </button>
</div>
