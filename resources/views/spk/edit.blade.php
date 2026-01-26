<x-app-layout>
    @section('title', 'Edit SPK')

    <form method="POST" action="{{ route('spk.update', $spk) }}" class="space-y-6" x-data="spkForm()" x-cloak>
        @csrf
        @method('PUT')

        {{-- Error Messages --}}
        @if ($errors->any())
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                <h3 class="text-sm font-medium text-red-800 dark:text-red-200 mb-2">Terjadi kesalahan:</h3>
                <ul class="list-disc list-inside text-sm text-red-700 dark:text-red-300 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- SPK Info (Read-only) -->
                <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-5">
                    <h3 class="font-semibold text-sm text-zinc-900 dark:text-white mb-4">Informasi SPK</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1">No
                                SPK</label>
                            <p class="font-mono text-sm text-zinc-900 dark:text-white">{{ $spk->spk_number }}</p>
                        </div>
                        <div>
                            <label
                                class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1">Status</label>
                            <span
                                class="px-2 py-0.5 text-xs font-medium rounded bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-400">{{ $spk->status_label }}</span>
                        </div>
                    </div>
                </div>

                <!-- Basic Info -->
                <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-5">
                    <h3 class="font-semibold text-sm text-zinc-900 dark:text-white mb-4">Informasi Dasar</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Tanggal
                                Produksi *</label>
                            <input type="date" name="production_date"
                                value="{{ old('production_date', $spk->production_date?->format('Y-m-d')) }}" required
                                class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                        </div>
                        <div>
                            <label
                                class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Deadline</label>
                            <input type="date" name="deadline"
                                value="{{ old('deadline', $spk->deadline?->format('Y-m-d')) }}"
                                class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                        </div>
                        <div class="md:col-span-2">
                            <label
                                class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Catatan</label>
                            <textarea name="notes" rows="2"
                                class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">{{ old('notes', $spk->notes) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Output (Barang Jadi) -->
                <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-5">
                    <h3 class="font-semibold text-sm text-zinc-900 dark:text-white mb-4">Output (Barang Jadi) *</h3>
                    <div class="space-y-2">
                        <template x-for="(item, index) in outputItems" :key="'out-'+index">
                            <div class="flex gap-2 items-start p-3 bg-zinc-50 dark:bg-zinc-800/50 rounded-lg">
                                <div class="flex-1 relative" @click.away="item.showDropdown = false">
                                    <input type="text" x-model="item.search" @input="item.showDropdown = true"
                                        @focus="item.showDropdown = true" placeholder="Ketik untuk cari barang jadi..."
                                        class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                                    <input type="hidden" :name="'output['+index+'][product_id]'"
                                        :value="item.product_id">

                                    <div x-show="item.showDropdown && filterProducts(item.search).length > 0"
                                        x-transition
                                        class="absolute z-50 w-full mt-1 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg shadow-lg max-h-40 overflow-y-auto">
                                        <template x-for="opt in filterProducts(item.search)" :key="opt.id">
                                            <div @click="selectProduct(item, opt)"
                                                class="px-3 py-2 text-sm cursor-pointer hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-900 dark:text-white">
                                                <span x-text="opt.name + ' (' + opt.code + ')'"></span>
                                                <span class="text-xs text-zinc-500 ml-2"
                                                    x-text="'[' + opt.unit + ']'"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <!-- Quantity -->
                                <div class="w-24">
                                    <input type="number" :name="'output['+index+'][quantity]'" x-model="item.quantity"
                                        step="any" min="0.01" placeholder="Qty" required
                                        class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                                </div>

                                <!-- Unit Display (Read-only) -->
                                <div class="w-20">
                                    <input type="text" :value="item.unit" readonly
                                        class="w-full px-2 py-2 text-sm rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-700 text-zinc-600 dark:text-zinc-400 text-center">
                                    <input type="hidden" :name="'output['+index+'][unit]'" :value="item.unit">
                                </div>

                                <button type="button" @click="outputItems.splice(index, 1)"
                                    x-show="outputItems.length > 1"
                                    class="px-2 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </template>
                    </div>
                    <button type="button" @click="addOutput()"
                        class="mt-3 px-3 py-1.5 text-sm text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg flex items-center gap-1">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        Tambah Item
                    </button>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <div
                    class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-5 lg:sticky lg:top-20">
                    <h3 class="font-semibold text-sm text-zinc-900 dark:text-white mb-4">Ringkasan</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-zinc-500">No. SPK</span>
                            <span class="font-medium text-zinc-900 dark:text-white">{{ $spk->spk_number }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-zinc-500">Output</span>
                            <span class="font-medium text-zinc-900 dark:text-white"
                                x-text="outputItems.filter(i => i.product_id).length + ' item'"></span>
                        </div>
                    </div>

                    <!-- Validation warning -->
                    <div x-show="outputItems.filter(i => i.product_id && i.quantity).length === 0"
                        class="mt-4 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                        <p class="text-xs text-yellow-700 dark:text-yellow-400">Tambahkan minimal 1 output barang jadi
                        </p>
                    </div>

                    <button type="submit" @click="validateForm($event)"
                        class="w-full mt-5 py-2.5 px-4 bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium rounded-lg transition-colors">
                        Update SPK
                    </button>
                    <a href="{{ route('spk.show', $spk) }}"
                        class="block w-full mt-2 py-2 px-4 text-center text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white">Batal</a>
                </div>
            </div>
        </div>
    </form>

    @push('scripts')
        <script>
            function spkForm() {
                return {
                    outputOptions: @json($barangJadi ?? []),
                    outputItems: @json($outputItemsData ?? []),

                    filterProducts(search) {
                        if (!Array.isArray(this.outputOptions)) return [];
                        if (!search) return this.outputOptions.slice(0, 10);
                        const s = search.toLowerCase();
                        return this.outputOptions.filter(o => o.name.toLowerCase().includes(s) || o.code.toLowerCase().includes(s)).slice(0, 10);
                    },

                    selectProduct(item, opt) {
                        item.product_id = opt.id;
                        item.search = opt.name + ' (' + opt.code + ')';
                        item.unit = opt.unit; // Auto-set unit from product
                        item.showDropdown = false;
                    },

                    addOutput() {
                        this.outputItems.push({ product_id: '', quantity: '', unit: '', search: '', showDropdown: false });
                        this.$nextTick(() => lucide.createIcons());
                    },

                    validateForm(event) {
                        const validOutput = this.outputItems.filter(item => item.product_id && item.quantity);
                        if (validOutput.length === 0) {
                            event.preventDefault();
                            alert('Pilih minimal 1 output barang jadi!');
                            return false;
                        }
                        return true;
                    }
                }
            }
        </script>
    @endpush

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</x-app-layout>