<x-app-layout>
    @section('title', 'Edit Invoice')

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">Edit Invoice</h2>
                <p class="text-zinc-500 dark:text-zinc-400 mt-1">{{ $invoice->invoice_number }}</p>
            </div>
            <a href="{{ route('invoice.show', $invoice) }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-zinc-200 hover:bg-zinc-300 dark:bg-zinc-700 dark:hover:bg-zinc-600 text-zinc-900 dark:text-white font-medium rounded-lg transition-colors">
                Kembali
            </a>
        </div>
    </x-slot>

    <div x-data="invoiceEditForm()" class="space-y-6">
        <form method="POST" action="{{ route('invoice.update', $invoice) }}">
            @csrf
            @method('PUT')

            {{-- Reference Info (Read Only) --}}
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6 mb-6">
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Informasi Customer</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div class="bg-zinc-50 dark:bg-zinc-800/50 rounded-lg p-4">
                        <p class="text-zinc-500 dark:text-zinc-400">Customer</p>
                        <p class="font-semibold text-zinc-900 dark:text-white">
                            {{ $invoice->customer?->name ?? 'Walk-in' }}
                        </p>
                        <input type="hidden" name="customer_id" value="{{ $invoice->customer_id }}">
                    </div>
                    @if($invoice->deliveryNote)
                        <div class="bg-zinc-50 dark:bg-zinc-800/50 rounded-lg p-4">
                            <p class="text-zinc-500 dark:text-zinc-400">Surat Jalan</p>
                            <p class="font-semibold text-zinc-900 dark:text-white">{{ $invoice->deliveryNote->sj_number }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Invoice Details --}}
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6 mb-6">
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Detail Invoice</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Tanggal Invoice
                            *</label>
                        <input type="date" name="invoice_date"
                            value="{{ old('invoice_date', $invoice->invoice_date->format('Y-m-d')) }}" required
                            class="w-full px-4 py-2 rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Jatuh
                            Tempo</label>
                        <input type="date" name="due_date"
                            value="{{ old('due_date', $invoice->due_date?->format('Y-m-d')) }}"
                            class="w-full px-4 py-2 rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">PPN (%)</label>
                        <input type="number" name="tax_percent" value="{{ old('tax_percent', $invoice->tax_percent) }}"
                            step="0.01" min="0" max="100" x-model="taxPercent" @input="calculateTotals()"
                            class="w-full px-4 py-2 rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Diskon
                            (Rp)</label>
                        <input type="number" name="discount" value="{{ old('discount', $invoice->discount) }}" step="1"
                            min="0" x-model="discount" @input="calculateTotals()"
                            class="w-full px-4 py-2 rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Catatan</label>
                        <textarea name="notes" rows="2"
                            class="w-full px-4 py-2 rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white resize-none">{{ old('notes', $invoice->notes) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Items --}}
            <div
                class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 overflow-hidden mb-6">
                <div class="p-6 border-b border-zinc-200 dark:border-zinc-700 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Item Barang</h3>
                        <p class="text-sm text-zinc-500">Tambah atau edit item invoice.</p>
                    </div>
                    <button type="button" @click="addItem()"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        Tambah Item
                    </button>
                </div>
                <div class="space-y-3">
                    <template x-for="(item, index) in items" :key="index">
                        <div
                            class="bg-zinc-50 dark:bg-zinc-800/50 rounded-lg p-4 border border-zinc-200 dark:border-zinc-700 md:flex md:gap-4 md:items-start">
                            <!-- Mobile Header: Number & Remove -->
                            <div class="flex justify-between items-center mb-3 md:hidden">
                                <span class="text-xs font-semibold text-zinc-500">Item #<span
                                        x-text="index + 1"></span></span>
                                <button type="button" @click="removeItem(index)" class="text-red-500">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>

                            <!-- Number (Desktop) -->
                            <div class="hidden md:block md:w-8 md:pt-2 text-center text-zinc-500 text-sm"
                                x-text="index + 1"></div>

                            <!-- Product Select -->
                            <div class="w-full md:flex-1 relative" @click.away="item.showDropdown = false">
                                <label class="block text-xs font-medium text-zinc-500 mb-1 md:hidden">Produk</label>
                                <div class="relative">
                                    <input type="text" x-model="item.search" @click="item.showDropdown = true"
                                        @focus="item.showDropdown = true" placeholder="Cari produk..."
                                        class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                                    <input type="hidden" :name="'items[' + index + '][product_id]'"
                                        :value="item.product_id">
                                    <input type="hidden" :name="'items[' + index + '][id]'" :value="item.id || ''">

                                    <!-- Dropdown/Modal -->
                                    <div x-show="item.showDropdown" x-transition
                                        class="fixed inset-0 z-[60] bg-white dark:bg-zinc-800 md:absolute md:z-50 md:inset-auto md:w-full md:mt-1 md:rounded-lg md:shadow-lg md:border md:border-zinc-200 md:dark:border-zinc-700 md:max-h-60 overflow-hidden flex flex-col">

                                        <!-- Mobile Header -->
                                        <div
                                            class="flex items-center justify-between p-3 border-b border-zinc-200 dark:border-zinc-700 md:hidden">
                                            <span class="font-semibold text-zinc-900 dark:text-white">Pilih
                                                Produk</span>
                                            <button type="button" @click="item.showDropdown = false"
                                                class="p-1 text-zinc-500"><i data-lucide="x"
                                                    class="w-5 h-5"></i></button>
                                        </div>

                                        <!-- Search (Mobile) -->
                                        <div
                                            class="p-2 border-b border-zinc-200 dark:border-zinc-700 md:hidden bg-zinc-50 dark:bg-zinc-900">
                                            <input type="text" x-model="item.search" placeholder="Cari..."
                                                class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white"
                                                @click.stop>
                                        </div>

                                        <div class="overflow-y-auto flex-1 md:max-h-60 p-2 md:p-0">
                                            <template x-for="p in filterProducts(item.search)" :key="p.id">
                                                <div @click="selectProduct(item, p)"
                                                    class="px-3 py-3 md:py-2 text-sm cursor-pointer hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-900 dark:text-white border-b border-zinc-100 dark:border-zinc-800 md:border-none">
                                                    <div class="font-medium" x-text="p.name"></div>
                                                    <div class="text-xs text-zinc-500 flex justify-between">
                                                        <span x-text="p.code"></span>
                                                        <span x-text="formatRupiah(p.price)"></span>
                                                    </div>
                                                </div>
                                            </template>
                                            <div x-show="filterProducts(item.search).length === 0"
                                                class="p-4 text-center text-sm text-zinc-500">Tidak ditemukan</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Qty & Price Row on Mobile -->
                            <div class="grid grid-cols-2 gap-3 mt-3 md:mt-0 md:flex md:w-auto">
                                <div class="md:w-24">
                                    <label class="block text-xs font-medium text-zinc-500 mb-1 md:hidden">Qty</label>
                                    <input type="number" :name="'items[' + index + '][quantity]'"
                                        x-model="item.quantity" step="0.01" min="0.01" required
                                        @input="calculateTotals()"
                                        class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white text-center">
                                </div>
                                <div class="md:w-32">
                                    <label class="block text-xs font-medium text-zinc-500 mb-1 md:hidden">Harga</label>
                                    <input type="number" :name="'items[' + index + '][unit_price]'"
                                        x-model="item.unit_price" step="1" min="0" required @input="calculateTotals()"
                                        class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white text-right">
                                </div>
                            </div>

                            <!-- Subtotal -->
                            <div class="mt-3 md:mt-0 md:w-32 md:pt-2 md:text-right flex justify-between md:block">
                                <span class="text-sm font-medium text-zinc-500 md:hidden">Subtotal</span>
                                <span class="text-sm font-semibold text-zinc-900 dark:text-white"
                                    x-text="formatRupiah(item.quantity * item.unit_price)"></span>
                            </div>

                            <!-- Desktop Remove -->
                            <div class="hidden md:block md:w-10 md:pt-2 text-center">
                                <button type="button" @click="removeItem(index)"
                                    class="text-red-500 hover:bg-red-100 dark:hover:bg-red-900/30 p-1.5 rounded transition-colors">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                    <div x-show="items.length === 0"
                        class="p-8 text-center text-zinc-500 border-2 border-dashed border-zinc-200 dark:border-zinc-700 rounded-lg">
                        Belum ada item. Klik "Tambah Item" untuk menambahkan.
                    </div>
                </div>
            </div>

            {{-- Totals --}}
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6 mb-6">
                <div class="flex justify-end">
                    <div class="w-full md:w-1/3 space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-zinc-500">Subtotal</span>
                            <span class="font-medium text-zinc-900 dark:text-white"
                                x-text="formatRupiah(subtotal)"></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-zinc-500">PPN (<span x-text="taxPercent"></span>%)</span>
                            <span class="font-medium text-zinc-900 dark:text-white"
                                x-text="formatRupiah(taxAmount)"></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-zinc-500">Diskon</span>
                            <span class="font-medium text-red-600"
                                x-text="'- ' + formatRupiah(Number(discount))"></span>
                        </div>
                        <div class="border-t border-zinc-200 dark:border-zinc-700 pt-3 flex justify-between">
                            <span class="font-semibold text-zinc-900 dark:text-white">Total</span>
                            <span class="font-bold text-xl text-blue-600 dark:text-blue-400"
                                x-text="formatRupiah(total)"></span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex justify-end gap-3">
                <a href="{{ route('invoice.show', $invoice) }}"
                    class="px-6 py-3 bg-zinc-200 hover:bg-zinc-300 dark:bg-zinc-700 dark:hover:bg-zinc-600 text-zinc-900 dark:text-white font-medium rounded-lg transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            function invoiceEditForm() {
                return {
                    items: [
                        @foreach($invoice->items as $item)
                                    {
                                id: {{ $item->id }},
                                product_id: {{ $item->product_id }},
                                quantity: {{ $item->quantity }},
                                unit_price: {{ $item->unit_price }},
                                search: '{{ $item->product->name }} ({{ $item->product->code }})',
                                showDropdown: false
                            },
                        @endforeach
                        ],
                    products: @json($products->map(fn($p) => [
                        'id' => $p->id,
                        'name' => $p->name,
                        'code' => $p->code,
                        'price' => $p->selling_price
                    ])),
                    taxPercent: {{ $invoice->tax_percent }},
                    discount: {{ $invoice->discount }},
                    subtotal: 0,
                    taxAmount: 0,
                    total: 0,

                    init() {
                        this.calculateTotals();
                        this.$nextTick(() => {
                            if (typeof lucide !== 'undefined') {
                                lucide.createIcons();
                            }
                        });
                    },

                    addItem() {
                        this.items.push({
                            id: null,
                            product_id: '',
                            quantity: 1,
                            unit_price: 0,
                            search: '',
                            showDropdown: false
                        });
                        this.$nextTick(() => {
                            if (typeof lucide !== 'undefined') {
                                lucide.createIcons();
                            }
                        });
                    },

                    removeItem(index) {
                        if (this.items.length > 0) {
                            this.items.splice(index, 1);
                            this.calculateTotals();
                        }
                    },

                    calculateTotals() {
                        this.subtotal = this.items.reduce((sum, item) => {
                            return sum + (Number(item.quantity) * Number(item.unit_price));
                        }, 0);

                        this.taxAmount = this.subtotal * (Number(this.taxPercent) / 100);
                        this.total = this.subtotal + this.taxAmount - Number(this.discount);
                    },

                    formatRupiah(amount) {
                        return 'Rp ' + Number(amount).toLocaleString('id-ID');
                    },

                    filterProducts(search) {
                        if (!search) return this.products.slice(0, 20);
                        const s = search.toLowerCase();
                        return this.products.filter(p => p.name.toLowerCase().includes(s) || p.code.toLowerCase().includes(s)).slice(0, 20);
                    },

                    selectProduct(item, product) {
                        item.product_id = product.id;
                        item.unit_price = product.price;
                        item.search = product.name + ' (' + product.code + ')';
                        item.showDropdown = false;
                        this.calculateTotals();
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>