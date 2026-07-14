{{-- Split login: dark brand panel (particles + animated logo) beside the form. --}}
<div class="flex min-h-screen">
    {{-- ── Brand panel (desktop only) ─────────────────────────────────────── --}}
    <div class="relative hidden overflow-hidden bg-gray-900 lg:flex lg:w-1/2">
        <div id="particles-js" class="absolute inset-0"></div>
        <div class="pointer-events-none absolute inset-0 bg-linear-to-br from-gray-900 via-gray-900/90 to-primary-950/50"></div>

        <div class="relative z-10 flex w-full flex-col justify-between p-12 xl:p-16">
            <div class="flex items-center gap-2.5">
                <x-ui.logo class="h-8 w-8" />
                <span class="text-2xl font-black text-white tracking-tight">{{ config('app.name', 'Boilerplate') }}</span>
            </div>

            <div>
                <h2 class="text-4xl font-extrabold leading-[1.1] tracking-tight text-white xl:text-5xl">
                    Manage your media,<br>beautifully.
                </h2>
                <p class="mt-6 max-w-md text-lg leading-relaxed text-gray-400">
                    Files, Lottie animations, and use cases — organized in one calm, focused workspace.
                </p>
            </div>

            <p class="text-sm text-gray-500">&copy; {{ date('Y') }} CMS. All rights reserved.</p>
        </div>
    </div>

    {{-- ── Form panel ─────────────────────────────────────────────────────── --}}
    <div class="flex w-full items-center justify-center px-6 py-12 sm:px-12 lg:w-1/2">
        <div class="w-full max-w-md">
            {{-- Logo (mobile header) --}}
            <div class="mb-10 flex justify-center lg:hidden">
                <div class="flex items-center gap-2.5">
                    <x-ui.logo class="h-8 w-8" />
                    <span class="text-2xl font-black text-gray-900 tracking-tight">{{ config('app.name', 'Boilerplate') }}</span>
                </div>
            </div>

            <div class="mb-10">
                <h1 class="mb-2 text-3xl font-extrabold tracking-tight text-gray-900">Welcome back</h1>
                <p class="font-medium text-gray-500">Log in to manage your workspace</p>
            </div>

            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                <x-ui.input label="Email Address" name="email" type="email" icon="heroicon-o-at-symbol"
                    placeholder="john@example.com" required autofocus />

                <x-ui.input label="Password" name="password" type="password" icon="heroicon-o-lock-closed"
                    placeholder="••••••••" required />

                <x-ui.checkbox name="remember" label="Keep me signed in" />

                <x-button fullWidth variant="primary" type="submit" icon="heroicon-o-arrow-right">
                    Sign In
                </x-button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Particles tuned for the dark brand panel. `particlesJS` is bootstrapped
        // from app.js (vendored, no CDN).
        const initParticles = function () {
            if (typeof particlesJS === 'undefined') return setTimeout(initParticles, 100);
            particlesJS('particles-js', {
                particles: {
                    number: { value: 55, density: { enable: true, value_area: 900 } },
                    color: { value: '#57DE6B' },
                    shape: { type: 'circle' },
                    opacity: { value: 0.85, random: true, anim: { enable: true, speed: 0.6, opacity_min: 0.4, sync: false } },
                    size: { value: 3, random: true },
                    line_linked: { enable: true, distance: 150, color: '#57DE6B', opacity: 0.5, width: 1 },
                    move: { enable: true, speed: 0.9, direction: 'none', random: true, straight: false, out_mode: 'out' },
                },
                interactivity: {
                    detect_on: 'canvas',
                    events: { onhover: { enable: true, mode: 'grab' }, onclick: { enable: false }, resize: true },
                    modes: { grab: { distance: 170, line_linked: { opacity: 0.45 } } },
                },
                retina_detect: true,
            });
        };
        initParticles();
    });
</script>
