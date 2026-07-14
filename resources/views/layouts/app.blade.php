<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="sideblock">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'CMS') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        // ── Global Helper: Wait for jQuery ──────────────────────────────────────────
        window.onReady = function(callback) {
            if (typeof window.$ !== 'undefined') {
                callback();
            } else {
                setTimeout(() => window.onReady(callback), 50);
            }
        };
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-surface-2 text-gray-900 antialiased overflow-x-hidden" x-data="{ isSidebarOpen: window.innerWidth >= 1280, activeSegment: 'dashboards' }"
    @resize.window="if (window.innerWidth >= 1280) isSidebarOpen = true"
    :class="isSidebarOpen ? 'is-sidebar-open' : ''">
    <!-- Grid pattern for premium look -->
    <div class="fixed inset-0 bg-[radial-gradient(#d5d5d5_1px,transparent_1px)] bg-size-[20px_20px] opacity-40 pointer-events-none -z-10"></div>

    <!-- Background decorative blobs for premium feel -->
    <div class="fixed -top-40 -left-40 w-96 h-96 rounded-full bg-primary-500/5 blur-3xl pointer-events-none -z-10"></div>
    <div class="fixed -bottom-40 -right-40 w-96 h-96 rounded-full bg-primary-500/5 blur-3xl pointer-events-none -z-10"></div>

    <div id="root" class="grid min-h-full grid-rows-[auto_1fr]">
        @include('layouts.sidebar')
        @include('layouts.header')
        <x-ui.animated-border-h />

        <main class="main-content transition-content grid grid-cols-1">
            <div class="transition-content overflow-hidden px-(--margin-x) py-8">
                @yield('content')
            </div>
        </main>
    </div>

    @include('layouts.toaster')
    @include('layouts.confirm-modal')

    @stack('scripts')
</body>

</html>
