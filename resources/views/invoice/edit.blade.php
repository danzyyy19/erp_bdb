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
                            {{ $invoice->customer?->name ?? 'Walk-in' }}</p>
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
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-zinc-500 uppercase">No</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-zinc-500 uppercase">Produk
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-zinc-500 uppercase">Qty</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-zinc-500 uppercase">Harga
                                    Satuan</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-zinc-500 uppercase">Subtotal
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-zinc-500 uppercase">Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                            <template x-for="(item, index) in items" :key="index">
                                <tr>
                                    <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400" x-text="index + 1"></td>
                                    <td class="px-4 py-3">
                                        <select :name="'items[' + index + '][product_id]'" x-model="item.product_id"
                                            @change="calculateTotals()" required
                                            class="w-full px-2 py-1.5 rounded border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white text-sm">
                                            <option value="">-- Pilih Produk --</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}"
                                                    data-price="{{ $product->selling_price }}">{{ $product->name }}
                                                    ({{ $product->code }})</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" :name="'items[' + index + '][id]'" :value="item.id || ''">
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <input type="number" :name="'items[' + index + '][quantity]'"
                                            x-model="item.quantity" step="0.01" min="0.01" required
                                            @input="calculateTotals()"
                                            class="w-20 px-2 py-1 text-center rounded border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <input type="number" :name="'items[' + index + '][unit_price]'"
                                            x-model="item.unit_price" step="1" min="0" required
                                            @input="calculateTotals()"
                                            class="w-32 px-2 py-1 text-right rounded border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                                    </td>
                                    <td class="px-4 py-3 text-right font-semibold text-zinc-900 dark:text-white"
                                        x-text="formatRupiah(item.quantity * item.unit_price)">
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <button type="button" @click="removeItem(index)"
                                            class="p-1.5 text-red-500 hover:bg-red-100 dark:hover:bg-red-900/30 rounded transition-colors">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            <tr x-show="items.length === 0">
                                <td colspan="6" class="px-4 py-8 text-center text-zinc-500">
                                    Belum ada item. Klik "Tambah Item" untuk menambahkan.
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
                                unit_price: {{ $item->unit_price }}
                            },
                        @endforeach
                    ],
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
                            unit_price: 0
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
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>