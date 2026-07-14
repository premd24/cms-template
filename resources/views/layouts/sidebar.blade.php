@php
    $navbar = [
        'Dashboard' => [
            ['label' => 'Dashboard', 'icon' => 'heroicon-o-squares-2x2', 'route' => url('/')],
        ],
        'Boilerplate' => [
            ['label' => 'Sample Items', 'icon' => 'heroicon-o-archive-box', 'route' => route('pages.sample-items')],
            ['label' => 'UI Components', 'icon' => 'heroicon-o-swatch', 'route' => route('pages.components')],
            ['label' => 'Error Previews', 'icon' => 'heroicon-o-exclamation-triangle', 'route' => route('pages.errors-preview')],
        ],
        'Configuration' => [
            ['label' => 'Settings', 'icon' => 'heroicon-o-cog-6-tooth', 'route' => route('pages.settings')],
        ],
    ];
@endphp

<div class="sidebar-panel bg-primary-500 animated-dashed-border-v">
    <div class="flex h-full grow flex-col bg-surface-3 text-gray-700">

        <!-- Sidebar Header -->
        <div class="flex h-16 items-center justify-center px-6 shrink-0 relative">
            <a href="/" class="flex items-center justify-center gap-2.5">
                <x-ui.logo class="h-6.5 w-6.5" />
                <span class="text-xl font-black text-gray-900 tracking-tight">{{ config('app.name', 'Boilerplate') }}</span>
            </a>

            <button @click="isSidebarOpen = false"
                class="xl:hidden absolute right-4 p-2 rounded-lg hover:bg-white/10 transition-colors text-gray-700 hover:text-gray-700">
                <x-heroicon-o-x-mark class="h-6 w-6" />
            </button>
        </div>

        <!-- Horizontal animated border (mobile only) -->
        <div class="relative h-px w-full xl:hidden pointer-events-none shrink-0"
            style="background-image: linear-gradient(to right, rgba(0,0,0,0.15) 60%, transparent 60%); background-size: 14px 1px; background-repeat: repeat-x;">
            <div class="absolute top-0 left-0 w-16 h-px animate-border-slide-h"
                style="background: linear-gradient(90deg, transparent, rgba(0,0,0,0.5), transparent);"></div>
        </div>

        <!-- Sidebar Menu -->
        <div class="grow overflow-y-auto no-scrollbar py-6 px-4 space-y-1">

            @foreach ($navbar as $group => $items)
                @php
                    $isGroupActive = false;

                    foreach ($items as $item) {
                        $currentUrl = request()->url();
                        $itemUrl = $item['route'];

                        if (
                            $currentUrl == $itemUrl ||
                            (str_starts_with($currentUrl, $itemUrl . '/') && $itemUrl !== url('/'))
                        ) {
                            $isGroupActive = true;
                            break;
                        }
                    }
                @endphp

                <div x-data="{ expanded: {{ $loop->first || $isGroupActive ? 'true' : 'false' }} }" class="{{ !$loop->first ? 'mt-4' : '' }}">

                    <!-- Group Header -->
                    <button @click="expanded = !expanded"
                        class="flex items-center justify-between cursor-pointer w-full px-4 py-2 text-xs font-black text-gray-700 uppercase tracking-widest hover:text-primary-700 transition-colors group">
                        <span>{{ $group }}</span>

                        <x-heroicon-o-chevron-right
                            class="h-3 w-3 transition-transform duration-200 text-gray-700 group-hover:text-primary-700"
                            x-bind:class="{ 'rotate-90': expanded }" />
                    </button>

                    <!-- Group Items -->
                    <div x-show="expanded" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-1" class="mt-1 space-y-1" x-cloak>

                        @foreach ($items as $item)
                            @php
                                $currentUrl = request()->url();
                                $itemUrl = $item['route'];

                                $isActive =
                                    $currentUrl == $itemUrl ||
                                    (str_starts_with($currentUrl, $itemUrl . '/') && $itemUrl !== url('/'));
                            @endphp

                            <a href="{{ $item['route'] }}"
                                class="group flex items-center px-4 py-2.5 text-sm rounded-lg transition-all duration-200
                                                {{ $isActive
                                                    ? 'text-primary-700 bg-white font-bold'
                                                    : 'text-gray-700 hover:text-gray-700 hover:bg-gray-700/10 font-semibold' }}">

                                <x-dynamic-component :component="$item['icon']"
                                    class="h-5 w-5 mr-3 transition-colors
                                                    {{ $isActive ? 'text-primary-700' : 'text-gray-700 group-hover:text-gray-700' }}" />

                                {{ $item['label'] }}
                            </a>
                        @endforeach

                    </div>

                </div>
            @endforeach

        </div>
    </div>
</div>

<!-- Mobile Overlay -->
<template x-if="isSidebarOpen">
    <div @click="isSidebarOpen = false"
        class="fixed inset-0 z-40 bg-gray-900/50 backdrop-blur-sm xl:hidden transition-opacity duration-300"></div>
</template>

