@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'submit',
    'loading' => false,
    'icon' => null,
    'iconStart' => null,
    'fullWidth' => false,
    'href' => null,
])

@php
    $baseClasses =
        'cursor-pointer relative inline-flex items-center justify-center font-bold transition-all duration-200 active:scale-[0.98] disabled:opacity-50 disabled:pointer-events-none group/btn gap-2';

    $variants = [
        'primary' =>
            "bg-brand-primary text-black hover:text-white focus-visible:text-white relative overflow-hidden isolate before:content-[''] before:absolute before:inset-0 before:bg-brand-secondary before:-z-10 before:scale-x-0 before:origin-right before:transition-transform before:duration-300 before:ease-out hover:before:scale-x-100 hover:before:origin-left focus-visible:before:scale-x-100 focus-visible:before:origin-left transition-colors duration-300",
        'secondary' =>
            "bg-brand-secondary text-white hover:text-black focus-visible:text-black relative overflow-hidden isolate before:content-[''] before:absolute before:inset-0 before:bg-brand-primary before:-z-10 before:scale-x-0 before:origin-right before:transition-transform before:duration-300 before:ease-out hover:before:scale-x-100 hover:before:origin-left focus-visible:before:scale-x-100 focus-visible:before:origin-left transition-colors duration-300",
        'outline' =>
            "border border-brand-primary text-black hover:text-black focus-visible:text-black relative overflow-hidden isolate before:content-[''] before:absolute before:inset-0 before:bg-brand-primary before:-z-10 before:scale-x-0 before:origin-right before:transition-transform before:duration-300 before:ease-out hover:before:scale-x-100 hover:before:origin-left focus-visible:before:scale-x-100 focus-visible:before:origin-left transition-colors duration-300",
        'secondary-outline' =>
            "border border-brand-secondary text-black hover:text-white focus-visible:text-white relative overflow-hidden isolate before:content-[''] before:absolute before:inset-0 before:bg-brand-secondary before:-z-10 before:scale-x-0 before:origin-right before:transition-transform before:duration-300 before:ease-out hover:before:scale-x-100 hover:before:origin-left focus-visible:before:scale-x-100 focus-visible:before:origin-left transition-colors duration-300",
        'white' =>
            "bg-white text-black hover:text-white focus-visible:text-white relative overflow-hidden isolate before:content-[''] before:absolute before:inset-0 before:bg-brand-secondary before:-z-10 before:scale-x-0 before:origin-right before:transition-transform before:duration-300 before:ease-out hover:before:scale-x-100 hover:before:origin-left focus-visible:before:scale-x-100 focus-visible:before:origin-left transition-colors duration-300",
        'danger' =>
            "border border-red-500 text-red-600 hover:text-white focus-visible:text-white relative overflow-hidden isolate before:content-[''] before:absolute before:inset-0 before:bg-red-500 before:-z-10 before:scale-x-0 before:origin-right before:transition-transform before:duration-300 before:ease-out hover:before:scale-x-100 hover:before:origin-left focus-visible:before:scale-x-100 focus-visible:before:origin-left transition-colors duration-300",
        'ghost' => 'text-gray-600 hover:bg-gray-50',
    ];

    $sizes = [
        'sm' => 'px-4 py-2 text-xs',
        'md' => 'px-5 py-3 text-sm',
        'lg' => 'px-6 py-4 text-base',
        'xl' => 'px-8 py-5 text-lg',
    ];

    $classes =
        $baseClasses .
        ' ' .
        ($variants[$variant] ?? $variants['primary']) .
        ' ' .
        ($sizes[$size] ?? $sizes['lg']) .
        ' ' .
        ($fullWidth ? 'w-full' : '');

    $alpineLoading = $attributes->has('::loading') || $attributes->has('x-bind:loading');
    $loadingVar = $attributes->get('::loading') ?? ($attributes->get('x-bind:loading') ?? 'false');

    $tag = $href ? 'a' : 'button';
@endphp

<{{ $tag }}
    @if ($href) href="{{ $href }}" 
    @else 
        type="{{ $type }}" @endif
    {{ $attributes->whereDoesntStartWith(['::loading', 'x-bind:loading'])->merge(['class' => $classes]) }}
    @if ($loading) disabled @endif
    @if ($alpineLoading) :disabled="{{ $loadingVar }}" @endif>
    <!-- Loading Spinner (Blade version) -->
    @if ($loading && !$alpineLoading)
        <svg class="animate-spin h-5 w-5 absolute" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
            </path>
        </svg>
    @endif

    <!-- Loading Spinner (Alpine version) -->
    @if ($alpineLoading)
        <svg x-show="{{ $loadingVar }}" class="animate-spin h-5 w-5 absolute" xmlns="http://www.w3.org/2000/svg"
            fill="none" viewBox="0 0 24 24" x-cloak>
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
            </circle>
            <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
            </path>
        </svg>
    @endif

    <!-- Content -->
    <span
        @if ($alpineLoading) :class="{{ $loadingVar }} ? 'opacity-0' : 'flex items-center gap-2 cursor-pointer'" @endif
        class="{{ $loading && !$alpineLoading ? 'opacity-0' : 'flex items-center gap-2 cursor-pointer' }}">
        @if ($iconStart)
            <x-dynamic-component :component="$iconStart"
                class="h-5 w-5 transition-transform group-hover/btn:-translate-x-1" />
        @endif

        {{ $slot }}

        @if ($icon)
            <x-dynamic-component :component="$icon" class="h-5 w-5 transition-transform group-hover/btn:translate-x-1" />
        @endif
    </span>
    </{{ $tag }}>

    <script>
        if (typeof window.setBtnLoading === 'undefined') {
            window.setBtnLoading = function($btn, isLoading, loadingText = 'Saving...') {
                if (!($btn instanceof jQuery)) {
                    $btn = $($btn);
                }
                if (isLoading) {
                    if ($btn.data('is-loading')) return;

                    // Cache original markup and freeze dimensions to prevent layout shifting
                    $btn.data('original-html', $btn.html());
                    $btn.data('is-loading', true);

                    const origWidth = $btn.outerWidth();
                    $btn.css('min-width', origWidth + 'px');
                    $btn.prop('disabled', true).addClass('opacity-80 pointer-events-none');

                    const spinnerHtml = `
                    <div class="flex items-center justify-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>${loadingText}</span>
                    </div>
                `;
                    $btn.html(spinnerHtml);
                } else {
                    if (!$btn.data('is-loading')) return;

                    $btn.html($btn.data('original-html'));
                    $btn.removeData('original-html');
                    $btn.removeData('is-loading');

                    $btn.css('min-width', '');
                    $btn.prop('disabled', false).removeClass('opacity-80 pointer-events-none');
                }
            };
        }
    </script>
