<x-app-layout>
    @section('title', 'Edit Job Cost')

    <div class="max-w-3xl mx-auto">
        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-6">
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white mb-6">Edit Job Cost:
                {{ $jobCost->job_cost_number }}</h2>

            <form action="{{ route('job-costs.update', $jobCost) }}" method="POST" x-data="jobCostForm()">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Tanggal <span
                                class="text-red-500">*</span></label>
                        <input type="date" name="date" value="{{ old('date', $jobCost->date->format('Y-m-d')) }}"
                            required
                            class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Deskripsi <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="description" value="{{ old('description', $jobCost->description) }}"
                            required
                            class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Catatan</label>
                    <textarea name="notes" rows="2"
                        class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">{{ old('notes', $jobCost->notes) }}</textarea>
                </div>

                <!-- Items -->
                <div class="border-t border-zinc-200 dark:border-zinc-700 pt-4 mb-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-zinc-900 dark:text-white">Item Produk</h3>
                        <button type="button" @click="addItem()"
                            class="px-3 py-1 text-xs bg-blue-600 hover:bg-blue-700 text-white rounded-lg">+ Tambah
                            Item</button>
                    </div>

                    <template x-for="(item, index) in items" :key="index">
                        <div class="flex gap-3 mb-3 items-start">
                            <!-- Searchable Product Select -->
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

                            <div class="w-28">
                                <input type="number" :name="'items['+index+'][quantity]'" step="0.01" min="0.01"
                                    required x-model="item.quantity" placeholder="Qty"
                                    class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                            </div>
                            <div class="flex-1">
                                <input type="text" :name="'items['+index+'][notes]'" x-model="item.notes"
                                    placeholder="Catatan"
                                    class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                            </div>
                            <button type="button" @click="removeItem(index)" x-show="items.length > 1"
                                class="p-2 text-red-500 hover:text-red-700">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </template>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                    <a href="{{ route('job-costs.show', $jobCost) }}"
                        class="px-4 py-2 text-sm text-zinc-600 dark:text-zinc-400">Batal</a>
                    <button type="submit"
                        class="px-4 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Update</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function jobCostForm() {
                return {
                    items: @js($jobCost->items->map(fn($i) => ['product_id' => $i->product_id, 'quantity' => (string) $i->quantity, 'notes' => $i->notes ?? ''])->values()),
                    products: @js($products->map(fn($p) => ['id' => $p->id, 'text' => $p->code . ' - ' . $p->name, 'stock' => $p->current_stock, 'unit' => $p->unit])),
                    addItem() {
                        this.items.push({ product_id: '', quantity: '', notes: '' });
                    },
                    removeItem(index) {
                        this.items.splice(index, 1);
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>