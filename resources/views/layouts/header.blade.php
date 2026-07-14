<header
    class="app-header transition-content sticky top-0 z-35 xl:z-35 flex h-16 shrink-0 items-center justify-between bg-white/80 px-(--margin-x) backdrop-blur-sm backdrop-saturate-150">
    <!-- Left: Mobile Sidebar Toggle -->
    <button @click="isSidebarOpen = !isSidebarOpen"
        class="xl:hidden p-2 -ml-2 rounded-lg hover:bg-gray-100 transition-colors text-gray-600">
        <x-heroicon-o-bars-3 class="h-6 w-6" />
    </button>

    <!-- Right: Search and Profile -->
    {{-- <div
        class="hidden sm:flex items-center gap-2 h-9 w-64 justify-between rounded-full border border-gray-200 bg-gray-50/50 px-4 text-sm text-gray-400 focus-within:border-primary-400 focus-within:ring-1 focus-within:ring-primary-400 transition-all">
        <div class="flex items-center gap-2">
            <x-heroicon-o-magnifying-glass class="h-4 w-4" />
            <span>Search here...</span>
        </div>
        <span class="text-xs font-mono bg-gray-200 px-1.5 py-0.5 rounded">/</span>
    </div> --}}

    <div class="flex items-center gap-1 ml-auto">
        <button class="sm:hidden p-2 rounded-full hover:bg-gray-100 transition-colors text-gray-500">
            <x-heroicon-o-magnifying-glass class="h-6 w-6" />
        </button>

        <!-- Profile Dropdown -->
        <div class="relative ml-2" x-data="{ open: false }">
            <button @click="open = !open" class="focus:outline-none transition-transform active:scale-95">
                <div
                    class="h-9 w-9 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-bold overflow-hidden border-2 border-white ring-1 ring-gray-700">
                    <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=57DE6B&color=404040"
                        alt="Profile">
                </div>
            </button>

            <!-- Dropdown Menu -->
            <x-ui.user-dropdown align="right" />
        </div>
    </div>
</header>
