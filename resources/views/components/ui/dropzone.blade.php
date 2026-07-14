@props([
    'label' => null,
    'name' => 'files[]',
    'id' => 'drop-zone',
    'inputId' => 'file-input',
    'multiple' => true,
    'maxSize' => '10MB',
    'accept' => '*',
    'required' => false,
])

<div class="space-y-4">
    @if ($label)
        <label class="block text-sm font-bold text-gray-700 mb-2">
            {{ $label }}@if ($required)<span class="text-red-500 ml-0.5">*</span>@endif
        </label>
    @endif

    <label for="{{ $inputId }}"
        {{ $attributes->merge(['class' => 'relative block border border-dashed p-10 text-center hover:border-primary-500 hover:bg-primary-50/10 transition-all cursor-pointer group']) }}
        id="{{ $id }}">

        <input type="file" name="{{ $name }}" id="{{ $inputId }}" {{ $multiple ? 'multiple' : '' }}
            accept="{{ $accept }}" class="hidden">

        <div
            class="h-16 w-16 bg-gray-50 border border-dashed rounded-2xl flex items-center justify-center text-gray-400 mx-auto mb-4 group-hover:border-brand-primary group-hover:text-primary-600 transition-colors">
            <x-heroicon-o-cloud-arrow-up class="h-8 w-8" />
        </div>

        <h4 class="text-gray-900 font-bold text-lg">Click to upload or drag and drop</h4>
        <p class="text-sm text-gray-500 font-semibold mt-1">Maximum file size: {{ $maxSize }}</p>
    </label>
    <div id="{{ $id }}-list" class="space-y-2 max-h-48 custom-scrollbar"></div>

    <p class="mt-2 text-sm text-red-500 font-medium error-message {{ $errors->has($name) ? '' : 'hidden' }}"
        data-error-for="{{ $name }}">
        {{ $errors->first($name) }}
    </p>
</div>

@push('scripts')
    <script>
        onReady(function() {
            const dropZone = $('#{{ $id }}');
            const fileInput = $('#{{ $inputId }}');
            const fileList = $('#{{ $id }}-list');

            const acceptValue = '{{ $accept }}';
            const allowedExtensions = acceptValue !== '*' ?
                acceptValue.toLowerCase().split(',').map(ext => ext.trim()) : [];

            function validateFiles(filesList) {
                if (allowedExtensions.length === 0) return true;

                for (let i = 0; i < filesList.length; i++) {
                    const fileName = filesList[i].name.toLowerCase();
                    const hasValidExtension = allowedExtensions.some(ext => fileName.endsWith(ext));
                    if (!hasValidExtension) {
                        if (window.toast) {
                            window.toast.error(
                                `File "${filesList[i].name}" is not allowed. Only ${acceptValue} files are accepted.`
                            );
                        } else {
                            alert(
                                `File "${filesList[i].name}" is not allowed. Only ${acceptValue} files are accepted.`
                            );
                        }
                        return false;
                    }
                }
                return true;
            }

            fileInput.on('change', function() {
                const files = this.files;

                if (files.length > 0 && !validateFiles(files)) {
                    this.value = '';
                    fileList.empty().removeClass('p-4 bg-gray-50 rounded-2xl border border-gray-100');
                    return;
                }

                fileList.empty();
                if (files.length > 0) {
                    fileList.addClass('p-4 bg-gray-50 rounded-2xl border border-gray-100');
                    for (let i = 0; i < files.length; i++) {
                        const size = (files[i].size / 1024 / 1024).toFixed(2);
                        fileList.append(`
                            <div class="text-sm text-gray-700 flex items-center justify-between p-2 hover:bg-white rounded-xl transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 bg-white rounded-lg flex items-center justify-center border border-gray-100 shadow-sm">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    </div>
                                    <span class="font-bold truncate max-w-[200px]">${files[i].name}</span>
                                </div>
                                <span class="text-xs font-extrabold text-gray-400 uppercase tracking-tighter">${size} MB</span>
                            </div>
                        `);
                    }
                } else {
                    fileList.removeClass('p-4 bg-gray-50 rounded-2xl border border-gray-100');
                }
            });

            // Drag and Drop Logic
            dropZone.on('dragover', function(e) {
                e.preventDefault();
                $(this).addClass('border-primary-500 bg-primary-50/20');
            });

            dropZone.on('dragleave', function(e) {
                e.preventDefault();
                $(this).removeClass('border-primary-500 bg-primary-50/20');
            });

            dropZone.on('drop', function(e) {
                e.preventDefault();
                $(this).removeClass('border-primary-500 bg-primary-50/20');
                const files = e.originalEvent.dataTransfer.files;

                if (files.length > 0 && !validateFiles(files)) {
                    return;
                }

                fileInput[0].files = files;
                fileInput.trigger('change');
            });
        });
    </script>
@endpush
