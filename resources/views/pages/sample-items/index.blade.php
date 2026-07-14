@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <x-ui.page-header title="Sample Items" description="A sample page showcasing the DataTable, status toggles, and layout boilerplate.">
            <x-slot name="actions">
                <x-button href="{{ route('pages.sample-items.create') }}" variant="primary" size="md" icon-start="heroicon-o-plus">
                    Add Sample Item
                </x-button>
            </x-slot>
        </x-ui.page-header>

        {{-- Sample Items DataTable --}}
        <x-ui.datatable 
            id="sample-items-dt" 
            :url="route('pages.sample-items')" 
            title="Sample Items List" 
            :perPage="10"
            responseDataPath="data.data"
            responsePaginationPath="data.pagination"
            :actions="['edit', 'delete']"
            :columns="[
                ['accessorKey' => 'id', 'header' => 'ID', 'cell' => 'GlobalIdCell', 'enableSorting' => false],
                ['accessorKey' => 'name', 'header' => 'Name', 'cell' => 'GlobalTextCell', 'enableSorting' => true],
                ['accessorKey' => 'description', 'header' => 'Description', 'cell' => 'GlobalTextCell', 'enableSorting' => false],
                ['accessorKey' => 'status', 'header' => 'Status', 'cell' => 'SwitchCell', 'enableSorting' => true],
                ['accessorKey' => 'created_at', 'header' => 'Created Date', 'cell' => 'GlobalDateTimeCell', 'enableSorting' => true],
                ['accessorKey' => 'actions', 'header' => 'Actions', 'cell' => 'RowActions', 'enableSorting' => false],
            ]" 
        />
    </div>
@endsection

@push('scripts')
    <script>

        window.onReady(() => {
            const dtId = 'sample-items-dt';

            // Handle edit button redirection
            $(document).on('click', '[data-action="edit"]', function() {
                const id = $(this).attr('data-id') || $(this).data('id');
                window.location.href = `{{ route('pages.sample-items.edit', ['id' => ':id']) }}`.replace(':id', id);
            });

            // Handle status toggle change via AJAX
            $(document).on('change', '.toggle-status', function() {
                const id = $(this).attr('data-id') || $(this).data('id');
                const $chk = $(this);
                $chk.prop('disabled', true);

                $.ajax({
                    url: `{{ route('pages.sample-items.toggle', ['id' => ':id']) }}`.replace(':id', id),
                    method: 'PATCH',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        $chk.prop('disabled', false);
                        window.toast.success(response.message || 'Status updated successfully!');
                    },
                    error: function(xhr) {
                        $chk.prop('disabled', false);
                        $chk.prop('checked', !$chk.prop('checked'));
                        window.toast.error(xhr.responseJSON?.message || 'Error updating status');
                    }
                });
            });

            // Handle delete action with sweetalert / confirm dialog
            $(document).on('click', '[data-action="delete"]', function() {
                const id = $(this).attr('data-id') || $(this).data('id');
                confirmDelete({
                    submit: function() {
                        $.ajax({
                            url: `{{ route('pages.sample-items.destroy', ['id' => ':id']) }}`.replace(':id', id),
                            method: 'DELETE',
                            data: { _token: '{{ csrf_token() }}' },
                            success: function(response) {
                                window.toast.success(response.message || 'Item deleted successfully!');

                                if (window.LaravelDataTables && window.LaravelDataTables[dtId]) {
                                    window.LaravelDataTables[dtId].ajax.reload();
                                } else {
                                    location.reload();
                                }
                            },
                            error: function(xhr) {
                                window.toast.error(xhr.responseJSON?.message || 'Error deleting item');
                            }
                        });
                    }
                }, 'Delete Sample Item?', 'Are you sure you want to delete this item? This action is permanent.');
            });
        });
    </script>
@endpush
