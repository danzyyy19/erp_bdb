<x-app-layout>
    @section('title', 'Material/Packaging')

    <!-- Filters -->
    <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-4 mb-4"
        x-data="inventoryFilter()">
        <div class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Cari</label>
                <input type="text" x-model="search" @input.debounce.300ms="fetchData()"
                    placeholder="Ketik nama atau kode..."
                    class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
            </div>
            <div class="w-32">
                <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Status Stok</label>
                <select x-model="stock_status" @change="fetchData()"
                    class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                    <option value="">Semua</option>
                    <option value="low">Stok Menipis</option>
                    <option value="normal">Stok Aman</option>
                </select>
            </div>
            <template x-if="search || stock_status">
                <button type="button" @click="resetFilter()"
                    class="px-3 py-2 text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white">Reset</button>
            </template>
        </div>
    </div>

    <!-- Action Button -->
    <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
        <div class="flex gap-2">
            @if(auth()->user()->isOwner() || auth()->user()->isOperasional() || auth()->user()->isFinance())
                <a href="{{ route('inventory.packaging.create') }}"
                    class="px-4 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Tambah
                    Material/Packaging</a>
            @endif
            <a href="{{ route('inventory.packaging.history') }}"
                class="px-4 py-2 text-sm bg-zinc-200 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-300 dark:hover:bg-zinc-600 rounded-lg">
                <i data-lucide="history" class="w-4 h-4 inline mr-1"></i>Riwayat
            </a>
        </div>
        <x-export-buttons excel-route="export.products.excel" pdf-route="export.products.pdf"
            :excel-params="['category_type' => 'packaging']" :pdf-params="['category_type' => 'packaging']" />
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50 border-b border-zinc-200 dark:border-zinc-700">
                    <tr>
                        <th
                            class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase border-r border-zinc-200 dark:border-zinc-700">
                            Kode</th>
                        <th
                            class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase border-r border-zinc-200 dark:border-zinc-700">
                            Nama</th>
                        <th
                            class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase border-r border-zinc-200 dark:border-zinc-700">
                            Stok</th>
                        <th
                            class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase border-r border-zinc-200 dark:border-zinc-700">
                            Min</th>
                        <th
                            class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase border-r border-zinc-200 dark:border-zinc-700">
                            Status</th>
                        <th
                            class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    @include('inventory.partials.packaging-table')
                </tbody>
            </table>
        </div>
        @if($products->hasPages())
            <div id="pagination"
                class="px-4 py-3 border-t border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50">
                {{ $products->links() }}
            </div>
        @else
            <div id="pagination" class="hidden"></div>
        @endif
    </div>

    @push('scripts')
        <script>
            function inventoryFilter() {
                return {
                    search: '{{ request('search') }}',
                    stock_status: '{{ request('stock_status') }}',

                    fetchData() {
                        const params = new URLSearchParams();
                        if (this.search) params.set('search', this.search);
                        if (this.stock_status) params.set('stock_status', this.stock_status);

                        const newUrl = '{{ route('inventory.packaging') }}' + (params.toString() ? '?' + params.toString() : '');
                        window.history.replaceState({}, '', newUrl);

                        fetch(newUrl, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                            .then(response => response.json())
                            .then(data => {
                                document.getElementById('table-body').innerHTML = data.html;
                                document.getElementById('pagination').innerHTML = data.pagination || '';
                                if (data.pagination) {
                                    document.getElementById('pagination').classList.remove('hidden');
                                } else {
                                    document.getElementById('pagination').classList.add('hidden');
                                }
                            })
                            .catch(err => console.error('Error fetching data:', err));
                    },

                    resetFilter() {
                        this.search = '';
                        this.stock_status = '';
                        this.fetchData();
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>