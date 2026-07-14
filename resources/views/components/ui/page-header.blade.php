@props([
    'title',
    'description' => null
])

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 leading-tight">{{ $title }}</h1>
        @if($description)
            <p class="text-gray-500 mt-1 text-sm sm:text-base leading-relaxed">{{ $description }}</p>
        @endif
    </div>
    
    @if(isset($actions))
        <div class="flex items-center gap-3 sm:justify-end">
            {{ $actions }}
        </div>
    @endif
</div>
