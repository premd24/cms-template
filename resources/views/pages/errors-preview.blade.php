@extends('layouts.app')

@section('content')
    <div class="space-y-10">
        <!-- Page Header -->
        <x-ui.page-header title="HTTP Error Previews" description="Test and preview standard HTTP exception templates. Click any button to view the page layout.">
        </x-ui.page-header>

        <!-- Error Pages Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @php
                $errorsList = [
                    [
                        'code' => 401,
                        'title' => '401 Unauthorized',
                        'desc' => 'Triggered when the user fails authentication or provides incorrect credentials.',
                        'variant' => 'blue'
                    ],
                    [
                        'code' => 402,
                        'title' => '402 Payment Required',
                        'desc' => 'Reserved for future use; displayed when a payment or premium action is required.',
                        'variant' => 'amber'
                    ],
                    [
                        'code' => 403,
                        'title' => '403 Forbidden',
                        'desc' => 'Occurs when the authenticated user does not have permission to access the resource.',
                        'variant' => 'red'
                    ],
                    [
                        'code' => 404,
                        'title' => '404 Not Found',
                        'desc' => 'Standard error displayed when a requested resource or URL cannot be found.',
                        'variant' => 'purple'
                    ],
                    [
                        'code' => 419,
                        'title' => '419 Page Expired',
                        'desc' => 'Laravel specific error triggered when a CSRF token mismatch occurs on form submission.',
                        'variant' => 'amber'
                    ],
                    [
                        'code' => 429,
                        'title' => '429 Too Many Requests',
                        'desc' => 'Displayed when a client hits API/web rate limits (too many requests in a timeframe).',
                        'variant' => 'emerald'
                    ],
                    [
                        'code' => 500,
                        'title' => '500 Server Error',
                        'desc' => 'Generic fallback code for unexpected internal exceptions or database failures.',
                        'variant' => 'red'
                    ],
                    [
                        'code' => 503,
                        'title' => '503 Service Unavailable',
                        'desc' => 'Shown when the application is undergoing temporary maintenance or is overloaded.',
                        'variant' => 'blue'
                    ]
                ];
            @endphp

            @foreach($errorsList as $item)
                <x-ui.card class="flex flex-col justify-between h-56 hover:border-primary-300 transition-all duration-300">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-bold px-2 py-1 rounded-full bg-{{ $item['variant'] }}-50 text-{{ $item['variant'] }}-700 border border-{{ $item['variant'] }}-100">
                                HTTP {{ $item['code'] }}
                            </span>
                        </div>
                        <h3 class="text-base font-bold text-gray-900 mb-2">{{ $item['title'] }}</h3>
                        <p class="text-xs font-semibold text-gray-400 leading-relaxed">{{ $item['desc'] }}</p>
                    </div>

                    <div class="pt-4 border-t border-dashed border-gray-100">
                        <x-button :href="route('pages.errors-preview.show', ['code' => $item['code']])" target="_blank" variant="outline" size="sm" class="w-full" icon="heroicon-o-arrow-top-right-on-square">
                            Preview Error
                        </x-button>
                    </div>
                </x-ui.card>
            @endforeach
        </div>
    </div>
@endsection
