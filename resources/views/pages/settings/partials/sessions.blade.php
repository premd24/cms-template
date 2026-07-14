<x-ui.section-card title="Browser Sessions" subtitle="Manage and log out your active sessions on other browsers and devices.">
    
    @if (config('session.driver') !== 'database')
        <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-sm flex items-start gap-3">
            <x-heroicon-o-exclamation-triangle class="h-5 w-5 shrink-0 mt-0.5" />
            <div>
                <span class="font-bold">Database session tracking is inactive.</span>
                <p class="mt-1 text-xs text-amber-700">Currently using the "{{ config('session.driver') }}" driver. To track and manage multiple active sessions, configure your environment with <code class="bg-amber-100/50 px-1 py-0.5 rounded font-mono">SESSION_DRIVER=database</code>.</p>
            </div>
        </div>
    @endif

    <div class="space-y-4">
        @foreach ($sessions as $session)
            <div class="flex items-center justify-between p-4 border border-gray-150 rounded-xl bg-gray-50/30 hover:bg-gray-50/80 transition-colors">
                <div class="flex items-center gap-4 min-w-0">
                    <div class="h-10 w-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                        <x-dynamic-component :component="$session->icon" class="h-5 w-5" />
                    </div>
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-sm font-bold text-gray-900 truncate">{{ $session->browser }} on {{ $session->os }}</span>
                            @if ($session->is_current)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 uppercase tracking-wider">
                                    This device
                                </span>
                            @endif
                        </div>
                        <div class="text-xs text-gray-400 font-semibold mt-0.5">
                            {{ $session->ip_address }} • {{ $session->last_active }}
                        </div>
                    </div>
                </div>
                @if (!$session->is_current && config('session.driver') === 'database')
                    <form action="{{ route('pages.settings.session.revoke', $session->id) }}" method="POST"
                        onsubmit="return confirmDelete(this, 'Revoke Session?', 'Are you sure you want to log out of this active session? This will force-logout the other device.')">
                        @csrf
                        @method('DELETE')
                        <x-button type="submit" variant="danger" size="sm">
                            Revoke
                        </x-button>
                    </form>
                @endif
            </div>
        @endforeach
    </div>
</x-ui.section-card>
