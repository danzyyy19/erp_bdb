<x-app-layout>
    @section('title', 'Riwayat Stok')

    <!-- Filters -->
    <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-4 mb-4"
        x-data="stockHistoryFilter()">
        <div class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[200px]" x-data="productSearchFilter()">
                <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Produk</label>
                <div class="relative">
                    <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-zinc-400"></i>
                    <input type="text" x-model="searchText" @input="filterProducts()" @focus="showDropdown = true"
                        @click.away="showDropdown = false" placeholder="Cari produk..."
                        class="w-full pl-10 pr-4 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white"
                        autocomplete="off">

                    <!-- Dropdown Results -->
                    <div x-show="showDropdown && filteredProducts.length > 0" x-cloak
                        class="absolute z-50 w-full mt-1 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg shadow-lg max-h-48 overflow-y-auto">
                        <div @click="selectProduct(null)"
                            class="px-3 py-2 text-sm cursor-pointer hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-500">
                            Semua Produk
                        </div>
                        <template x-for="product in filteredProducts" :key="product.id">
                            <div @click="selectProduct(product)"
                                class="px-3 py-2 text-sm cursor-pointer hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-900 dark:text-white">
                                <span x-text="product.name"></span>
                                <span class="text-xs text-zinc-500" x-text="' (' + product.code + ')'"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
            <div class="w-32">
                <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Tipe</label>
                <select x-model="type" @change="fetchData()"
                    class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                    <option value="">Semua</option>
                    <option value="in">Masuk</option>
                    <option value="out">Keluar</option>
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
            <template x-if="product_id || type || date_from || date_to">
                <button type="button" @click="resetFilter()"
                    class="px-3 py-2 text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white">Reset</button>
            </template>
        </div>
    </div>

    <!-- Table -->
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
                            class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase border-r border-zinc-200 dark:border-zinc-700">
                            User</th>
                        <th
                            class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase border-r border-zinc-200 dark:border-zinc-700">
                            Catatan</th>
                        <th
                            class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    @include('inventory.partials.stock-history-table')
                </tbody>
            </table>
        </div>
        @if($movements->hasPages())
            <div id="pagination"
                class="px-4 py-3 border-t border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50">
                {{ $movements->links() }}
            </div>
        @else
            <div id="pagination" class="hidden"></div>
        @endif
    </div>

    @push('scripts')
        <script>
            function stockHistoryFilter() {
                return {
                    product_id: '{{ request('product_id') }}',
                    type: '{{ request('type') }}',
                    date_from: '{{ request('date_from') }}',
                    date_to: '{{ request('date_to') }}',

                    fetchData() {
                        const params = new URLSearchParams();
                        if (this.product_id) params.set('product_id', this.product_id);
                        if (this.type) params.set('type', this.type);
                        if (this.date_from) params.set('date_from', this.date_from);
                        if (this.date_to) params.set('date_to', this.date_to);

                        const newUrl = '{{ route('inventory.stock-history') }}' + (params.toString() ? '?' + params.toString() : '');
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
                        this.product_id = '';
                        this.type = '';
                        this.date_from = '';
                        this.date_to = '';
                        this.fetchData();
                    }
                }
            }

            const allProducts = @json($products);

            function productSearchFilter() {
                return {
                    searchText: '',
                    showDropdown: false,
                    filteredProducts: [],

                    init() {
                        this.filteredProducts = allProducts.slice(0, 10);
                    },

                    filterProducts() {
                        if (this.searchText.length === 0) {
                            this.filteredProducts = allProducts.slice(0, 10);
                        } else {
                            const searchLower = this.searchText.toLowerCase();
                            this.filteredProducts = allProducts.filter(p =>
                                p.name.toLowerCase().includes(searchLower) ||
                                p.code.toLowerCase().includes(searchLower)
                            ).slice(0, 10);
                        }
                        this.showDropdown = true;
                    },

                    selectProduct(product) {
                        const parent = Alpine.$data(this.$root.closest('[x-data="stockHistoryFilter()"]'));
                        if (product) {
                            parent.product_id = product.id;
                            this.searchText = product.name;
                        } else {
                            parent.product_id = '';
                            this.searchText = '';
                        }
                        this.showDropdown = false;
                        parent.fetchData();
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>