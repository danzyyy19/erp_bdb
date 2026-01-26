<x-app-layout>
    @section('title', 'Riwayat Barang Jadi')

    <div class="space-y-4" x-data="historyFilter()">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-zinc-900 dark:text-white">Riwayat Barang Jadi</h2>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Lihat riwayat produksi dan penjualan barang jadi</p>
            </div>
            <a href="{{ route('inventory.barang-jadi') }}"
                class="px-4 py-2 text-sm bg-zinc-200 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300 rounded-lg hover:bg-zinc-300 dark:hover:bg-zinc-600">
                ← Kembali
            </a>
        </div>

        <div class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[200px] relative">
                <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Cari Produk</label>
                <i data-lucide="search" class="absolute left-3 top-8 w-4 h-4 text-zinc-400"></i>
                <input type="text" x-model="search" @input.debounce.300ms="fetchData()"
                    placeholder="Nama atau kode produk..."
                    class="w-full pl-10 pr-4 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
            </div>
            <div class="w-32">
                <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Tipe</label>
                <select x-model="type" @change="fetchData()"
                    class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                    <option value="">Semua</option>
                    <option value="in">Masuk</option>
                    <option value="out">Keluar</option>
                    <option value="production_in">Hasil Produksi</option>
                    <option value="adjustment">Adjustment</option>
                </select>
            </div>
            <div class="w-36">
                <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Dari</label>
                <input type="date" x-model="date_from" @change="fetchData()"
                    class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
            </div>
            <div class="w-36">
                <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Sampai</label>
                <input type="date" x-model="date_to" @change="fetchData()"
                    class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
            </div>
            <template x-if="search || type || date_from || date_to">
                <button type="button" @click="resetFilter()"
                    class="px-3 py-2 text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white">Reset</button>
            </template>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-zinc-50 dark:bg-zinc-800/50 border-b border-zinc-200 dark:border-zinc-700">
                        <tr>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase border-r border-zinc-200 dark:border-zinc-700">
                                Tanggal</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase border-r border-zinc-200 dark:border-zinc-700">
                                Produk</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase border-r border-zinc-200 dark:border-zinc-700">
                                Tipe</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase border-r border-zinc-200 dark:border-zinc-700">
                                Qty</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase border-r border-zinc-200 dark:border-zinc-700">
                                Sebelum</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase border-r border-zinc-200 dark:border-zinc-700">
                                Sesudah</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                                User</th>
                        </tr>
                    </thead>
                    <tbody id="table-body">
                        @include('inventory.partials.category-history-table')
                    </tbody>
                </table>
            </div>
            @if($movements->hasPages())
                <div id="pagination"
                    class="px-4 py-3 border-t border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50">
                    {{ $movements->links() }}</div>
            @else
                <div id="pagination" class="hidden"></div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            function historyFilter() {
                return {
                    search: '{{ request('search') }}', type: '{{ request('type') }}', date_from: '{{ request('date_from') }}', date_to: '{{ request('date_to') }}',
                    fetchData() {
                        const params = new URLSearchParams();
                        if (this.search) params.set('search', this.search);
                        if (this.type) params.set('type', this.type);
                        if (this.date_from) params.set('date_from', this.date_from);
                        if (this.date_to) params.set('date_to', this.date_to);
                        fetch('{{ route('inventory.barang-jadi.history') }}?' + params.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                            .then(res => res.json()).then(data => { document.getElementById('table-body').innerHTML = data.html; document.getElementById('pagination').innerHTML = data.pagination || ''; if (window.lucide) lucide.createIcons(); });
                    },
                    resetFilter() { this.search = ''; this.type = ''; this.date_from = ''; this.date_to = ''; this.fetchData(); }
                }
            }
        </script>
    @endpush
</x-app-layout>