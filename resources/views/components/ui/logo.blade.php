@props([
    'class' => 'h-6 w-6',
])

<svg class="{{ $class }} shrink-0" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
    <!-- Top Face -->
    <path d="M16 3L28 10L16 17L4 10L16 3Z" fill="url(#logo-grad-top)" />
    <!-- Left Face -->
    <path d="M4 10L16 17V29L4 22V10Z" fill="url(#logo-grad-left)" />
    <!-- Right Face -->
    <path d="M16 17L28 10V22L16 29V17Z" fill="url(#logo-grad-right)" />
    
    <defs>
        <linearGradient id="logo-grad-top" x1="4" y1="10" x2="28" y2="10" gradientUnits="userSpaceOnUse">
            <stop stop-color="#8df49c" />
            <stop offset="1" stop-color="#57DE6B" />
        </linearGradient>
        <linearGradient id="logo-grad-left" x1="4" y1="10" x2="16" y2="29" gradientUnits="userSpaceOnUse">
            <stop stop-color="#57DE6B" />
            <stop offset="1" stop-color="#33b469" />
        </linearGradient>
        <linearGradient id="logo-grad-right" x1="28" y1="10" x2="16" y2="29" gradientUnits="userSpaceOnUse">
            <stop stop-color="#33b469" />
            <stop offset="1" stop-color="#1a6f3e" />
        </linearGradient>
    </defs>
</svg>
