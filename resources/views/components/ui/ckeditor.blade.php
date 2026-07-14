@props([
    'label' => null,
    'name',
    'id' => null,
    'placeholder' => '',
    'required' => false,
    'value' => null,
    'editorHeight' => '350px',
    'autoInit' => true, // set to false inside Alpine x-for loops to prevent double-init
])

@php
    $id = $id ?? $name;
@endphp

<div class="space-y-2 ckeditor-container" style="--editor-min-height: {{ $editorHeight }};">
    @if ($label)
        <label for="{{ $id }}" class="block text-sm font-bold text-gray-700 mb-2">
            {{ $label }}@if ($required)<span class="text-red-500 ml-0.5">*</span>@endif
        </label>
    @endif

    <div class="relative group ckeditor-editor-wrapper">
        <textarea id="{{ $id }}" name="{{ $name }}" {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => 'hidden']) }} placeholder="{{ $placeholder }}">{!! old($name, $value) !!}</textarea>
    </div>

    <p class="mt-2 text-sm text-red-500 font-medium error-message {{ $errors->has($name) ? '' : 'hidden' }}"
        data-error-for="{{ $name }}">
        {{ $errors->first($name) }}
    </p>
</div>

@if ($autoInit)
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.ClassicEditor) {
                window.ClassicEditor.create(document.querySelector('#{{ $id }}'), {
                    placeholder: '{{ $placeholder }}'
                }).then(editor => {
                    // Keep the textarea value updated in real-time for jQuery/FormData
                    editor.model.document.on('change:data', () => {
                        document.querySelector('#{{ $id }}').value = editor.getData();
                    });

                    // Track editor instance if needed
                    if (!window.ckeditors) {
                        window.ckeditors = {};
                    }
                    window.ckeditors['{{ $name }}'] = editor;
                }).catch(error => {
                    console.error('CKEditor initialization failed:', error);
                });
            }
        });
    </script>
@endif

<style>
    /* Premium Styling for the Quill editor matching our Tailwind theme */
    .ckeditor-editor-wrapper .ql-container {
        border-bottom-left-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
        background-color: #faf9f7 !important;
        border-color: #e9e5db !important;
        font-family: inherit;
        font-size: inherit;
        color: #1a1a1a;
        transition: background-color 0.2s, border-color 0.2s, box-shadow 0.2s;
    }

    .ckeditor-editor-wrapper .ql-editor {
        min-height: var(--editor-min-height) !important;
        padding-left: 1.25rem !important;
        padding-right: 1.25rem !important;
    }

    .ckeditor-editor-wrapper .ql-editor.ql-blank::before {
        font-style: normal;
        color: #9ca3af;
        left: 1.25rem;
        right: 1.25rem;
    }

    .ckeditor-editor-wrapper:focus-within .ql-container {
        background-color: #ffffff !important;
        border-color: #57DE6B !important;
        box-shadow: 0 0 0 4px rgba(87, 222, 107, 0.1) !important;
    }

    .ckeditor-editor-wrapper .ql-toolbar {
        border-top-left-radius: 0 !important;
        border-top-right-radius: 0 !important;
        background-color: #ffffff !important;
        border-color: #e9e5db !important;
        border-bottom: 0 !important;
        padding: 0.5rem !important;
    }

    /* Error state styles */
    .ckeditor-editor-wrapper.has-error .ql-container {
        background-color: #fef2f2 !important;
        border-color: #ef4444 !important;
    }

    .ckeditor-editor-wrapper.has-error:focus-within .ql-container {
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1) !important;
    }
</style>
