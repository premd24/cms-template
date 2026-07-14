<x-ui.section-card title="System & Workspace Information" subtitle="Technical environment specs and configurations.">
    <div class="border border-gray-150 rounded-xl overflow-hidden bg-white">
        <table class="min-w-full divide-y divide-gray-150">
            <tbody class="divide-y divide-gray-100">
                <tr>
                    <td class="px-6 py-4 text-sm font-bold text-gray-500 w-1/3">Laravel Version</td>
                    <td class="px-6 py-4 text-sm font-semibold text-gray-900 font-mono">{{ $systemInfo['laravel_version'] }}</td>
                </tr>
                <tr>
                    <td class="px-6 py-4 text-sm font-bold text-gray-500">PHP Version</td>
                    <td class="px-6 py-4 text-sm font-semibold text-gray-900 font-mono">{{ $systemInfo['php_version'] }}</td>
                </tr>
                <tr>
                    <td class="px-6 py-4 text-sm font-bold text-gray-500">Environment</td>
                    <td class="px-6 py-4 text-sm font-bold text-gray-900">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 capitalize">
                            {{ $systemInfo['app_env'] }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="px-6 py-4 text-sm font-bold text-gray-500">Debug Mode</td>
                    <td class="px-6 py-4 text-sm font-bold text-gray-900">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $systemInfo['app_debug'] === 'Enabled' ? 'bg-red-50 text-red-700' : 'bg-green-50 text-green-700' }}">
                            {{ $systemInfo['app_debug'] }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="px-6 py-4 text-sm font-bold text-gray-500">Session Driver</td>
                    <td class="px-6 py-4 text-sm font-semibold text-gray-900 font-mono">{{ $systemInfo['session_driver'] }}</td>
                </tr>
                <tr>
                    <td class="px-6 py-4 text-sm font-bold text-gray-500">Timezone</td>
                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $systemInfo['timezone'] }}</td>
                </tr>
                <tr>
                    <td class="px-6 py-4 text-sm font-bold text-gray-500">Application URL</td>
                    <td class="px-6 py-4 text-sm font-semibold text-gray-900 font-mono select-all">{{ $systemInfo['app_url'] }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</x-ui.section-card>
