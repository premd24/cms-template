<div class="relative flex min-h-screen items-center justify-center bg-surface-2 px-6 py-12 overflow-hidden">
    <div
        class="absolute inset-0 bg-[radial-gradient(#d5d5d5_1px,transparent_1px)] bg-size-[20px_20px] opacity-40 pointer-events-none">
    </div>
    <div class="absolute -top-40 -left-40 w-96 h-96 rounded-full bg-primary-500/5 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 rounded-full bg-primary-500/5 blur-3xl pointer-events-none">
    </div>

    <div class="relative z-10 w-full max-w-md">
        <x-ui.card class="p-8 md:p-12">
            <div class="mb-6 flex justify-center">
                <div class="flex items-center gap-2.5">
                    <x-ui.logo class="h-8 w-8" />
                    <span class="text-2xl font-black text-gray-900 tracking-tight">{{ config('app.name', 'Boilerplate') }}</span>
                </div>
            </div>
            <div class="mb-10 text-center">
                <h1 class="mb-3 text-3xl font-extrabold tracking-tight text-gray-900">Welcome Back</h1>
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
        </x-ui.card>
    </div>
</div>

