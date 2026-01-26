<x-app-layout>
    @section('title', 'Buat Invoice dari Surat Jalan')

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">Buat Invoice</h2>
                <p class="text-zinc-500 dark:text-zinc-400 mt-1">Dari Surat Jalan {{ $deliveryNote->sj_number }}</p>
            </div>
            <a href="{{ route('delivery-notes.show', $deliveryNote) }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-zinc-200 hover:bg-zinc-300 dark:bg-zinc-700 dark:hover:bg-zinc-600 text-zinc-900 dark:text-white font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div x-data="invoiceForm()" class="space-y-6">
        <form method="POST" action="{{ route('invoice.store-from-delivery-note', $deliveryNote) }}">
            @csrf

            {{-- Reference Info --}}
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6 mb-6">
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Informasi Referensi</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div class="bg-zinc-50 dark:bg-zinc-800/50 rounded-lg p-4">
                        <p class="text-zinc-500 dark:text-zinc-400">Surat Jalan</p>
                        <p class="font-semibold text-zinc-900 dark:text-white">{{ $deliveryNote->sj_number }}</p>
                    </div>
                    <div class="bg-zinc-50 dark:bg-zinc-800/50 rounded-lg p-4">
                        <p class="text-zinc-500 dark:text-zinc-400">Customer</p>
                        <p class="font-semibold text-zinc-900 dark:text-white">{{ $deliveryNote->customer?->name ?? '-' }}</p>
                    </div>
                    <div class="bg-zinc-50 dark:bg-zinc-800/50 rounded-lg p-4">
                        <p class="text-zinc-500 dark:text-zinc-400">Tanggal Kirim</p>
                        <p class="font-semibold text-zinc-900 dark:text-white">{{ $deliveryNote->delivery_date?->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            {{-- Invoice Details --}}
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6 mb-6">
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Detail Invoice</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Tanggal Invoice *</label>
                        <input type="date" name="invoice_date" value="{{ date('Y-m-d') }}" required
                            class="w-full px-4 py-2 rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Jatuh Tempo</label>
                        <input type="date" name="due_date" value="{{ date('Y-m-d', strtotime('+30 days')) }}"
                            class="w-full px-4 py-2 rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">PPN (%)</label>
                        <input type="number" name="tax_percent" value="11" step="0.01" min="0" max="100"
                            x-model="taxPercent" @input="calculateTotals()"
                            class="w-full px-4 py-2 rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Diskon (Rp)</label>
                        <input type="number" name="discount" value="0" step="1" min="0"
                            x-model="discount" @input="calculateTotals()"
                            class="w-full px-4 py-2 rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Catatan</label>
                        <textarea name="notes" rows="2"
                            class="w-full px-4 py-2 rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white resize-none"></textarea>
                    </div>
                </div>
            </div>

            {{-- Items --}}
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 overflow-hidden mb-6">
                <div class="p-6 border-b border-zinc-200 dark:border-zinc-700">
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Item Barang</h3>
                    <p class="text-sm text-zinc-500">Isi harga satuan untuk setiap item</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-zinc-500 uppercase">No</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-zinc-500 uppercase">Produk</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-zinc-500 uppercase">Kode</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-zinc-500 uppercase">Qty</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-zinc-500 uppercase">Satuan</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-zinc-500 uppercase">Harga Satuan</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-zinc-500 uppercase">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                            @foreach($deliveryNote->items as $index => $item)
                                <tr>
                                    <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">
                                        {{ $item->product?->name ?? '-' }}
                                        <input type="hidden" name="items[{{ $index }}][product_id]" value="{{ $item->product_id }}">
                                    </td>
                                    <td class="px-4 py-3 text-center text-zinc-600 dark:text-zinc-400">{{ $item->product?->code ?? '-' }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <input type="number" name="items[{{ $index }}][quantity]" value="{{ $item->quantity }}" 
                                            step="0.01" min="0.01" required readonly
                                            x-model="items[{{ $index }}].quantity"
                                            class="w-20 px-2 py-1 text-center rounded border border-zinc-300 dark:border-zinc-600 bg-zinc-100 dark:bg-zinc-700 text-zinc-900 dark:text-white">
                                    </td>
                                    <td class="px-4 py-3 text-center text-zinc-600 dark:text-zinc-400">{{ $item->unit }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <input type="number" name="items[{{ $index }}][unit_price]" 
                                            value="{{ $item->product?->selling_price ?? 0 }}" 
                                            step="1" min="0" required
                                            x-model="items[{{ $index }}].unit_price"
                                            @input="calculateTotals()"
                                            class="w-32 px-2 py-1 text-right rounded border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                                    </td>
                                    <td class="px-4 py-3 text-right font-semibold text-zinc-900 dark:text-white" 
                                        x-text="formatRupiah(items[{{ $index }}].quantity * items[{{ $index }}].unit_price)">
                                        Rp 0
                                    </td>
                                </tr>
                            @endforeach
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
                            <span class="font-medium text-zinc-900 dark:text-white" x-text="formatRupiah(subtotal)"></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-zinc-500">PPN (<span x-text="taxPercent"></span>%)</span>
                            <span class="font-medium text-zinc-900 dark:text-white" x-text="formatRupiah(taxAmount)"></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-zinc-500">Diskon</span>
                            <span class="font-medium text-red-600" x-text="'- ' + formatRupiah(Number(discount))"></span>
                        </div>
                        <div class="border-t border-zinc-200 dark:border-zinc-700 pt-3 flex justify-between">
                            <span class="font-semibold text-zinc-900 dark:text-white">Total</span>
                            <span class="font-bold text-xl text-blue-600 dark:text-blue-400" x-text="formatRupiah(total)"></span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex justify-end gap-3">
                <a href="{{ route('delivery-notes.show', $deliveryNote) }}"
                    class="px-6 py-3 bg-zinc-200 hover:bg-zinc-300 dark:bg-zinc-700 dark:hover:bg-zinc-600 text-zinc-900 dark:text-white font-medium rounded-lg transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    Buat Invoice
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        function invoiceForm() {
            return {
                items: [
                    @foreach($deliveryNote->items as $item)
                    {
                        quantity: {{ $item->quantity }},
                        unit_price: {{ $item->product?->selling_price ?? 0 }}
                    },
                    @endforeach
                ],
                taxPercent: 11,
                discount: 0,
                subtotal: 0,
                taxAmount: 0,
                total: 0,

                init() {
                    this.calculateTotals();
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
