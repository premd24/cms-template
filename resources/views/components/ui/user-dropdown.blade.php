@props([
    'align' => 'right', // 'right' or 'top'
])

@php
    $alignmentClasses = match ($align) {
        'right' => 'right-0',
        'top' => 'bottom-full left-0 mb-3',
        default => 'right-0 mt-3',
    };
@endphp

<div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100"
    x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100"
    x-transition:leave-end="transform opacity-0 scale-95"
    class="absolute {{ $alignmentClasses }} w-72 bg-white rounded-2xl border border-gray-200 z-40 overflow-hidden"
    x-cloak>

    <!-- User Info Header -->
    <div class="px-4 py-2 bg-gray-50/50 flex items-center gap-4 border-b border-gray-100">
        <div class="h-14 w-14 rounded-full border-2 border-white ring-1 ring-gray-100 overflow-hidden shrink-0">
            <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=57DE6B&color=fff"
                alt="Profile">
        </div>
        <div class="min-w-0">
            <div class="text-base font-bold text-gray-900 truncate">{{ Auth::user()->name }}</div>
            <div class="text-xs font-medium text-gray-400">{{ Auth::user()->email }}</div>
        </div>
    </div>

    <div class="p-2">
        <a href="{{ route('pages.settings') }}"
            class="flex items-center gap-4 px-4 py-3 rounded-2xl hover:bg-gray-50 transition-colors group">
            <div
                class="h-10 w-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center transition-transform group-hover:scale-110">
                <x-heroicon-o-cog-6-tooth class="h-5 w-5" />
            </div>
            <div>
                <div class="text-sm font-bold text-gray-700">Settings</div>
                <div class="text-[10px] font-medium text-gray-400">Webapp settings</div>
            </div>
        </a>
    </div>

    <!-- Logout Button -->
    <div class="p-4 bg-gray-50/30 border-t border-gray-50">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <x-button type="submit" fullWidth variant="secondary-outline" size="md"
                icon-start="heroicon-o-arrow-left-on-rectangle">
                Logout
            </x-button>
        </form>
    </div>
</div>
