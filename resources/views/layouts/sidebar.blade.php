<!-- Mobile Backdrop -->
<div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-30 lg:hidden" x-cloak></div>

<!-- Sidebar -->
<aside
    class="fixed left-0 top-0 h-screen bg-white dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-800 z-40 flex flex-col transform transition-transform duration-200"
    :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen, 'lg:translate-x-0': true, 'lg:w-64': sidebarOpen, 'lg:w-16': !sidebarOpen, 'w-64': true}"
    x-cloak>
    <!-- Logo -->
    <div class="h-14 flex items-center justify-between px-4 border-b border-zinc-200 dark:border-zinc-800">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2" x-show="sidebarOpen">
            <img src="{{ asset('images/logo-bdb.png') }}" alt="Logo" class="w-7 h-7 rounded-lg object-contain">
            <span class="font-bold text-zinc-900 dark:text-white">BDB</span>
        </a>
        <button @click="toggleSidebar()" class="p-1.5 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg">
            <i data-lucide="menu" class="w-5 h-5 text-zinc-600 dark:text-zinc-400"></i>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 p-2 space-y-1 overflow-y-auto text-sm">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">
            <i data-lucide="layout-dashboard" class="w-5 h-5 flex-shrink-0"></i>
            <span x-show="sidebarOpen">Dashboard</span>
        </a>

        {{-- ========================================= --}}
        {{-- PRODUKSI (Owner, Operasional) --}}
        {{-- ========================================= --}}
        @if(auth()->user()->isOwner() || auth()->user()->isOperasional())
            <div class="pt-4" x-show="sidebarOpen">
                <p class="px-3 text-[10px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-wider">Produksi
                </p>
            </div>

            <!-- SPK -->
            <div x-data="{ open: {{ request()->routeIs('spk.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="flex items-center justify-between w-full px-3 py-2 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800">
                    <div class="flex items-center gap-3">
                        <i data-lucide="clipboard-list" class="w-5 h-5 flex-shrink-0"></i>
                        <span x-show="sidebarOpen">SPK</span>
                    </div>
                    <i data-lucide="chevron-down" class="w-4 h-4" x-show="sidebarOpen" :class="open && 'rotate-180'"></i>
                </button>
                <div x-show="open && sidebarOpen" class="ml-8 mt-1 space-y-1">
                    <a href="{{ route('spk.index') }}"
                        class="block px-3 py-1.5 rounded-lg text-xs {{ request()->routeIs('spk.index') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20' : 'text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">Daftar
                        SPK</a>
                    <a href="{{ route('spk.create') }}"
                        class="block px-3 py-1.5 rounded-lg text-xs {{ request()->routeIs('spk.create') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20' : 'text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">Buat
                        SPK</a>
                    @if(auth()->user()->isOwner())
                        <a href="{{ route('spk.pending') }}"
                            class="flex items-center px-3 py-1.5 rounded-lg text-xs {{ request()->routeIs('spk.pending') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20' : 'text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">
                            Pending
                            @if($pendingSpkCount > 0)
                                <span
                                    class="ml-auto px-1.5 text-xs bg-red-500 text-white rounded-full">{{ $pendingSpkCount }}</span>
                            @endif
                        </a>
                    @endif
                </div>
            </div>

            <!-- FPB -->
            <div x-data="{ open: {{ request()->routeIs('fpb.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="flex items-center justify-between w-full px-3 py-2 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800">
                    <div class="flex items-center gap-3">
                        <i data-lucide="file-input" class="w-5 h-5 flex-shrink-0"></i>
                        <span x-show="sidebarOpen">FPB</span>
                    </div>
                    <i data-lucide="chevron-down" class="w-4 h-4" x-show="sidebarOpen" :class="open && 'rotate-180'"></i>
                </button>
                <div x-show="open && sidebarOpen" class="ml-8 mt-1 space-y-1">
                    <a href="{{ route('fpb.index') }}"
                        class="block px-3 py-1.5 rounded-lg text-xs {{ request()->routeIs('fpb.index') && !request('status') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20' : 'text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">Daftar
                        FPB</a>
                    <a href="{{ route('fpb.create') }}"
                        class="block px-3 py-1.5 rounded-lg text-xs {{ request()->routeIs('fpb.create') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20' : 'text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">Buat
                        FPB</a>
                    @if(auth()->user()->isOwner())
                        <a href="{{ route('fpb.index', ['status' => 'pending']) }}"
                            class="flex items-center px-3 py-1.5 rounded-lg text-xs {{ request()->routeIs('fpb.index') && request('status') == 'pending' ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20' : 'text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">
                            Pending
                            @if($pendingFpbCount > 0)
                                <span
                                    class="ml-auto px-1.5 text-xs bg-red-500 text-white rounded-full">{{ $pendingFpbCount }}</span>
                            @endif
                        </a>
                    @endif
                </div>
            </div>

            <!-- Job Cost -->
            <div x-data="{ open: {{ request()->routeIs('job-costs.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="flex items-center justify-between w-full px-3 py-2 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800">
                    <div class="flex items-center gap-3">
                        <i data-lucide="scissors" class="w-5 h-5 flex-shrink-0"></i>
                        <span x-show="sidebarOpen">Job Cost</span>
                    </div>
                    <i data-lucide="chevron-down" class="w-4 h-4" x-show="sidebarOpen" :class="open && 'rotate-180'"></i>
                </button>
                <div x-show="open && sidebarOpen" class="ml-8 mt-1 space-y-1">
                    <a href="{{ route('job-costs.index') }}"
                        class="block px-3 py-1.5 rounded-lg text-xs {{ request()->routeIs('job-costs.index') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20' : 'text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">Daftar
                        JC</a>
                    <a href="{{ route('job-costs.create') }}"
                        class="block px-3 py-1.5 rounded-lg text-xs {{ request()->routeIs('job-costs.create') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20' : 'text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">Buat
                        JC</a>
                    @if(auth()->user()->isOwner())
                        <a href="{{ route('job-costs.pending') }}"
                            class="block px-3 py-1.5 rounded-lg text-xs {{ request()->routeIs('job-costs.pending') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20' : 'text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">
                            Pending Approval
                        </a>
                    @endif
                </div>
            </div>
        @endif

        {{-- ========================================= --}}
        {{-- INVENTORY (All roles) --}}
        {{-- ========================================= --}}
        <div class="pt-4" x-show="sidebarOpen">
            <p class="px-3 text-[10px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-wider">
                Inventory</p>
        </div>

        <div x-data="{ open: {{ request()->routeIs('inventory.*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                class="flex items-center justify-between w-full px-3 py-2 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800">
                <div class="flex items-center gap-3">
                    <i data-lucide="package" class="w-5 h-5 flex-shrink-0"></i>
                    <span x-show="sidebarOpen">Stok Barang</span>
                </div>
                <i data-lucide="chevron-down" class="w-4 h-4" x-show="sidebarOpen" :class="open && 'rotate-180'"></i>
            </button>
            <div x-show="open && sidebarOpen" class="ml-8 mt-1 space-y-1">
                <a href="{{ route('inventory.bahan-baku') }}"
                    class="block px-3 py-1.5 rounded-lg text-xs {{ request()->routeIs('inventory.bahan-baku') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20' : 'text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">Bahan
                    Baku</a>
                <a href="{{ route('inventory.packaging') }}"
                    class="block px-3 py-1.5 rounded-lg text-xs {{ request()->routeIs('inventory.packaging') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20' : 'text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">Material/Packaging</a>
                <a href="{{ route('inventory.barang-jadi') }}"
                    class="block px-3 py-1.5 rounded-lg text-xs {{ request()->routeIs('inventory.barang-jadi') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20' : 'text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">Barang
                    Jadi</a>
                <a href="{{ route('inventory.stock-history') }}"
                    class="block px-3 py-1.5 rounded-lg text-xs {{ request()->routeIs('inventory.stock-history') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20' : 'text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">Riwayat
                    Stok</a>
                @if(auth()->user()->isOwner() || auth()->user()->isOperasional())
                    <a href="{{ route('inventory.add-stock') }}"
                        class="block px-3 py-1.5 rounded-lg text-xs {{ request()->routeIs('inventory.add-stock') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20' : 'text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">
                        <i data-lucide="plus-circle" class="w-3 h-3 inline mr-1"></i>Tambah Stok
                    </a>
                @endif
                @if(auth()->user()->isOwner())
                    <a href="{{ route('inventory.pending-approval') }}"
                        class="flex items-center px-3 py-1.5 rounded-lg text-xs {{ request()->routeIs('inventory.pending-approval') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20' : 'text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">
                        Pending Approval
                        @if($pendingItemCount > 0)
                            <span
                                class="ml-auto px-1.5 text-xs bg-red-500 text-white rounded-full">{{ $pendingItemCount }}</span>
                        @endif
                    </a>
                @endif
            </div>
        </div>

        {{-- ========================================= --}}
        {{-- KEUANGAN (Owner, Finance) --}}
        {{-- ========================================= --}}
        @if(auth()->user()->isOwner() || auth()->user()->isFinance())
            <div class="pt-4" x-show="sidebarOpen">
                <p class="px-3 text-[10px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-wider">Keuangan
                </p>
            </div>

            <!-- Invoice -->
            <a href="{{ route('invoice.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('invoice.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">
                <i data-lucide="file-text" class="w-5 h-5 flex-shrink-0"></i>
                <span x-show="sidebarOpen">Invoice</span>
            </a>

            {{-- Special Order - REMOVED --}}

            <!-- Purchase/Pembelian -->
            <div x-data="{ open: {{ request()->routeIs('purchases.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="flex items-center justify-between w-full px-3 py-2 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800">
                    <div class="flex items-center gap-3">
                        <i data-lucide="shopping-cart" class="w-5 h-5 flex-shrink-0"></i>
                        <span x-show="sidebarOpen">Pembelian</span>
                    </div>
                    <i data-lucide="chevron-down" class="w-4 h-4" x-show="sidebarOpen" :class="open && 'rotate-180'"></i>
                </button>
                <div x-show="open && sidebarOpen" class="ml-8 mt-1 space-y-1">
                    <a href="{{ route('purchases.index') }}"
                        class="block px-3 py-1.5 rounded-lg text-xs {{ request()->routeIs('purchases.index') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20' : 'text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">Daftar
                        PO</a>
                    <a href="{{ route('purchases.create') }}"
                        class="block px-3 py-1.5 rounded-lg text-xs {{ request()->routeIs('purchases.create') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20' : 'text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">Buat
                        PO</a>
                </div>
            </div>

            <!-- Suppliers -->
            <a href="{{ route('suppliers.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('suppliers.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">
                <i data-lucide="building" class="w-5 h-5 flex-shrink-0"></i>
                <span x-show="sidebarOpen">Supplier</span>
            </a>
        @endif

        {{-- ========================================= --}}
        {{-- PENGIRIMAN (Owner, Finance, Operasional) --}}
        {{-- ========================================= --}}
        <div class="pt-4" x-show="sidebarOpen">
            <p class="px-3 text-[10px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-wider">
                Pengiriman</p>
        </div>

        <a href="{{ route('delivery-notes.index') }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('delivery-notes.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">
            <i data-lucide="truck" class="w-5 h-5 flex-shrink-0"></i>
            <span x-show="sidebarOpen">Surat Jalan</span>
        </a>

        {{-- ========================================= --}}
        {{-- DATA MASTER (Owner, Finance) --}}
        {{-- ========================================= --}}
        @if(auth()->user()->isOwner() || auth()->user()->isFinance())
            <div class="pt-4" x-show="sidebarOpen">
                <p class="px-3 text-[10px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-wider">Data
                    Master</p>
            </div>

            <a href="{{ route('customers.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('customers.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">
                <i data-lucide="users" class="w-5 h-5 flex-shrink-0"></i>
                <span x-show="sidebarOpen">Customers</span>
            </a>
        @endif

        {{-- ========================================= --}}
        {{-- LAPORAN (All roles, some items restricted) --}}
        {{-- ========================================= --}}
        <div class="pt-4" x-show="sidebarOpen">
            <p class="px-3 text-[10px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-wider">Laporan
            </p>
        </div>

        <div x-data="{ open: {{ request()->routeIs('reports.*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                class="flex items-center justify-between w-full px-3 py-2 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800">
                <div class="flex items-center gap-3">
                    <i data-lucide="bar-chart-3" class="w-5 h-5 flex-shrink-0"></i>
                    <span x-show="sidebarOpen">Laporan</span>
                </div>
                <i data-lucide="chevron-down" class="w-4 h-4" x-show="sidebarOpen" :class="open && 'rotate-180'"></i>
            </button>
            <div x-show="open && sidebarOpen" class="ml-8 mt-1 space-y-1">
                <a href="{{ route('reports.production') }}"
                    class="block px-3 py-1.5 rounded-lg text-xs {{ request()->routeIs('reports.production') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20' : 'text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">Produksi</a>
                @if(auth()->user()->isOwner() || auth()->user()->isFinance())
                    <a href="{{ route('reports.sales') }}"
                        class="block px-3 py-1.5 rounded-lg text-xs {{ request()->routeIs('reports.sales') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20' : 'text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">Penjualan</a>
                @endif
                <a href="{{ route('reports.inventory') }}"
                    class="block px-3 py-1.5 rounded-lg text-xs {{ request()->routeIs('reports.inventory') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20' : 'text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">Inventory</a>
            </div>
        </div>

        {{-- ========================================= --}}
        {{-- BANTUAN --}}
        {{-- ========================================= --}}
        <div class="pt-4" x-show="sidebarOpen">
            <p class="px-3 text-[10px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-wider">Bantuan
            </p>
        </div>

        <a href="{{ route('manual-book') }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('manual-book') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">
            <i data-lucide="book-open" class="w-5 h-5 flex-shrink-0"></i>
            <span x-show="sidebarOpen">Manual Book</span>
        </a>
    </nav>
</aside>