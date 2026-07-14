{{--
    ┌──────────────────────────────────────────────────────────────────┐
    │  <x-ui.datatable>  — Reusable Server-Side DataTable Component    │
    │                                                                  │
    │  USAGE:                                                          │
    │  <x-ui.datatable                                                 │
    │      id="my-table"                                               │
    │      :url="route('api.endpoint')"                                │
    │      title="My Table"                                            │
    │      :perPage="10"                                               │
    │      responseDataPath="data.data"                                │
    │      responsePaginationPath="data.pagination.pagination"         │
    │      :columns="[                                                 │
    │          ['data' => 'name',  'title' => 'Name'],                 │
    │          ['data' => 'email', 'title' => 'Email'],                │
    │          ['data' => 'role',  'title' => 'Role', 'orderable' => false], │
    │      ]"                                                          │
    │  />                                                              │
    └──────────────────────────────────────────────────────────────────┘
--}}

@props([
    'id'      => 'dt-' . Str::random(8),
    'url'     => '',
    'columns' => [],
    'title'   => null,
    'perPage' => 10,
    'responseDataPath' => null,
    'responsePaginationPath' => null,
    'actions' => ['view', 'edit', 'delete'],
])

@php
    $tid = $id;                             // table element id
    $cid = $id . '-card';                   // card wrapper id
    $colsJson = json_encode(array_values($columns));
    $jsDataPath = $responseDataPath ? "'{$responseDataPath}'" : 'null';
    $jsPagPath = $responsePaginationPath ? "'{$responsePaginationPath}'" : 'null';
    $actionsJson = json_encode($actions);
@endphp

{{-- ── Card — same structure as x-ui.card ──────────────────────────────── --}}
<x-ui.card padding="p-0" id="{{ $cid }}" class="overflow-hidden">

    {{-- ── Toolbar ─────────────────────────────────────────────────────── --}}
    <div class="px-5 py-4">
        <div class="flex flex-wrap items-center justify-between gap-4">

            {{-- Left: title + per-page --}}
            <div class="flex items-center gap-4">
                @if(false) <!-- $title -->
                    <h3 class="text-base font-bold text-gray-900">{{ $title }}</h3>
                    <div class="h-5 w-px bg-gray-200"></div>
                @endif
                <div class="flex items-center gap-2 text-xs text-gray-500">
                    <span class="font-medium">Show</span>
                    <select id="{{ $tid }}-per-page"
                        class="bg-gray-50/50 border border-gray-200 text-gray-700 text-xs font-medium
                               px-2 py-1.5 pr-7 appearance-none cursor-pointer
                               focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-400 transition"
                        style="background-image:url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E&quot;);background-repeat:no-repeat;background-position:right .4rem center;background-size:.75rem">
                        <option value="5"  {{ $perPage == 5  ? 'selected' : '' }}>5</option>
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    <span class="font-medium">entries</span>
                </div>
            </div>

            {{-- Right: search --}}
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-gray-400 group-focus-within:text-primary-500 transition-colors">
                    <x-heroicon-o-magnifying-glass class="w-3.5 h-3.5" />
                </div>
                <input id="{{ $tid }}-search" type="search" placeholder="Search…"
                    class="w-48 sm:w-60 pl-9 pr-3 py-2 text-sm text-gray-900 bg-gray-50/50 border border-gray-200
                           placeholder-gray-400
                           focus:outline-none focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-400
                           transition" />
            </div>
        </div>
    </div>

    {{-- ── Table area ───────────────────────────────────────────────────── --}}
    <div class="relative border-t border-gray-100">

        {{-- Loading overlay --}}
        <div id="{{ $tid }}-loading"
             class="absolute inset-0 z-20 bg-white/70 backdrop-blur-[1px] items-center justify-center hidden">
            <div class="flex flex-col items-center gap-2">
                <svg class="w-7 h-7 animate-spin text-primary-500" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor"
                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                </svg>
                <span class="text-xs font-medium text-gray-400">Loading…</span>
            </div>
        </div>

        {{-- The actual table --}}
        <div class="overflow-x-auto">
            <table id="{{ $tid }}" class="w-full text-sm text-left">
                <thead>
                    <tr>
                        @foreach($columns as $col)
                            @php
                                $isOrderable = true;
                                if (isset($col['enableSorting'])) {
                                    $isOrderable = filter_var($col['enableSorting'], FILTER_VALIDATE_BOOLEAN);
                                } elseif (isset($col['orderable'])) {
                                    $isOrderable = filter_var($col['orderable'], FILTER_VALIDATE_BOOLEAN);
                                }
                            @endphp
                            <th class="text-xs font-bold text-gray-700 uppercase tracking-wider
                                       whitespace-nowrap select-none bg-gray-50 {{ $isOrderable ? 'cursor-pointer' : 'cursor-default pointer-events-none' }}">
                                <div class="flex items-center gap-1.5">
                                    <span>{{ $col['header'] ?? ($col['title'] ?? ($col['accessorKey'] ?? ($col['data'] ?? ''))) }}</span>
                                    @if($isOrderable)
                                        <svg class="w-3 h-3 text-gray-300 sort-icon transition-colors"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                        </svg>
                                    @endif
                                </div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="text-gray-600">
                    {{-- DataTables injects rows here --}}
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── Footer ───────────────────────────────────────────────────────── --}}
    <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-3.5 border-t border-gray-100">
        <p id="{{ $tid }}-info" class="text-xs text-gray-400 font-medium"></p>
        <nav id="{{ $tid }}-pag" class="flex items-center gap-1" aria-label="Pagination"></nav>
    </div>
