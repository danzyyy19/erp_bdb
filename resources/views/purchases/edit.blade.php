<x-app-layout>
    @section('title', 'Edit Purchase Order')

    <div class="max-w-4xl mx-auto">
        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-6">
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white mb-6">Edit PO:
                {{ $purchase->purchase_number }}</h2>

            <form action="{{ route('purchases.update', $purchase) }}" method="POST" x-data="purchaseForm()">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Tanggal <span
                                class="text-red-500">*</span></label>
                        <input type="date" name="purchase_date"
                            value="{{ old('purchase_date', $purchase->purchase_date->format('Y-m-d')) }}" required
                            class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Supplier <span
                                class="text-red-500">*</span></label>
                        <select name="supplier_id" required
                            class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                            <option value="">Pilih Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id', $purchase->supplier_id) == $supplier->id ? 'selected' : '' }}>{{ $supplier->code }} -
                                    {{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">PPN (%)</label>
                        <input type="number" name="tax_percentage"
                            value="{{ old('tax_percentage', $purchase->tax_percentage) }}" step="0.1" min="0"
                            class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Catatan</label>
                    <textarea name="notes" rows="2"
                        class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">{{ old('notes', $purchase->notes) }}</textarea>
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
                        <div class="flex gap-3 mb-3 items-start">
                            <!-- Searchable Material Select -->
                            <div class="flex-1 relative" x-data="{ open: false, search: '' }"
                                @click.away="open = false">
                                <button type="button" @click="open = !open"
                                    class="w-full flex items-center justify-between px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-left"
                                    :class="open && 'ring-2 ring-blue-500'">
                                    <span :class="item.product_id ? 'text-zinc-900 dark:text-white' : 'text-zinc-400'"
                                        x-text="item.product_id ? products.find(p => p.id == item.product_id)?.text || 'Pilih material...' : 'Pilih material...'"></span>
                                    <i data-lucide="chevron-down" class="w-4 h-4 text-zinc-400"></i>
                                </button>
                                <input type="hidden" :name="'items['+index+'][product_id]'" x-model="item.product_id"
                                    required>

                                <div x-show="open" x-transition
                                    class="absolute z-50 w-full mt-1 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg shadow-xl overflow-hidden">
                                    <div class="p-2 border-b border-zinc-200 dark:border-zinc-700">
                                        <input type="text" x-model="search" placeholder="Cari material..."
                                            class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-600 bg-zinc-50 dark:bg-zinc-900 text-zinc-900 dark:text-white">
                                    </div>
                                    <div class="max-h-48 overflow-y-auto">
                                        <template
                                            x-for="product in products.filter(p => !search || p.text.toLowerCase().includes(search.toLowerCase()))"
                                            :key="product.id">
                                            <button type="button"
                                                @click="item.product_id = product.id; open = false; search = ''"
                                                class="w-full px-3 py-2 text-left hover:bg-zinc-100 dark:hover:bg-zinc-700 border-b border-zinc-100 dark:border-zinc-700/50"
                                                :class="item.product_id == product.id && 'bg-blue-50 dark:bg-blue-900/20'">
                                                <div class="font-medium text-sm text-zinc-900 dark:text-white"
                                                    x-text="product.text"></div>
                                                <div class="text-xs text-zinc-500">Stok: <span
                                                        x-text="product.stock"></span> <span
                                                        x-text="product.unit"></span></div>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <div class="w-24">
                                <input type="number" :name="'items['+index+'][quantity]'" step="0.01" min="0.01"
                                    required x-model="item.quantity" placeholder="Qty"
                                    class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                            </div>
                            <div class="w-36">
                                <input type="number" :name="'items['+index+'][unit_price]'" step="1" min="0" required
                                    x-model="item.unit_price" placeholder="Harga"
                                    class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                            </div>
                            <div class="w-36 text-right pt-2 text-sm text-zinc-600 dark:text-zinc-400">
                                <span x-text="formatRupiah(item.quantity * item.unit_price || 0)"></span>
                            </div>
                            <button type="button" @click="removeItem(index)" x-show="items.length > 1"
                                class="p-2 text-red-500 hover:text-red-700">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </template>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                    <a href="{{ route('purchases.show', $purchase) }}"
                        class="px-4 py-2 text-sm text-zinc-600 dark:text-zinc-400">Batal</a>
                    <button type="submit"
                        class="px-4 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Update</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function purchaseForm() {
                return {
                    items: @js($purchase->items->map(fn($i) => ['product_id' => $i->product_id, 'quantity' => (string) $i->quantity, 'unit_price' => (string) $i->unit_price])->values()),
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