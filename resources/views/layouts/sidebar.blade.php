<!-- Mobile Backdrop -->
<div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-30 lg:hidden" x-cloak></div>

<!-- Sidebar -->
<aside
    class="fixed left-0 top-0 h-screen bg-white dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-800 z-40 flex flex-col transform transition-transform duration-200"
    :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen, 'lg:translate-x-0': true, 'lg:w-64': sidebarOpen, 'lg:w-16': !sidebarOpen, 'w-64': true}"
    x-cloak>
    <!-- Logo -->
    <div
        class="h-16 flex items-center justify-between px-4 bg-blue-600 dark:bg-zinc-900 border-b border-blue-500 dark:border-zinc-800 transition-colors duration-200">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2" x-show="sidebarOpen">
            <div class="bg-white p-1 rounded-lg shadow-sm">
                <img src="{{ asset('images/logo-bdb.png') }}" alt="Logo" class="w-6 h-6 object-contain">
            </div>
            <span class="font-bold text-lg text-white tracking-wide">BDB ERP</span>
        </a>
        <button @click="toggleSidebar()"
            class="p-1.5 hover:bg-blue-500/50 dark:hover:bg-zinc-800 rounded-lg text-blue-100/80 dark:text-zinc-400 hover:text-white transition-colors">
            <i data-lucide="menu" class="w-5 h-5"></i>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 p-3 space-y-1 overflow-y-auto text-sm">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 font-medium active:bg-blue-700 active:text-white {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-md shadow-blue-200 dark:shadow-none' : 'text-zinc-600 dark:text-zinc-400 hover:bg-blue-50 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400' }}">
            <i data-lucide="layout-dashboard" class="w-5 h-5 flex-shrink-0"></i>
            <span x-show="sidebarOpen">Dashboard</span>
        </a>

        {{-- ========================================= --}}
        {{-- PRODUKSI --}}
        {{-- ========================================= --}}
        @if(auth()->user()->isOwner() || auth()->user()->isOperasional())
            <div class="pt-6 pb-2" x-show="sidebarOpen">
                <p
                    class="px-3 text-[10px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest leading-none">
                    Produksi</p>
            </div>

            <!-- SPK -->
            <div x-data="{ open: {{ request()->routeIs('spk.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="flex items-center justify-between w-full px-3 py-2.5 rounded-lg transition-colors group active:bg-blue-700 active:text-white {{ request()->routeIs('spk.*') ? 'text-blue-600 font-bold bg-blue-50 dark:bg-zinc-800 dark:text-blue-400' : 'text-zinc-600 dark:text-zinc-400 hover:bg-blue-50 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400' }}">
                    <div class="flex items-center gap-3">
                        <i data-lucide="clipboard-list"
                            class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('spk.*') ? 'text-blue-600 dark:text-blue-400' : 'group-hover:text-blue-600 dark:group-hover:text-blue-400' }}"></i>
                        <span x-show="sidebarOpen">SPK</span>
                    </div>
                    <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200" x-show="sidebarOpen"
                        :class="open && 'rotate-180'"></i>
                </button>
                <div x-show="open && sidebarOpen" x-collapse
                    class="ml-9 mt-1 space-y-1 border-l border-zinc-200 dark:border-zinc-700/50 pl-2">
                    <a href="{{ route('spk.index') }}"
                        class="block px-3 py-2 rounded-lg text-xs active:bg-blue-700 active:text-white {{ request()->routeIs('spk.index') ? 'bg-blue-600 text-white shadow-sm font-medium' : 'text-zinc-500 dark:text-zinc-400 hover:bg-blue-50 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-300' }}">
                        Daftar SPK
                    </a>
                    <a href="{{ route('spk.create') }}"
                        class="block px-3 py-2 rounded-lg text-xs active:bg-blue-700 active:text-white {{ request()->routeIs('spk.create') ? 'bg-blue-600 text-white shadow-sm font-medium' : 'text-zinc-500 dark:text-zinc-400 hover:bg-blue-50 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-300' }}">
                        Buat SPK
                    </a>
                    @if(auth()->user()->isOwner())
                        <a href="{{ route('spk.pending') }}"
                            class="flex items-center px-3 py-2 rounded-lg text-xs active:bg-blue-700 active:text-white {{ request()->routeIs('spk.pending') ? 'bg-blue-600 text-white shadow-sm font-medium' : 'text-zinc-500 dark:text-zinc-400 hover:bg-blue-50 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-300' }}">
                            Pending
                            @if($pendingSpkCount > 0)
                                <span
                                    class="ml-auto px-1.5 py-0.5 text-[10px] bg-red-500 text-white rounded-full font-bold box-shadow-sm border border-white/20">{{ $pendingSpkCount }}</span>
                            @endif
                        </a>
                    @endif
                </div>
            </div>

            <!-- FPB -->
            <div x-data="{ open: {{ request()->routeIs('fpb.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="flex items-center justify-between w-full px-3 py-2.5 rounded-lg transition-colors group active:bg-blue-700 active:text-white {{ request()->routeIs('fpb.*') ? 'text-blue-600 font-bold bg-blue-50 dark:bg-zinc-800 dark:text-blue-400' : 'text-zinc-600 dark:text-zinc-400 hover:bg-blue-50 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400' }}">
                    <div class="flex items-center gap-3">
                        <i data-lucide="file-input"
                            class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('fpb.*') ? 'text-blue-600 dark:text-blue-400' : 'group-hover:text-blue-600 dark:group-hover:text-blue-400' }}"></i>
                        <span x-show="sidebarOpen">FPB</span>
                    </div>
                    <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200" x-show="sidebarOpen"
                        :class="open && 'rotate-180'"></i>
                </button>
                <div x-show="open && sidebarOpen" x-collapse
                    class="ml-9 mt-1 space-y-1 border-l border-zinc-200 dark:border-zinc-700/50 pl-2">
                    <a href="{{ route('fpb.index') }}"
                        class="block px-3 py-2 rounded-lg text-xs active:bg-blue-700 active:text-white {{ request()->routeIs('fpb.index') && !request('status') ? 'bg-blue-600 text-white shadow-sm font-medium' : 'text-zinc-500 dark:text-zinc-400 hover:bg-blue-50 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-300' }}">
                        Daftar FPB
                    </a>
                    <a href="{{ route('fpb.create') }}"
                        class="block px-3 py-2 rounded-lg text-xs active:bg-blue-700 active:text-white {{ request()->routeIs('fpb.create') ? 'bg-blue-600 text-white shadow-sm font-medium' : 'text-zinc-500 dark:text-zinc-400 hover:bg-blue-50 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-300' }}">
                        Buat FPB
                    </a>
                    @if(auth()->user()->isOwner())
                        <a href="{{ route('fpb.index', ['status' => 'pending']) }}"
                            class="flex items-center px-3 py-2 rounded-lg text-xs active:bg-blue-700 active:text-white {{ request()->routeIs('fpb.index') && request('status') == 'pending' ? 'bg-blue-600 text-white shadow-sm font-medium' : 'text-zinc-500 dark:text-zinc-400 hover:bg-blue-50 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-300' }}">
                            Pending
                            @if($pendingFpbCount > 0)
                                <span
                                    class="ml-auto px-1.5 py-0.5 text-[10px] bg-red-500 text-white rounded-full font-bold border border-white/20">{{ $pendingFpbCount }}</span>
                            @endif
                        </a>
                    @endif
                </div>
            </div>

            <!-- Job Cost -->
            <div x-data="{ open: {{ request()->routeIs('job-costs.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="flex items-center justify-between w-full px-3 py-2.5 rounded-lg transition-colors group active:bg-blue-700 active:text-white {{ request()->routeIs('job-costs.*') ? 'text-blue-600 font-bold bg-blue-50 dark:bg-zinc-800 dark:text-blue-400' : 'text-zinc-600 dark:text-zinc-400 hover:bg-blue-50 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400' }}">
                    <div class="flex items-center gap-3">
                        <i data-lucide="scissors"
                            class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('job-costs.*') ? 'text-blue-600 dark:text-blue-400' : 'group-hover:text-blue-600 dark:group-hover:text-blue-400' }}"></i>
                        <span x-show="sidebarOpen">Job Cost</span>
                    </div>
                    <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200" x-show="sidebarOpen"
                        :class="open && 'rotate-180'"></i>
                </button>
                <div x-show="open && sidebarOpen" x-collapse
                    class="ml-9 mt-1 space-y-1 border-l border-zinc-200 dark:border-zinc-700/50 pl-2">
                    <a href="{{ route('job-costs.index') }}"
                        class="block px-3 py-2 rounded-lg text-xs active:bg-blue-700 active:text-white {{ request()->routeIs('job-costs.index') ? 'bg-blue-600 text-white shadow-sm font-medium' : 'text-zinc-500 dark:text-zinc-400 hover:bg-blue-50 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-300' }}">
                        Daftar JC
                    </a>
                    <a href="{{ route('job-costs.create') }}"
                        class="block px-3 py-2 rounded-lg text-xs active:bg-blue-700 active:text-white {{ request()->routeIs('job-costs.create') ? 'bg-blue-600 text-white shadow-sm font-medium' : 'text-zinc-500 dark:text-zinc-400 hover:bg-blue-50 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-300' }}">
                        Buat JC
                    </a>
                    @if(auth()->user()->isOwner())
                        <a href="{{ route('job-costs.pending') }}"
                            class="block px-3 py-2 rounded-lg text-xs active:bg-blue-700 active:text-white {{ request()->routeIs('job-costs.pending') ? 'bg-blue-600 text-white shadow-sm font-medium' : 'text-zinc-500 dark:text-zinc-400 hover:bg-blue-50 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-300' }}">
                            Pending Approval
                        </a>
                    @endif
                </div>
            </div>
        @endif

        {{-- ========================================= --}}
        {{-- INVENTORY --}}
        {{-- ========================================= --}}
        <div class="pt-6 pb-2" x-show="sidebarOpen">
            <p
                class="px-3 text-[10px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest leading-none">
                Inventory</p>
        </div>

        <div x-data="{ open: {{ request()->routeIs('inventory.*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                class="flex items-center justify-between w-full px-3 py-2.5 rounded-lg transition-colors group active:bg-blue-700 active:text-white {{ request()->routeIs('inventory.*') ? 'text-blue-600 font-bold bg-blue-50 dark:bg-zinc-800 dark:text-blue-400' : 'text-zinc-600 dark:text-zinc-400 hover:bg-blue-50 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400' }}">
                <div class="flex items-center gap-3">
                    <i data-lucide="package"
                        class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('inventory.*') ? 'text-blue-600 dark:text-blue-400' : 'group-hover:text-blue-600 dark:group-hover:text-blue-400' }}"></i>
                    <span x-show="sidebarOpen">Stok Barang</span>
                </div>
                <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200" x-show="sidebarOpen"
                    :class="open && 'rotate-180'"></i>
            </button>
            <div x-show="open && sidebarOpen" x-collapse
                class="ml-9 mt-1 space-y-1 border-l border-zinc-200 dark:border-zinc-700/50 pl-2">
                <a href="{{ route('inventory.bahan-baku') }}"
                    class="block px-3 py-2 rounded-lg text-xs active:bg-blue-700 active:text-white {{ request()->routeIs('inventory.bahan-baku') ? 'bg-blue-600 text-white shadow-sm font-medium' : 'text-zinc-500 dark:text-zinc-400 hover:bg-blue-50 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-300' }}">
                    Bahan Baku
                </a>
                <a href="{{ route('inventory.packaging') }}"
                    class="block px-3 py-2 rounded-lg text-xs active:bg-blue-700 active:text-white {{ request()->routeIs('inventory.packaging') ? 'bg-blue-600 text-white shadow-sm font-medium' : 'text-zinc-500 dark:text-zinc-400 hover:bg-blue-50 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-300' }}">
                    Material/Packaging
                </a>
                <a href="{{ route('inventory.barang-jadi') }}"
                    class="block px-3 py-2 rounded-lg text-xs active:bg-blue-700 active:text-white {{ request()->routeIs('inventory.barang-jadi') ? 'bg-blue-600 text-white shadow-sm font-medium' : 'text-zinc-500 dark:text-zinc-400 hover:bg-blue-50 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-300' }}">
                    Barang Jadi
                </a>
                <a href="{{ route('inventory.stock-history') }}"
                    class="block px-3 py-2 rounded-lg text-xs active:bg-blue-700 active:text-white {{ request()->routeIs('inventory.stock-history') ? 'bg-blue-600 text-white shadow-sm font-medium' : 'text-zinc-500 dark:text-zinc-400 hover:bg-blue-50 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-300' }}">
                    Riwayat Stok
                </a>
                @if(auth()->user()->isOwner() || auth()->user()->isOperasional())
                    <a href="{{ route('inventory.add-stock') }}"
                        class="block px-3 py-2 rounded-lg text-xs active:bg-blue-700 active:text-white {{ request()->routeIs('inventory.add-stock') ? 'bg-blue-600 text-white shadow-sm font-medium' : 'text-zinc-500 dark:text-zinc-400 hover:bg-blue-50 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-300' }}">
                        <i data-lucide="plus-circle" class="w-3 h-3 inline mr-1 opacity-70"></i>Tambah Stok
                    </a>
                @endif
                @if(auth()->user()->isOwner())
                    <a href="{{ route('inventory.pending-approval') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-xs active:bg-blue-700 active:text-white {{ request()->routeIs('inventory.pending-approval') ? 'bg-blue-600 text-white shadow-sm font-medium' : 'text-zinc-500 dark:text-zinc-400 hover:bg-blue-50 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-300' }}">
                        Pending Approval
                        @if($pendingItemCount > 0)
                            <span
                                class="ml-auto px-1.5 py-0.5 text-[10px] bg-red-500 text-white rounded-full font-bold box-shadow-sm border border-white/20">{{ $pendingItemCount }}</span>
                        @endif
                    </a>
                @endif
            </div>
        </div>

        {{-- ========================================= --}}
        {{-- KEUANGAN --}}
        {{-- ========================================= --}}
        @if(auth()->user()->isOwner() || auth()->user()->isFinance())
            <div class="pt-6 pb-2" x-show="sidebarOpen">
                <p
                    class="px-3 text-[10px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest leading-none">
                    Keuangan</p>
            </div>

            <!-- Invoice -->
            <a href="{{ route('invoice.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors group active:bg-blue-700 active:text-white {{ request()->routeIs('invoice.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-200 dark:shadow-none' : 'text-zinc-600 dark:text-zinc-400 hover:bg-blue-50 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400' }}">
                <i data-lucide="file-text" class="w-5 h-5 flex-shrink-0"></i>
                <span x-show="sidebarOpen">Invoice</span>
            </a>

            <!-- Purchase/Pembelian -->
            <div x-data="{ open: {{ request()->routeIs('purchases.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="flex items-center justify-between w-full px-3 py-2.5 rounded-lg transition-colors group active:bg-blue-700 active:text-white {{ request()->routeIs('purchases.*') ? 'text-blue-600 font-bold bg-blue-50 dark:bg-zinc-800 dark:text-blue-400' : 'text-zinc-600 dark:text-zinc-400 hover:bg-blue-50 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400' }}">
                    <div class="flex items-center gap-3">
                        <i data-lucide="shopping-cart"
                            class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('purchases.*') ? 'text-blue-600 dark:text-blue-400' : 'group-hover:text-blue-600 dark:group-hover:text-blue-400' }}"></i>
                        <span x-show="sidebarOpen">Pembelian</span>
                    </div>
                    <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200" x-show="sidebarOpen"
                        :class="open && 'rotate-180'"></i>
                </button>
                <div x-show="open && sidebarOpen" x-collapse
                    class="ml-9 mt-1 space-y-1 border-l border-zinc-200 dark:border-zinc-700/50 pl-2">
                    <a href="{{ route('purchases.index') }}"
                        class="block px-3 py-2 rounded-lg text-xs active:bg-blue-700 active:text-white {{ request()->routeIs('purchases.index') ? 'bg-blue-600 text-white shadow-sm font-medium' : 'text-zinc-500 dark:text-zinc-400 hover:bg-blue-50 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-300' }}">
                        Daftar PO
                    </a>
                    <a href="{{ route('purchases.create') }}"
                        class="block px-3 py-2 rounded-lg text-xs active:bg-blue-700 active:text-white {{ request()->routeIs('purchases.create') ? 'bg-blue-600 text-white shadow-sm font-medium' : 'text-zinc-500 dark:text-zinc-400 hover:bg-blue-50 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-300' }}">
                        Buat PO
                    </a>
                </div>
            </div>

            <!-- Suppliers -->
            <a href="{{ route('suppliers.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors group active:bg-blue-700 active:text-white {{ request()->routeIs('suppliers.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-200 dark:shadow-none' : 'text-zinc-600 dark:text-zinc-400 hover:bg-blue-50 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400' }}">
                <i data-lucide="building" class="w-5 h-5 flex-shrink-0"></i>
                <span x-show="sidebarOpen">Supplier</span>
            </a>
        @endif

        {{-- ========================================= --}}
        {{-- PENGIRIMAN --}}
        {{-- ========================================= --}}
        <div class="pt-6 pb-2" x-show="sidebarOpen">
            <p
                class="px-3 text-[10px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest leading-none">
                Pengiriman</p>
        </div>

        <a href="{{ route('delivery-notes.index') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors group active:bg-blue-700 active:text-white {{ request()->routeIs('delivery-notes.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-200 dark:shadow-none' : 'text-zinc-600 dark:text-zinc-400 hover:bg-blue-50 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400' }}">
            <i data-lucide="truck" class="w-5 h-5 flex-shrink-0"></i>
            <span x-show="sidebarOpen">Surat Jalan</span>
        </a>

        {{-- ========================================= --}}
        {{-- DATA MASTER --}}
        {{-- ========================================= --}}
        @if(auth()->user()->isOwner() || auth()->user()->isFinance())
            <div class="pt-6 pb-2" x-show="sidebarOpen">
                <p
                    class="px-3 text-[10px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest leading-none">
                    Data Master</p>
            </div>

            <a href="{{ route('customers.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors group active:bg-blue-700 active:text-white {{ request()->routeIs('customers.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-200 dark:shadow-none' : 'text-zinc-600 dark:text-zinc-400 hover:bg-blue-50 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400' }}">
                <i data-lucide="users" class="w-5 h-5 flex-shrink-0"></i>
                <span x-show="sidebarOpen">Customers</span>
            </a>
        @endif

        {{-- ========================================= --}}
        {{-- LAPORAN --}}
        {{-- ========================================= --}}
        <div class="pt-6 pb-2" x-show="sidebarOpen">
            <p
                class="px-3 text-[10px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest leading-none">
                Laporan</p>
        </div>

        <div x-data="{ open: {{ request()->routeIs('reports.*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                class="flex items-center justify-between w-full px-3 py-2.5 rounded-lg transition-colors group active:bg-blue-700 active:text-white {{ request()->routeIs('reports.*') ? 'text-blue-600 font-bold bg-blue-50 dark:bg-zinc-800 dark:text-blue-400' : 'text-zinc-600 dark:text-zinc-400 hover:bg-blue-50 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400' }}">
                <div class="flex items-center gap-3">
                    <i data-lucide="bar-chart-3"
                        class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('reports.*') ? 'text-blue-600 dark:text-blue-400' : 'group-hover:text-blue-600 dark:group-hover:text-blue-400' }}"></i>
                    <span x-show="sidebarOpen">Laporan</span>
                </div>
                <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200" x-show="sidebarOpen"
                    :class="open && 'rotate-180'"></i>
            </button>
            <div x-show="open && sidebarOpen" x-collapse
                class="ml-9 mt-1 space-y-1 border-l border-zinc-200 dark:border-zinc-700/50 pl-2">
                <a href="{{ route('reports.production') }}"
                    class="block px-3 py-2 rounded-lg text-xs active:bg-blue-700 active:text-white {{ request()->routeIs('reports.production') ? 'bg-blue-600 text-white shadow-sm font-medium' : 'text-zinc-500 dark:text-zinc-400 hover:bg-blue-50 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-300' }}">
                    Produksi
                </a>
                @if(auth()->user()->isOwner() || auth()->user()->isFinance())
                    <a href="{{ route('reports.sales') }}"
                        class="block px-3 py-2 rounded-lg text-xs active:bg-blue-700 active:text-white {{ request()->routeIs('reports.sales') ? 'bg-blue-600 text-white shadow-sm font-medium' : 'text-zinc-500 dark:text-zinc-400 hover:bg-blue-50 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-300' }}">
                        Penjualan
                    </a>
                @endif
                <a href="{{ route('reports.inventory') }}"
                    class="block px-3 py-2 rounded-lg text-xs active:bg-blue-700 active:text-white {{ request()->routeIs('reports.inventory') ? 'bg-blue-600 text-white shadow-sm font-medium' : 'text-zinc-500 dark:text-zinc-400 hover:bg-blue-50 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-300' }}">
                    Inventory
                </a>
            </div>
        </div>

        {{-- ========================================= --}}
        {{-- BANTUAN --}}
        {{-- ========================================= --}}
        <div class="pt-6 pb-2" x-show="sidebarOpen">
            <p
                class="px-3 text-[10px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest leading-none">
                Bantuan</p>
        </div>

        <a href="{{ route('manual-book') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors group active:bg-blue-700 active:text-white {{ request()->routeIs('manual-book') ? 'bg-blue-600 text-white shadow-md shadow-blue-200 dark:shadow-none' : 'text-zinc-600 dark:text-zinc-400 hover:bg-blue-50 dark:hover:bg-zinc-800 hover:text-blue-600 dark:hover:text-blue-400' }}">
            <i data-lucide="book-open" class="w-5 h-5 flex-shrink-0"></i>
            <span x-show="sidebarOpen">Manual Book</span>
        </a>
    </nav>
</aside>