@props([
    'label' => null,
    'name',
    'options' => [],
    'icon' => null,
    'required' => false,
    'placeholder' => 'Select an option',
    'value' => null,
    'id' => null,
])

@php
    $uniqueId = 'select-' . uniqid();
    $selectedValue = old($name, $value);
    $selectId = $id ?? $name;
@endphp

<div class="space-y-2 autocomplete-select-container relative" id="{{ $uniqueId }}">
    @if ($label)
        <label class="block text-sm font-bold text-gray-700 mb-2">
            {{ $label }}@if ($required)<span class="text-red-500 ml-0.5">*</span>@endif
        </label>
    @endif

    <div class="relative group autocomplete-input-wrapper">
        {{-- Custom search & show input --}}
        @if ($icon)
            <div
                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-primary-500 transition-colors">
                <x-dynamic-component :component="$icon" class="h-5 w-5" />
            </div>
        @endif

        <input type="text"
            class="autocomplete-search block w-full {{ $icon ? 'pl-12' : 'px-4' }} pr-10 py-4 bg-gray-50/50 border border-gray-200 text-gray-900 focus:bg-white focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all outline-none font-semibold cursor-pointer"
            placeholder="{{ $placeholder }}" autocomplete="off" />

        {{-- Hidden original select to maintain absolute form/request compatibility --}}
        <select id="{{ $selectId }}" name="{{ $name }}"
            {{ $attributes->merge(['class' => 'hidden-select hidden']) }} {{ $required ? 'required' : '' }}>
            @if ($placeholder)
                <option value="" {{ empty($selectedValue) ? 'selected' : '' }}>{{ $placeholder }}</option>
            @endif

            @foreach ($options as $val => $labelOption)
                <option value="{{ $val }}" {{ $selectedValue == $val ? 'selected' : '' }}>
                    {{ $labelOption }}
                </option>
            @endforeach

            {{ $slot }}
        </select>

        {{-- Custom Chevron / Toggle icons --}}
        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-400">
            <x-heroicon-o-chevron-down class="h-5 w-5 select-chevron transition-transform duration-200" />
        </div>

        {{-- Dropdown options list --}}
        <div
            class="autocomplete-dropdown hidden absolute z-50 w-full bg-white border border-gray-100 shadow-xl max-h-60 overflow-y-auto p-1.5 rounded-xl top-full mt-1">
            {{-- Populated dynamically via JS --}}
        </div>
    </div>

    @error($name)
        <p class="mt-2 text-sm text-red-500 font-medium error-message" data-error-for="{{ $name }}">
            {{ $message }}</p>
    @else
        <p class="mt-2 text-sm text-red-500 font-medium error-message hidden" data-error-for="{{ $name }}"></p>
    @enderror
</div>

