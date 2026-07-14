@props([
    'title' => null,
    'headers' => [],
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl shadow-soft border border-gray-100 overflow-hidden']) }}>
    @if($title || isset($actions))
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            @if($title)
                <h3 class="font-bold text-gray-900 text-lg">{{ $title }}</h3>
            @endif
            
            @if(isset($actions))
                <div class="flex items-center gap-3">
                    {{ $actions }}
                </div>
            @endif
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50/50">
                    @foreach($headers as $header)
                        <th class="px-6 py-3 text-xs font-bold text-gray-400 uppercase tracking-wider">
                            {{ $header }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                {{ $slot }}
            </tbody>
        </table>
    </div>
    
    @if(isset($footer))
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $footer }}
        </div>
    @endif
</div>