</x-ui.card>

{{-- ── Scoped Styles ────────────────────────────────────────────────────── --}}
<style>
    /* DataTables wrapper reset */
    #{{ $cid }} .dataTables_wrapper { display: contents; }

    /* Table structure */
    #{{ $tid }} { width: 100% !important; margin: 0 !important; border-collapse: collapse !important; }
    #{{ $tid }} thead th { padding: 0 !important; border-bottom: 1px solid var(--color-gray-100) !important; }
    #{{ $tid }} thead th > div { padding: 0.875rem 1.25rem; }

    /* Body rows */
    #{{ $tid }} tbody td {
        padding: 0.875rem 1.25rem !important;
        vertical-align: middle !important;
        border-bottom: 1px solid var(--color-gray-50) !important;
    }
    #{{ $tid }} tbody tr:last-child td { border-bottom: none !important; }
    #{{ $tid }} tbody tr { transition: background 0.15s ease; }
    #{{ $tid }} tbody tr:hover td { background: var(--color-gray-50) !important; }

    /* Hide DataTables default sorting indicators */
    #{{ $tid }} thead th::before,
    #{{ $tid }} thead th::after {
        display: none !important;
        content: "" !important;
    }

    /* Sort state colours */
    #{{ $tid }} thead th.sorting       .sort-icon { color: #d1d5db; }
    #{{ $tid }} thead th.sorting_asc   .sort-icon { color: var(--color-primary-500); }
    #{{ $tid }} thead th.sorting_desc  .sort-icon { color: var(--color-primary-500); transform: scaleY(-1); display: inline-block; }

    /* Loading overlay active state */
    #{{ $tid }}-loading.active { display: flex; }

    /* Right-align last column (Actions) */
    #{{ $tid }} thead th:last-child { text-align: right !important; }
    #{{ $tid }} thead th:last-child > div { justify-content: flex-end !important; }
    #{{ $tid }} tbody td:last-child { text-align: right !important; }
</style>

{{-- ── Pre-rendered Buttons for JS usage ─────────────────────────────── --}}
@php
    $renderBtn = function($variant, $action, $title, $icon) {
        $html = \Illuminate\Support\Facades\Blade::render("
            <x-button variant='{$variant}' size='sm' data-id='__ID__' data-action='{$action}' title='{$title}' icon-start='{$icon}'>
                " . ucfirst($action) . "
            </x-button>
        ");
        // Strip script tags to prevent breaking JS execution in browser
        $html = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $html);
        // Escape backticks so they don't break JS template literals
        return str_replace('`', '\\`', $html);
    };

    $btnView   = $renderBtn('primary', 'view', 'View', 'heroicon-o-eye');
    $btnEdit   = $renderBtn('secondary-outline', 'edit', 'Edit', 'heroicon-o-pencil-square');
    $btnDelete = $renderBtn('danger', 'delete', 'Delete', 'heroicon-o-trash');
@endphp