<script>
    (function() {
        const initSelect = () => {
            const containers = document.querySelectorAll('#{{ $uniqueId }}');
            containers.forEach(container => {
                if (container.dataset.selectInitialized) return;
                container.dataset.selectInitialized = 'true';

                const searchInput = container.querySelector('.autocomplete-search');
                const hiddenSelect = container.querySelector('.hidden-select');
                const dropdown = container.querySelector('.autocomplete-dropdown');
                const chevron = container.querySelector('.select-chevron');

                let allOptions = [];

                // Parse options from the select element (including slots!)
                function loadOptions() {
                    allOptions = Array.from(hiddenSelect.options).map(opt => ({
                        value: opt.value,
                        text: opt.textContent.trim(),
                        selected: opt.selected
                    }));
                }

                loadOptions();

                // Set initial input text
                const selectedOpt = allOptions.find(opt => opt.selected);
                if (selectedOpt && selectedOpt.value !== '') {
                    searchInput.value = selectedOpt.text;
                }

                // Render dropdown list
                function renderDropdown(filterText = '') {
                    dropdown.innerHTML = '';
                    const searchVal = filterText.toLowerCase();

                    const filtered = allOptions.filter(opt => {
                        // Do not render the empty placeholder option inside the searchable list
                        if (opt.value === '') return false;
                        return opt.text.toLowerCase().includes(searchVal);
                    });

                    if (filtered.length === 0) {
                        const noResults = document.createElement('div');
                        noResults.className = 'px-4 py-3 text-sm text-gray-500 font-medium italic';
                        noResults.textContent = 'No options found';
                        dropdown.appendChild(noResults);
                        return;
                    }

                    filtered.forEach(opt => {
                        const item = document.createElement('div');
                        item.className = `px-4 py-3 rounded-lg text-sm font-semibold cursor-pointer transition-all select-none hover:bg-emerald-50 hover:text-emerald-700 ${
                            hiddenSelect.value === opt.value ? 'bg-emerald-50 text-emerald-700' : 'text-gray-700'
                        }`;
                        item.textContent = opt.text;
                        item.dataset.value = opt.value;

                        item.addEventListener('click', (e) => {
                            e.stopPropagation();
                            selectOption(opt.value, opt.text);
                            closeDropdown();
                        });

                        dropdown.appendChild(item);
                    });
                }

                function selectOption(value, text) {
                    hiddenSelect.value = value;
                    searchInput.value = text;

                    // Dispatch standard change event for Alpine/jQuery/AJAX handlers
                    hiddenSelect.dispatchEvent(new Event('change', {
                        bubbles: true
                    }));
                }

                function openDropdown() {
                    loadOptions();
                    renderDropdown(searchInput.value === selectedText() ? '' : searchInput.value);

                    // Dynamic viewport collision check
                    const rect = searchInput.getBoundingClientRect();
                    const viewportHeight = window.innerHeight;
                    const spaceBelow = viewportHeight - rect.bottom;
                    const dropdownHeight = 250; // threshold height

                    if (spaceBelow < dropdownHeight && rect.top > dropdownHeight) {
                        dropdown.classList.remove('top-full', 'mt-1');
                        dropdown.classList.add('bottom-full', 'mb-1');
                    } else {
                        dropdown.classList.remove('bottom-full', 'mb-1');
                        dropdown.classList.add('top-full', 'mt-1');
                    }

                    dropdown.classList.remove('hidden');
                    if (chevron) chevron.classList.add('rotate-180');
                }

                function closeDropdown() {
                    setTimeout(() => {
                        dropdown.classList.add('hidden');
                        if (chevron) chevron.classList.remove('rotate-180');
                        // If the user typed search text but didn't select, revert input value to selected text
                        const currentText = selectedText();
                        searchInput.value = currentText;
                    }, 150);
                }

                function selectedText() {
                    const selectedOpt = Array.from(hiddenSelect.options).find(opt => opt.value === hiddenSelect.value);
                    return selectedOpt && selectedOpt.value !== '' ? selectedOpt.textContent.trim() : '';
                }

                // Toggle dropdown on click / focus
                searchInput.addEventListener('focus', openDropdown);
                searchInput.addEventListener('click', (e) => {
                    e.stopPropagation();
                    openDropdown();
                });

                searchInput.addEventListener('input', () => {
                    renderDropdown(searchInput.value);
                });

                // Close dropdown on outside click
                document.addEventListener('click', (e) => {
                    if (!container.contains(e.target)) {
                        closeDropdown();
                    }
                });

                // Observe changes to the underlying select in case it's changed by external JavaScript
                const observer = new MutationObserver(() => {
                    loadOptions();
                    const currentText = selectedText();
                    if (searchInput.value !== currentText) {
                        searchInput.value = currentText;
                    }
                });

                observer.observe(hiddenSelect, {
                    childList: true,
                    subtree: true,
                    attributes: true
                });

                // Track original select value programmatically in case it gets modified externally
                const descriptor = Object.getOwnPropertyDescriptor(HTMLSelectElement.prototype, 'value');
                if (descriptor) {
                    try {
                        Object.defineProperty(hiddenSelect, 'value', {
                            get: descriptor.get,
                            set: function(val) {
                                descriptor.set.call(this, val);
                                loadOptions();
                                searchInput.value = selectedText();
                            },
                            configurable: true,
                            enumerable: true
                        });
                    } catch (e) {
                        console.warn('Could not redefine select value property:', e);
                    }
                }
            });
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initSelect);
        } else {
            initSelect();
        }
    })();
</script>

<style>
    /* Smooth Autocomplete Dropdown Transitions */
    .autocomplete-dropdown {
        backdrop-filter: blur(12px);
        background-color: rgba(255, 255, 255, 0.98);
    }

    .autocomplete-search:focus {
        background-color: #ffffff !important;
        border-color: #57DE6B !important;
        box-shadow: 0 0 0 4px rgba(87, 222, 107, 0.1) !important;
    }

    .autocomplete-dropdown::-webkit-scrollbar {
        width: 6px;
    }

    .autocomplete-dropdown::-webkit-scrollbar-track {
        background: transparent;
    }

    .autocomplete-dropdown::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 9999px;
    }

    .autocomplete-dropdown::-webkit-scrollbar-thumb:hover {
        background: #cbd5e1;
    }
</style>
