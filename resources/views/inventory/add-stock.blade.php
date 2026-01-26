<x-app-layout>
    @section('title', 'Tambah Stok')

    <div class="max-w-2xl mx-auto">
        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-6">
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white mb-6">
                <i data-lucide="plus-circle" class="w-5 h-5 inline mr-2"></i>
                Tambah Stok Produk
            </h2>

            @if(session('success'))
                <div
                    class="mb-4 p-3 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('inventory.add-stock.store') }}" method="POST" class="space-y-4"
                x-data="stockForm()">
                @csrf

                <!-- Product Selection with Search -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                        Pilih Produk <span class="text-red-500">*</span>
                    </label>

                    <!-- Searchable Product Select -->
                    <div class="relative" @click.away="open = false">
                        <button type="button" @click="open = !open"
                            class="w-full flex items-center justify-between px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-left"
                            :class="open && 'ring-2 ring-blue-500'">
                            <span :class="selectedProduct ? 'text-zinc-900 dark:text-white' : 'text-zinc-400'"
                                x-text="selectedProduct ? products.find(p => p.id == selectedProduct)?.text || 'Pilih produk...' : 'Pilih produk...'"></span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-zinc-400"></i>
                        </button>
                        <input type="hidden" name="product_id" x-model="selectedProduct" required>

                        <div x-show="open" x-transition
                            class="absolute z-50 w-full mt-1 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg shadow-xl overflow-hidden">
                            <div class="p-2 border-b border-zinc-200 dark:border-zinc-700">
                                <input type="text" x-model="search" placeholder="Cari produk..."
                                    class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-600 bg-zinc-50 dark:bg-zinc-900 text-zinc-900 dark:text-white">
                            </div>
                            <div class="max-h-60 overflow-y-auto">
                                <template
                                    x-for="product in products.filter(p => !search || p.text.toLowerCase().includes(search.toLowerCase()))"
                                    :key="product.id">
                                    <button type="button" @click="selectProduct(product)"
                                        class="w-full px-3 py-2 text-left hover:bg-zinc-100 dark:hover:bg-zinc-700 border-b border-zinc-100 dark:border-zinc-700/50"
                                        :class="selectedProduct == product.id && 'bg-blue-50 dark:bg-blue-900/20'">
                                        <div class="font-medium text-sm text-zinc-900 dark:text-white"
                                            x-text="product.text"></div>
                                        <div class="text-xs text-zinc-500">Stok: <span x-text="product.stock"></span>
                                            <span x-text="product.unit"></span></div>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                    @error('product_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Product Info -->
                <div x-show="selectedProduct" class="p-3 rounded-lg bg-zinc-50 dark:bg-zinc-800 text-sm">
                    <div class="flex justify-between">
                        <span class="text-zinc-500 dark:text-zinc-400">Kode:</span>
                        <span class="font-medium text-zinc-900 dark:text-white" x-text="productCode"></span>
                    </div>
                    <div class="flex justify-between mt-1">
                        <span class="text-zinc-500 dark:text-zinc-400">Stok Saat Ini:</span>
                        <span class="font-medium text-zinc-900 dark:text-white"><span x-text="currentStock"></span>
                            <span x-text="productUnit"></span></span>
                    </div>
                </div>

                <!-- Quantity -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                        Jumlah <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="quantity" step="0.01" min="0.01" required
                            value="{{ old('quantity') }}"
                            class="flex-1 px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                        <span class="text-sm text-zinc-500 dark:text-zinc-400 w-16"
                            x-text="productUnit || 'unit'"></span>
                    </div>
                    @error('quantity')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Notes -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                        Catatan
                    </label>
                    <textarea name="notes" rows="3" placeholder="Contoh: Pembelian dari supplier X, PO-123456"
                        class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">{{ old('notes') }}</textarea>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                    <a href="{{ route('inventory.index') }}"
                        class="px-4 py-2 text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-900">Batal</a>
                    <button type="submit"
                        class="px-4 py-2 text-sm bg-green-600 hover:bg-green-700 text-white rounded-lg">
                        <i data-lucide="plus" class="w-4 h-4 inline mr-1"></i>
                        Tambah Stok
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function stockForm() {
                return {
                    open: false,
                    search: '',
                    selectedProduct: '',
                    productCode: '',
                    productUnit: '',
                    currentStock: 0,
                    products: @js($products->map(fn($p) => ['id' => $p->id, 'code' => $p->code, 'text' => $p->code . ' - ' . $p->name, 'stock' => $p->current_stock, 'unit' => $p->unit])),

                    selectProduct(product) {
                        this.selectedProduct = product.id;
                        this.productCode = product.code;
                        this.productUnit = product.unit;
                        this.currentStock = product.stock;
                        this.open = false;
                        this.search = '';
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>