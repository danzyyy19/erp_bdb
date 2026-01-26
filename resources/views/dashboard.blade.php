<x-app-layout>
    @section('title', 'Dashboard')

    <!-- Filter -->
    <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-4 mb-4" x-data="dashboardFilter()">
        <div class="flex flex-wrap items-end gap-3">
            <div class="w-28">
                <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Bulan</label>
                <select x-model="month" @change="submitFilter()"
                        class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}">{{ DateTime::createFromFormat('!m', $i)->format('M') }}</option>
                    @endfor
                </select>
            </div>
            <div class="w-24">
                <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Tahun</label>
                <select x-model="year" @change="submitFilter()"
                        class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                    @for($y = now()->year; $y >= now()->year - 5; $y--)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="text-sm text-zinc-500">
                Menampilkan data bulan <span class="font-medium text-zinc-900 dark:text-white">{{ $monthLabel ?? 'ini' }}</span>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Total SPK -->
        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-5">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Total SPK</p>
            <p class="text-2xl font-bold text-zinc-900 dark:text-white mt-1">{{ $stats['total_spk'] }}</p>
            <div class="flex items-center gap-2 mt-2 text-sm">
                <span class="text-yellow-600 dark:text-yellow-400">{{ $stats['pending_spk'] }} pending</span>
                <span class="text-zinc-300 dark:text-zinc-600">•</span>
                <span class="text-indigo-600 dark:text-indigo-400">{{ $stats['in_progress_spk'] }} proses</span>
            </div>
        </div>

        <!-- Completed SPK -->
        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-5">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">SPK Selesai</p>
            <p class="text-2xl font-bold text-zinc-900 dark:text-white mt-1">{{ $stats['completed_spk'] }}</p>
            <p class="text-sm text-green-600 dark:text-green-400 mt-2">Produksi selesai</p>
        </div>

        <!-- Total Products -->
        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-5">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Total Produk</p>
            <p class="text-2xl font-bold text-zinc-900 dark:text-white mt-1">{{ $stats['total_products'] }}</p>
            @if($stats['low_stock_products'] > 0)
                <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $stats['low_stock_products'] }} stok rendah</p>
            @else
                <p class="text-sm text-green-600 dark:text-green-400 mt-2">Stok aman</p>
            @endif
        </div>

        <!-- Invoice Count (Owner, Finance only) -->
        @if(auth()->user()->isOwner() || auth()->user()->isFinance())
        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-5">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Total Invoice</p>
            <p class="text-2xl font-bold text-zinc-900 dark:text-white mt-1">{{ $stats['total_invoices'] ?? 0 }}</p>
            @if(($stats['unpaid_invoices'] ?? 0) > 0)
                <p class="text-sm text-yellow-600 dark:text-yellow-400 mt-2">{{ $stats['unpaid_invoices'] }} belum lunas</p>
            @else
                <p class="text-sm text-green-600 dark:text-green-400 mt-2">Semua lunas</p>
            @endif
        </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent SPK -->
        <div class="lg:col-span-2 bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800">
            <div class="p-4 border-b border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
                <h3 class="font-semibold text-sm text-zinc-900 dark:text-white">SPK Terbaru</h3>
                <a href="{{ route('spk.index') }}" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">Lihat Semua</a>
            </div>
            <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @forelse($recentSpks as $spk)
                    <a href="{{ route('spk.show', $spk) }}" class="flex items-center justify-between p-4 hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                        <div>
                            <p class="font-medium text-sm text-zinc-900 dark:text-white">{{ $spk->spk_number }}</p>
                            <p class="text-xs text-zinc-500">{{ $spk->creator->name }} • {{ $spk->created_at->diffForHumans() }}</p>
                        </div>
                        @php
                            $colors = [
                                'pending' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400',
                                'approved' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400',
                                'in_progress' => 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400',
                                'completed' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400',
                                'rejected' => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400',
                            ];
                        @endphp
                        <span class="px-2 py-0.5 text-xs rounded {{ $colors[$spk->status] ?? '' }}">{{ $spk->status_label }}</span>
                    </a>
                @empty
                    <div class="p-8 text-center text-sm text-zinc-500">Belum ada SPK</div>
                @endforelse
            </div>
        </div>

        <!-- Low Stock Products -->
        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800">
            <div class="p-4 border-b border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
                <h3 class="font-semibold text-sm text-zinc-900 dark:text-white">Stok Rendah</h3>
                <a href="{{ route('inventory.bahan-baku') }}?stock_status=low" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">Lihat Semua</a>
            </div>
            <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @forelse($lowStockProducts as $product)
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-1">
                            <p class="font-medium text-sm text-zinc-900 dark:text-white truncate">{{ $product->name }}</p>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-zinc-500">{{ $product->code }}</span>
                            <span class="font-medium text-red-600 dark:text-red-400">
                                {{ number_format($product->current_stock, 0) }} / {{ number_format($product->min_stock, 0) }} {{ $product->unit }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-sm text-zinc-500">Semua stok aman</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Finance Dashboard Sections (Owner, Finance only) --}}
    @if(auth()->user()->isOwner() || auth()->user()->isFinance())
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
        <!-- Upcoming Due Invoices -->
        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800">
            <div class="p-4 border-b border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
                <h3 class="font-semibold text-sm text-zinc-900 dark:text-white">
                    <i data-lucide="alert-circle" class="w-4 h-4 inline text-yellow-500 mr-1"></i>
                    Invoice Jatuh Tempo
                </h3>
                <a href="{{ route('invoice.index') }}" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">Lihat Semua</a>
            </div>
            <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @forelse($upcomingDueInvoices ?? [] as $invoice)
                    <a href="{{ route('invoice.show', $invoice) }}" class="flex items-center justify-between p-4 hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                        <div>
                            <p class="font-medium text-sm text-zinc-900 dark:text-white">{{ $invoice->invoice_number }}</p>
                            <p class="text-xs text-zinc-500">{{ $invoice->customer->name ?? '-' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium {{ $invoice->due_date < now() ? 'text-red-600 dark:text-red-400' : 'text-yellow-600 dark:text-yellow-400' }}">
                                {{ $invoice->due_date ? $invoice->due_date->format('d M Y') : '-' }}
                            </p>
                            <p class="text-xs text-zinc-500">Rp {{ number_format($invoice->total_amount ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </a>
                @empty
                    <div class="p-8 text-center text-sm text-zinc-500">Tidak ada invoice mendekati jatuh tempo</div>
                @endforelse
            </div>
        </div>

        <!-- Recent Completed Deliveries -->
        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800">
            <div class="p-4 border-b border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
                <h3 class="font-semibold text-sm text-zinc-900 dark:text-white">
                    <i data-lucide="truck" class="w-4 h-4 inline text-green-500 mr-1"></i>
                    Surat Jalan Selesai
                </h3>
                <a href="{{ route('delivery-notes.index') }}" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">Lihat Semua</a>
            </div>
            <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @forelse($recentDeliveries ?? [] as $delivery)
                    <a href="{{ route('delivery-notes.show', $delivery) }}" class="flex items-center justify-between p-4 hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                        <div>
                            <p class="font-medium text-sm text-zinc-900 dark:text-white">{{ $delivery->delivery_number }}</p>
                            <p class="text-xs text-zinc-500">{{ $delivery->customer->name ?? '-' }}</p>
                        </div>
                        <div class="text-right">
                            <span class="px-2 py-0.5 text-xs rounded bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">Selesai</span>
                            <p class="text-xs text-zinc-500 mt-1">{{ $delivery->updated_at->diffForHumans() }}</p>
                        </div>
                    </a>
                @empty
                    <div class="p-8 text-center text-sm text-zinc-500">Belum ada surat jalan selesai</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Finance Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-6">
        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-5">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Invoice Telat Bayar</p>
            <p class="text-2xl font-bold {{ ($stats['overdue_invoices'] ?? 0) > 0 ? 'text-red-600 dark:text-red-400' : 'text-zinc-900 dark:text-white' }}">{{ $stats['overdue_invoices'] ?? 0 }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-5">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Jatuh Tempo 7 Hari</p>
            <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $stats['due_soon_invoices'] ?? 0 }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-5">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Pengiriman Selesai</p>
            <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $stats['completed_deliveries'] ?? 0 }}</p>
        </div>
    </div>
    @endif

    <!-- Quick Actions -->
    @if(auth()->user()->isOwner() || auth()->user()->isOperasional())
        <div class="mt-6 bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-5">
            <h3 class="font-semibold text-sm text-zinc-900 dark:text-white mb-3">Aksi Cepat</h3>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('spk.create') }}" class="px-4 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Buat SPK Baru</a>
                @if(auth()->user()->isOwner())
                    <a href="{{ route('spk.pending') }}" class="px-4 py-2 text-sm bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg">
                        Review SPK Pending @if($stats['pending_spk'] > 0)({{ $stats['pending_spk'] }})@endif
                    </a>
                    <a href="{{ route('customers.create') }}" class="px-4 py-2 text-sm bg-green-600 hover:bg-green-700 text-white rounded-lg">Tambah Customer</a>
                @endif
                <a href="{{ route('inventory.bahan-baku') }}" class="px-4 py-2 text-sm bg-zinc-600 hover:bg-zinc-700 text-white rounded-lg">Cek Inventory</a>
            </div>
        </div>
    @endif

    @push('scripts')
    <script>
        function dashboardFilter() {
            return {
                month: '{{ $month ?? now()->month }}',
                year: '{{ $year ?? now()->year }}',
                submitFilter() {
                    const params = new URLSearchParams();
                    params.set('month', this.month);
                    params.set('year', this.year);
                    window.location.href = '{{ route('dashboard') }}' + '?' + params.toString();
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
