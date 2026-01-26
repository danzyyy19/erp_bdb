<x-app-layout>
    @section('title', 'Buat Purchase Order')

    <style>
        /* Mobile Modal Style (Default) */
        #material-dropdown-content {
            position: fixed !important;
            inset: 0 !important;
            width: 100% !important;
            height: 100% !important;
            max-height: 100vh !important;
            border-radius: 0 !important;
            margin-top: 0 !important;
            z-index: 60 !important;
        }

        /* Desktop Dropdown Style */
        @media (min-width: 768px) {
            #material-dropdown-content {
                position: absolute !important;
                inset: auto !important;
                width: 100% !important;
                height: auto !important;
                max-height: 15rem !important; /* h-60 */
                border-radius: 0.5rem !important;
                margin-top: 0.25rem !important;
            }
        }
    </style>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-6">
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white mb-6">Buat Purchase Order Baru</h2>

            <form action="{{ route('purchases.store') }}" method="POST" x-data="purchaseForm()">
                @csrf

                <div class="grid grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Tanggal <span
                                class="text-red-500">*</span></label>
                        <input type="date" name="purchase_date" value="{{ old('purchase_date', date('Y-m-d')) }}"
                            required
                            class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Supplier <span
                                class="text-red-500">*</span></label>
                        <select name="supplier_id" required
                            class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                            <option value="">Pilih Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->code }} - {{ $supplier->name }}</option>
                            @endforeach
                        </select>
                        @error('supplier_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">PPN (%)</label>
                        <input type="number" name="tax_percentage" value="{{ old('tax_percentage', 11) }}" step="0.1"
                            min="0"
                            class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Catatan</label>
                    <textarea name="notes" rows="2" placeholder="Catatan untuk supplier..."
                        class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">{{ old('notes') }}</textarea>
                </div>

                <!-- Items -->
                <div class="border-t border-zinc-200 dark:border-zinc-700 pt-4 mb-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-zinc-900 dark:text-white">Material yang Diminta</h3>
                        <button type="button" @click="addItem()"
                            class="px-3 py-1 text-xs bg-blue-600 hover:bg-blue-700 text-white rounded-lg">+ Tambah
                            Item</button>
                    </div>

                    <template x-for="(item, index) in items" :key="index">
                        <div
                            class="mb-4 bg-zinc-50 dark:bg-zinc-800/50 p-4 rounded-lg md:bg-transparent md:p-0 md:mb-2 md:flex md:gap-3 md:items-start border border-zinc-200 dark:border-zinc-700 md:border-none">

                            <div class="mb-3 md:mb-0 md:flex-1 relative" x-data="{ open: false, search: '' }"
                                @click.away="open = false">
                                <label class="block text-xs font-medium text-zinc-500 mb-1 md:hidden">Cari
                                    Material</label>
                                <button type="button" @click="open = !open"
                                    class="w-full flex items-center justify-between px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-left"
                                    :class="open && 'ring-2 ring-blue-500'">
                                    <span :class="item.product_id ? 'text-zinc-900 dark:text-white' : 'text-zinc-400'"
                                        x-text="item.product_id ? products.find(p => p.id == item.product_id)?.text || 'Pilih material...' : 'Pilih material...'"></span>
                                    <i data-lucide="chevron-down" class="w-4 h-4 text-zinc-400"></i>
                                </button>
                                <input type="hidden" :name="'items['+index+'][product_id]'" x-model="item.product_id"
                                    required>

                                <!-- Dropdown / Mobile Modal -->
                                <div id="material-dropdown-content" x-show="open" x-transition
                                     class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 shadow-xl overflow-hidden overflow-y-auto">

                                    <!-- Mobile Header -->
                                    <div
                                        class="md:hidden flex items-center justify-between p-3 border-b border-zinc-200 dark:border-zinc-700 sticky top-0 bg-white dark:bg-zinc-800 z-10">
                                        <span class="font-semibold text-zinc-900 dark:text-white">Pilih Material</span>
                                        <button type="button" @click="open = false" class="p-1 text-zinc-500"><i
                                                data-lucide="x" class="w-5 h-5"></i></button>
                                    </div>

                                    <div
                                        class="p-2 border-b border-zinc-200 dark:border-zinc-700 sticky top-[50px] md:top-0 bg-white dark:bg-zinc-800 z-10">
                                        <input type="text" x-model="search" placeholder="Cari material..."
                                            class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-600 bg-zinc-50 dark:bg-zinc-900 text-zinc-900 dark:text-white"
                                            x-ref="searchInput">
                                    </div>
                                    <div class="max-h-[calc(100vh-120px)] md:max-h-48 overflow-y-auto p-2 md:p-0">
                                        <template
                                            x-for="product in products.filter(p => !search || p.text.toLowerCase().includes(search.toLowerCase()))"
                                            :key="product.id">
                                            <button type="button"
                                                @click="item.product_id = product.id; open = false; search = ''"
                                                class="w-full px-3 py-3 text-left hover:bg-zinc-100 dark:hover:bg-zinc-700 border-b border-zinc-100 dark:border-zinc-700/50 rounded-lg md:rounded-none mb-1 md:mb-0"
                                                :class="item.product_id == product.id && 'bg-blue-50 dark:bg-blue-900/20 ring-1 ring-blue-500 md:ring-0'">
                                                <div class="font-medium text-sm text-zinc-900 dark:text-white"
                                                    x-text="product.text"></div>
                                                <div class="text-xs text-zinc-500 mt-0.5">Stok: <span
                                                        x-text="product.stock"></span> <span
                                                        x-text="product.unit"></span></div>
                                            </button>
                                        </template>
                                        <div x-show="products.filter(p => !search || p.text.toLowerCase().includes(search.toLowerCase())).length === 0"
                                            class="p-4 text-center text-sm text-zinc-500">
                                            Tidak ditemukan
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3 md:flex md:gap-3">
                                <div class="w-full md:w-24">
                                    <label class="block text-xs font-medium text-zinc-500 mb-1 md:hidden">Qty</label>
                                    <input type="number" :name="'items['+index+'][quantity]'" step="0.01" min="0.01"
                                        required x-model="item.quantity" placeholder="Qty"
                                        class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                                </div>
                                <div class="w-full md:w-36">
                                    <label class="block text-xs font-medium text-zinc-500 mb-1 md:hidden">Harga
                                        Satuan</label>
                                    <input type="number" :name="'items['+index+'][unit_price]'" step="1" min="0"
                                        required x-model="item.unit_price" placeholder="Harga"
                                        class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                                </div>
                            </div>

                            <div class="flex justify-between items-center mt-3 md:mt-2 md:w-36 md:text-right md:block">
                                <label class="block text-xs font-medium text-zinc-500 md:hidden">Total</label>
                                <span class="text-sm font-medium text-zinc-900 dark:text-white"
                                    x-text="formatRupiah(item.quantity * item.unit_price || 0)"></span>
                            </div>

                            <div class="flex justify-end mt-2 md:mt-0">
                                <button type="button" @click="removeItem(index)" x-show="items.length > 1"
                                    class="p-2 text-red-500 hover:text-red-700 bg-red-50 md:bg-transparent rounded-lg md:rounded-none">
                                    <div class="flex items-center gap-1 md:block">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        <span class="text-xs font-medium md:hidden">Hapus</span>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                    <a href="{{ route('purchases.index') }}"
                        class="px-4 py-2 text-sm text-zinc-600 dark:text-zinc-400">Batal</a>
                    <button type="submit"
                        class="px-4 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function purchaseForm() {
                return {
                    items: [{ product_id: '', quantity: '', unit_price: '' }],
                    products: @js($products->map(fn($p) => ['id' => $p->id, 'text' => $p->code . ' - ' . $p->name, 'stock' => $p->current_stock, 'unit' => $p->unit])),
                    addItem() {
                        this.items.push({ product_id: '', quantity: '', unit_price: '' });
                    },
                    removeItem(index) {
                        this.items.splice(index, 1);
                    },
                    formatRupiah(amount) {
                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>