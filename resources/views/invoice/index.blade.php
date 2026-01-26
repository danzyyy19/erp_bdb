<x-app-layout>
    @section('title', 'Daftar Invoice')

    <div class="space-y-4" x-data="invoiceTable()">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-zinc-900 dark:text-white">Daftar Invoice</h2>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Kelola invoice penjualan</p>
            </div>
            <a href="{{ route('invoice.create') }}"
                class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Buat Invoice
            </a>
        </div>

        <!-- Search & Filter -->
        <div class="flex flex-col sm:flex-row gap-3">
            <!-- Search -->
            <div class="relative flex-1">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-zinc-400"></i>
                <input type="text" x-model="search" @input.debounce.300ms="fetchData()"
                    placeholder="Cari nomor invoice..."
                    class="w-full pl-10 pr-4 py-2 text-sm rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <!-- Status Filter -->
            <select x-model="status" @change="fetchData()"
                class="px-3 py-2 text-sm rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                <option value="">Semua Status</option>
                <option value="pending_approval">Menunggu Persetujuan</option>
                <option value="sent">Terkirim</option>
                <option value="paid">Lunas</option>
                <option value="partial">Sebagian</option>
                <option value="overdue">Jatuh Tempo</option>
                <option value="cancelled">Dibatalkan</option>
            </select>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-zinc-50 dark:bg-zinc-800/50 border-b border-zinc-200 dark:border-zinc-700">
                        <tr>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase border-r border-zinc-200 dark:border-zinc-700">
                                No. Invoice</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase border-r border-zinc-200 dark:border-zinc-700">
                                Customer</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase border-r border-zinc-200 dark:border-zinc-700">
                                Tanggal</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase border-r border-zinc-200 dark:border-zinc-700">
                                Jatuh Tempo</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase border-r border-zinc-200 dark:border-zinc-700">
                                Total</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase border-r border-zinc-200 dark:border-zinc-700">
                                Status</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="invoice-table-body">
                        @include('invoice.partials.invoice-table', ['invoices' => $invoices])
                    </tbody>
                </table>
            </div>

            @if($invoices->hasPages())
                <div id="pagination-container"
                    class="px-4 py-3 border-t border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50">
                    {{ $invoices->links() }}
                </div>
            @else
                <div id="pagination-container" class="hidden"></div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            function invoiceTable() {
                return {
                    search: '{{ request('search') }}',
                    status: '{{ request('status') }}',

                    fetchData() {
                        const params = new URLSearchParams();
                        if (this.search) params.append('search', this.search);
                        if (this.status) params.append('status', this.status);

                        fetch(`{{ route('invoice.index') }}?${params.toString()}`, {
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        })
                            .then(res => res.text())
                            .then(html => {
                                const parser = new DOMParser();
                                const doc = parser.parseFromString(html, 'text/html');
                                document.getElementById('invoice-table-body').innerHTML = doc.getElementById('invoice-table-body').innerHTML;
                                const paginationEl = doc.getElementById('pagination-container');
                                document.getElementById('pagination-container').innerHTML = paginationEl ? paginationEl.innerHTML : '';
                                if (window.lucide) lucide.createIcons();
                            });
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>
