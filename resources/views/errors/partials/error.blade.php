<div class="relative flex min-h-screen items-center justify-center bg-surface-2 px-6 py-12 overflow-hidden">
    <div
        class="absolute inset-0 bg-[radial-gradient(#d5d5d5_1px,transparent_1px)] bg-size-[20px_20px] opacity-40 pointer-events-none">
    </div>
    <div class="absolute -top-40 -left-40 w-96 h-96 rounded-full bg-primary-500/5 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 rounded-full bg-primary-500/5 blur-3xl pointer-events-none">
    </div>

    <x-ui.card class="relative w-full max-w-md rounded-2xl border border-gray-150 bg-white p-8 text-center sm:p-10 z-10">
        <div class="mb-8 flex justify-center">
            <div class="flex items-center gap-2.5">
                <x-ui.logo class="h-8 w-8" />
                <span class="text-2xl font-black text-gray-900 tracking-tight">{{ config('app.name', 'Boilerplate') }}</span>
            </div>
        </div>
        <div class="mb-6 flex justify-center">
            <div
                class="h-20 w-20 rounded-full bg-primary-50 border border-primary-100 flex items-center justify-center text-primary-700 font-mono text-2xl font-bold tracking-wider select-none shadow-sm shadow-primary-100/10">
                {{ $code }}
            </div>
        </div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">
            {{ $title }}
        </h1>
        <p class="mx-auto mt-3 max-w-xs text-sm leading-relaxed text-gray-500 font-medium">
            {{ $message }}
        </p>
        <div class="flex flex-row items-center justify-center gap-3 w-full mt-3">
            <x-button :href="url('/')" variant="primary" icon-start="heroicon-o-home">
                Back to home
            </x-button>
            <x-button type="button" onclick="window.history.back()" variant="secondary-outline"
                icon-start="heroicon-o-arrow-left">
                Go back
            </x-button>
        </div>
    </x-ui.card>
</div>

