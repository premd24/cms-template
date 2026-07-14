@extends('layouts.app')

@php
    $isEdit = isset($item) && $item !== null;
    $formAction = $isEdit ? route('pages.sample-items.update', $item->id) : route('pages.sample-items.store');
    $pageTitle = $isEdit ? 'Edit Sample Item' : 'Create Sample Item';
    $pageDesc = $isEdit ? 'Modify the details of this sample item.' : 'Add a new sample item to the boilerplate.';
    $submitBtnText = $isEdit ? 'Update Item' : 'Create Item';
@endphp

@section('content')
    <div class="space-y-8">
        <x-ui.page-header :title="$pageTitle" :description="$pageDesc">
            <x-slot name="actions">
                <x-button href="{{ route('pages.sample-items') }}" variant="secondary-outline" size="md" icon-start="heroicon-o-arrow-left">
                    Back to List
                </x-button>
            </x-slot>
        </x-ui.page-header>

        <x-ui.card>
            <form id="sample-item-form" action="{{ $formAction }}" method="POST" class="space-y-6">
                @csrf
                @if ($isEdit)
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Item Name Input --}}
                    <x-ui.input 
                        label="Item Name" 
                        name="name" 
                        :value="old('name', $item->name ?? '')"
                        placeholder="e.g. Starter Pack A" 
                        required 
                    />

                    {{-- Item Status Select --}}
                    <x-ui.select 
                        label="Status" 
                        name="status" 
                        :options="['active' => 'Active', 'inactive' => 'Inactive']"
                        :value="old('status', $item->status ?? 'active')"
                        placeholder="Select status..." 
                        required 
                    />
                </div>

                {{-- Item Description Input --}}
                <x-ui.textarea 
                    label="Description" 
                    name="description" 
                    placeholder="Enter item description..." 
                    rows="4"
                >{{ old('description', $item->description ?? '') }}</x-ui.textarea>

                <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100">
                    <x-button type="button" href="{{ route('pages.sample-items') }}" variant="secondary-outline">
                        Discard Changes
                    </x-button>
                    <x-button type="submit" variant="primary">
                        {{ $submitBtnText }}
                    </x-button>
                </div>
            </form>
        </x-ui.card>
    </div>
@endsection

@push('scripts')
    <script>
        window.onReady(() => {
            const $form = $('#sample-item-form');

            $form.on('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const $btn = $form.find('button[type="submit"]');

                window.setBtnLoading($btn, true);

                $.ajax({
                    url: $form.attr('action'),
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json'
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        window.setBtnLoading($btn, false);
                        window.toast.success(response.message || 'Saved successfully!');
                        setTimeout(() => {
                            window.location.href = '{{ route('pages.sample-items') }}';
                        }, 1200);
                    },
                    error: function(xhr) {
                        window.setBtnLoading($btn, false);
                        
                        // Clear existing errors
                        $form.find('.error-message').addClass('hidden').text('');
                        $form.find('input, textarea, select').removeClass(
                            'bg-red-50 border-red-500 text-red-900 placeholder-red-300 focus:ring-4 focus:ring-red-500/10 focus:border-red-500 border-red-200'
                        );

                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;

                            Object.keys(errors).forEach(key => {
                                const errorMessage = errors[key][0];
                                const $field = $form.find(`[name="${key}"]`);

                                if ($field.length) {
                                    $field.addClass(
                                        'bg-red-50 border-red-500 text-red-900 placeholder-red-300 focus:ring-4 focus:ring-red-500/10 focus:border-red-500'
                                    ).removeClass(
                                        'bg-gray-50/50 border-gray-200'
                                    );

                                    // Display error message
                                    const $errorLabel = $form.find(`[data-error-for="${key}"]`);
                                    if ($errorLabel.length) {
                                        $errorLabel.text(errorMessage).removeClass('hidden');
                                    }
                                }
                            });
                        } else {
                            window.toast.error(xhr.responseJSON?.message || 'Failed to save sample item');
                        }
                    }
                });
            });
        });
    </script>
@endpush