{{-- ── Script ───────────────────────────────────────────────────────────── --}}
<script>
(function () {
    window.onReady(init);



    function init() {
        const tid  = '{{ $tid }}';
        const columns = {!! $colsJson !!};
        const $loading = $('#' + tid + '-loading');
        const $info    = $('#' + tid + '-info');
        const $pag     = $('#' + tid + '-pag');

        // ── Renderers Mapping (TanStack Style) ──────────────────────────
        const renderers = {
            GlobalIdCell: (data, type, row, meta) => `
                <span class="text-xs font-bold text-gray-400 font-mono tracking-tight">
                    #${meta.row + 1 + meta.settings._iDisplayStart}
                </span>`,

            GlobalTextCell: (data) => {
                const displayVal = (data === null || data === undefined || String(data).trim() === '') ? '-' : data;
                return `<span class="font-semibold text-gray-700 line-clamp-2 max-w-xs leading-normal">${displayVal}</span>`;
            },

            GlobalDateTimeCell: (data) => {
                if (!data) return '<span class="text-gray-300">—</span>';
                const date = new Date(data);
                return `
                    <div class="flex flex-col leading-tight">
                        <span class="text-xs font-semibold text-gray-700">${date.toLocaleDateString()}</span>
                        <span class="text-[10px] text-gray-400 font-medium">${date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>
                    </div>`;
            },

            StatusCell: (data) => {
                const isActive = data === true || data === 1 || data === 'Active' || data === '1';
                const cls = isActive
                    ? 'text-primary-700 bg-primary-50'
                    : 'text-gray-500 bg-gray-100';
                return `<span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold ${cls}">${isActive ? 'Active' : 'Inactive'}</span>`;
            },

            SwitchCell: (data, type, row) => {
                let checked = (data === 1 || data === '1' || data === true) ? 'checked' : '';
                return `
                    <div class="flex items-center">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer toggle-status" data-id="${row.id}" ${checked}>
                            <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer 
                                        peer-checked:after:translate-x-full peer-checked:after:border-white 
                                        after:content-[''] after:absolute after:top-[2px] after:left-[2px] 
                                        after:bg-white after:border-gray-300 after:border after:rounded-full 
                                        after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                        </label>
                    </div>
                `;
            },
 
            RowActions: (data, type, row) => {
                const actions = {!! $actionsJson !!};
                let html = '<div class="flex items-center justify-end gap-2">';
                
                if (actions.includes('view')) {
                    html += `{!! $btnView !!}`.replace(/__ID__/g, row.id);
                }
                
                if (actions.includes('edit')) {
                    html += `{!! $btnEdit !!}`.replace(/__ID__/g, row.id);
                }
                
                if (actions.includes('delete')) {
                    html += `{!! $btnDelete !!}`.replace(/__ID__/g, row.id);
                }
                
                html += '</div>';
                return html;
            },
        
            ImageViewCell: (data) => {
                if (!data) return '<span class="text-gray-300">—</span>';
                return `
                    <a
                        href="${data}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-block hover:opacity-90 transition-all"
                    >
                        <img
                            src="${data}"
                            alt="Preview"
                            class="w-48 h-48 rounded-lg border border-gray-200 object-cover shadow-sm cursor-pointer"
                        />
                    </a>
                `;
            },
        
            toggle: (data) => renderers.StatusCell(data),
            user: (data, type, row) => `
                <div class="flex items-center gap-3">
                    <img src="${row.avatar || row.profileUrl}" class="h-9 w-9 rounded-full border border-gray-200 shadow-sm object-cover" />
                    <span class="font-semibold text-gray-900 tracking-tight">${data}</span>
                </div>`,
            role: (data) => `
                <span class="px-2.5 py-1 text-[11px] font-bold rounded-lg border text-slate-600 bg-slate-50 border-slate-200 uppercase tracking-tight">${data}</span>`,
            actions: (data, type, row) => renderers.RowActions(data, type, row)
        };

        // Expose RowActions globally so custom page column renderers can reuse them
        window.defaultRowActions = renderers.RowActions;

        const dtCols = columns.map(col => {
            const dataField = col.accessorKey || col.data;
            const titleField = col.header || col.title;
            const isOrderable = col.enableSorting !== undefined ? col.enableSorting : (col.orderable !== undefined ? col.orderable : true);
            const renderer = col.cell || col.render;

            return {
                data: dataField,
                title: titleField,
                orderable: isOrderable,
                render: typeof renderer === 'string' ? (window[renderer] || renderers[renderer] || null) : renderer
            };
        });

        const dt = $('#' + tid).DataTable({
            serverSide : true,
            processing : false,
            dom        : 't',
            pageLength : {{ $perPage }},
            order      : [],
            ajax: {
                url         : '{{ $url }}',
                type        : 'GET',
                data        : function (d) {
                    if (window.getDtFilters && typeof window.getDtFilters === 'function') {
                        Object.assign(d, window.getDtFilters());
                    }
                },
                beforeSend  : function () { $loading.addClass('active'); },
                complete    : function () { $loading.removeClass('active'); },
                dataFilter : function (raw) {
                    const dataPath = {!! $jsDataPath !!};
                    const pagPath  = {!! $jsPagPath !!};
                    if (!dataPath && !pagPath) return raw;

                    const json = JSON.parse(raw);
                    function resolve(obj, path) {
                        return path.split('.').reduce((o, k) => (o && o[k] !== undefined ? o[k] : null), obj);
                    }

                    const records = resolve(json, dataPath);
                    const pag = resolve(json, pagPath);

                    return JSON.stringify({
                        draw: json.draw ?? json.data?.draw ?? 1,
                        data: records ?? [],
                        recordsTotal: pag ? (pag.recordsTotal ?? pag.total ?? 0) : 0,
                        recordsFiltered: pag ? (pag.total ?? 0) : 0,
                    });
                }
            },
            columns    : dtCols,
            createdRow : function (row, data, dataIndex) {
                $(row).addClass('group transition-colors');
            },
            language   : { 
                emptyTable: `
                    <div class="flex flex-col items-center justify-center py-16 text-center w-full">
                        <div class="p-3 bg-primary-50 rounded-2xl mb-4">
                            <svg class="w-7 h-7 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0H4"/>
                            </svg>
                        </div>
                        <p class="text-sm font-bold text-gray-700">No records found</p>
                        <p class="text-xs text-gray-400 mt-1">Try adjusting your search or filters</p>
                    </div>`,
                zeroRecords: `
                    <div class="flex flex-col items-center justify-center py-16 text-center w-full">
                        <div class="p-3 bg-primary-50 rounded-2xl mb-4">
                            <svg class="w-7 h-7 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <p class="text-sm font-bold text-gray-700">No matching records found</p>
                    </div>`
            },

            drawCallback: function () {
                const api  = this.api();
                const info = api.page.info();
                const empty = info.recordsDisplay === 0;

                // Info text
                $info.html(empty
                    ? 'No records to display'
                    : `Showing <strong class="text-gray-700">${info.start + 1}–${info.end}</strong>
                       of <strong class="text-gray-700">${info.recordsTotal}</strong> records`
                );

                // Rebuild pagination
                buildPagination(info, $pag, dt);
            },
        });

        window.LaravelDataTables = window.LaravelDataTables || {};
        window.LaravelDataTables[tid] = dt;

        // ── Search (debounced) ─────────────────────────────────────────
        let searchTimer;
        $('#' + tid + '-search').on('input', function () {
            clearTimeout(searchTimer);
            const v = this.value;
            searchTimer = setTimeout(() => dt.search(v).draw(), 380);
        });

        // ── Per-page ───────────────────────────────────────────────────
        $('#' + tid + '-per-page').on('change', function () {
            dt.page.len(+this.value).draw();
        });

        // ── Pagination clicks ──────────────────────────────────────────
        $(document).on('click', '#' + tid + '-pag [data-p]', function () {
            const v = $(this).data('p');
            if      (v === 'prev') dt.page('previous').draw('page');
            else if (v === 'next') dt.page('next').draw('page');
            else                   dt.page(+v).draw('page');
        });
    }

    // ── Pagination builder ─────────────────────────────────────────────
    function buildPagination(info, $c, dt) {
        $c.empty();
        if (info.pages <= 1) return;

        const cur = info.page, tot = info.pages;

        function mkBtn(label, action, active, disabled) {
            const base    = 'inline-flex items-center justify-center h-8 min-w-[2rem] px-2 rounded-lg text-xs font-semibold transition select-none';
            const variant = active
                ? 'bg-primary-500 text-white shadow-sm pointer-events-none'
                : disabled
                    ? 'text-gray-300 pointer-events-none'
                    : 'text-gray-500 hover:bg-gray-100 hover:text-gray-800 cursor-pointer';
            const attr = (!active && !disabled) ? `data-p="${action}"` : '';
            return `<button class="${base} ${variant}" ${attr}>${label}</button>`;
        }

        const chevL = `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>`;
        const chevR = `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>`;
        const ellip = `<span class="inline-flex items-center h-8 px-1 text-xs text-gray-400">…</span>`;

        $c.append(mkBtn(chevL, 'prev', false, cur === 0));

        // Windowed page numbers
        const W = 5;
        let s = Math.max(0, cur - Math.floor(W / 2));
        let e = Math.min(tot - 1, s + W - 1);
        if (e - s < W - 1) s = Math.max(0, e - W + 1);

        if (s > 0) { $c.append(mkBtn(1, 0, false, false)); if (s > 1) $c.append(ellip); }
        for (let i = s; i <= e; i++) $c.append(mkBtn(i + 1, i, i === cur, false));
        if (e < tot - 1) { if (e < tot - 2) $c.append(ellip); $c.append(mkBtn(tot, tot - 1, false, false)); }

        $c.append(mkBtn(chevR, 'next', false, cur === tot - 1));
    }
})();
</script>
