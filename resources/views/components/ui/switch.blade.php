@props([
    'checked' => false,
    'id'      => null,
    'name'    => null,
    'value'   => '1',
    'label'   => null,
])

@php
    $id = $id ?? 'switch-' . uniqid();
@endphp

<label class="inline-flex items-center cursor-pointer group">
    <div class="relative">
        <input type="checkbox" 
               id="{{ $id }}" 
               name="{{ $name }}" 
               value="{{ $value }}"
               {{ $checked ? 'checked' : '' }}
               {{ $attributes->merge(['class' => 'sr-only peer']) }}>
        
        <div class="w-10 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-emerald-500/20 transition-all duration-200 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-1 after:inset-s-1 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500 shadow-inner"></div>
    </div>
    @if($label)
        <span class="ms-3 text-sm font-medium text-gray-700 group-hover:text-gray-900 transition-colors">{{ $label }}</span>
    @endif
</label>
