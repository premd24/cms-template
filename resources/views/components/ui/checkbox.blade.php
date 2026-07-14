@props([
    'label' => null,
    'name',
    'id' => null,
    'checked' => false,
    'required' => false,
])

@php
    $id = $id ?? $name;
@endphp

<div class="flex items-center">
    <input 
        id="{{ $id }}" 
        name="{{ $name }}" 
        type="checkbox"
        {{ $checked ? 'checked' : '' }}
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'h-5 w-5 border-gray-300 accent-primary-500 text-primary-500 focus:ring-primary-500 transition-all cursor-pointer bg-gray-50/50']) }}
    />
    @if($label)
        <label for="{{ $id }}" class="ml-3 block text-sm font-bold text-gray-600 cursor-pointer">
            {{ $label }}
        </label>
    @endif
</div>
