@props(['name', 'title' => null, 'maxWidth' => '2xl'])

@php
    $maxWidth = [
        'sm' => 'sm:max-w-sm',
        'md' => 'sm:max-w-md',
        'lg' => 'sm:max-w-lg',
        'xl' => 'sm:max-w-xl',
        '2xl' => 'sm:max-w-2xl',
        '3xl' => 'sm:max-w-3xl',
        '4xl' => 'sm:max-w-4xl',
        '5xl' => 'sm:max-w-5xl',
        '6xl' => 'sm:max-w-6xl',
        '7xl' => 'sm:max-w-7xl',
        'full' => 'sm:max-w-full',
    ][$maxWidth];
@endphp

<template x-teleport="body">
    <div {{ $attributes->merge(['class' => 'fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6', 'style' => 'display: none;', 'name' => $name]) }} x-data="{ show: false }" x-show="show"
        x-init="$watch('show', value => {
            const modalName = $el.getAttribute('name') || '{{ $name }}';
            if (value) {
                document.body.classList.add('overflow-hidden');
                window.openModals = window.openModals || [];
                if (!window.openModals.includes(modalName)) {
                    window.openModals.push(modalName);
                }
            } else {
                if (window.openModals) {
                    window.openModals = window.openModals.filter(m => m !== modalName);
                    if (window.openModals.length === 0) {
                        document.body.classList.remove('overflow-hidden');
                    }
                } else {
                    document.body.classList.remove('overflow-hidden');
                }
            }
        })"
        x-on:open-modal.window="if ($event.detail.name === '{{ $name }}' || $event.detail.name === $el.getAttribute('name')) show = true"
        x-on:close-modal.window="if ($event.detail.name === '{{ $name }}' || $event.detail.name === $el.getAttribute('name')) show = false"
        x-on:keydown.escape.window="if (show && window.openModals && window.openModals[window.openModals.length - 1] === ($el.getAttribute('name') || '{{ $name }}')) show = false">
        <!-- Background backdrop -->
        <div x-show="show" class="fixed inset-0 transition-opacity" x-on:click="show = false"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="absolute inset-0 bg-gray-900/50"></div>
        </div>

        <!-- Modal Content -->
        <div x-show="show"
            class="relative w-full {{ $maxWidth }} bg-white rounded-2xl shadow-[0_20px_70px_rgba(0,0,0,0.3)] border border-gray-100 transform transition-all overflow-hidden flex flex-col max-h-[calc(100vh-3rem)]"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-8 scale-95">
            @if ($title)
                <div class="px-6 py-4 flex items-center justify-between border-b border-dashed bg-gray-50/50 relative z-10 shrink-0">
                    <h3 class="modal-title text-xl font-bold text-gray-800 tracking-tight">{{ $title }}</h3>
                    <button type="button" @click="show = false"
                        class="h-8 w-8 rounded-full hover:bg-gray-200/50 text-gray-400 hover:text-gray-600 transition-all flex items-center justify-center cursor-pointer">
                        <x-heroicon-o-x-mark class="h-5 w-5" />
                    </button>
                </div>
            @endif

            <div class="p-6 relative z-10 overflow-y-auto flex-1 min-h-0">
                {{ $slot }}
            </div>

            @if (isset($footer))
                <div class="px-8 py-6 bg-gray-50/50 border-t border-gray-100 flex justify-end gap-3 relative z-10 shrink-0">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</template>
