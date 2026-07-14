@props([
    'label' => null,
    'name',
    'id' => null,
    'placeholder' => '',
    'required' => false,
    'rows' => 3,
])

<div class="space-y-2">
    @if ($label)
        <label for="{{ $id ?? $name }}" class="block text-sm font-bold text-gray-700 mb-2">
            {{ $label }}@if ($required)<span class="text-red-500 ml-0.5">*</span>@endif
        </label>
    @endif

    <div class="relative group">
        <textarea id="{{ $id ?? $name }}" name="{{ $name }}" rows="{{ $rows }}" {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => 'block w-full px-4 py-4 bg-gray-50/50 border border-gray-200 text-gray-900 focus:bg-white focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all outline-none resize-none']) }}
            placeholder="{{ $placeholder }}">{{ $slot }}</textarea>
    </div>

    <p class="mt-2 text-sm text-red-500 font-medium error-message {{ $errors->has($name) ? '' : 'hidden' }}"
        data-error-for="{{ $name }}">
        {{ $errors->first($name) }}
    </p>
</div>
