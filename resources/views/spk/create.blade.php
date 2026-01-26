<x-app-layout>
    @section('title', 'Buat SPK')

    <form method="POST" action="{{ route('spk.store') }}" class="space-y-6" x-data="spkForm()" x-cloak>
        @csrf

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
                <!-- Basic Info -->
                <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-5">
                    <h3 class="font-semibold text-sm text-zinc-900 dark:text-white mb-4">Informasi Dasar</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Tanggal
                                Produksi *</label>
                            <input type="date" name="production_date"
                                value="{{ old('production_date', date('Y-m-d')) }}" required
                                class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                        </div>
                        <div>
                            <label
                                class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Deadline</label>
                            <input type="date" name="deadline" value="{{ old('deadline') }}"
                                class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                        </div>
                        <div class="md:col-span-2">
                            <label
                                class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Catatan</label>
                            <textarea name="notes" rows="2"
                                class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Info: Material via FPB -->
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800 p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-blue-800 dark:text-blue-300">Bahan Baku & Material</p>
                            <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">Permintaan bahan baku dan
                                material/packaging dilakukan melalui menu <strong>FPB (Form Permintaan Barang)</strong>
                                setelah SPK dibuat dan disetujui.</p>
                        </div>
                    </div>
                </div>

                <!-- Output (Barang Jadi) -->
                <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-5">
                    <h3 class="font-semibold text-sm text-zinc-900 dark:text-white mb-4">Output (Barang Jadi) *</h3>
                    <div class="space-y-2">
                        <template x-for="(item, index) in outputItems" :key="'out-'+index">
                            <div
                                class="flex flex-col md:flex-row gap-3 items-start p-3 bg-zinc-50 dark:bg-zinc-800/50 rounded-lg border border-zinc-200 dark:border-zinc-700 md:border-none">
                                <!-- Product Select -->
                                <div class="w-full md:flex-1 relative" @click.away="item.showDropdown = false">
                                    <label class="block text-xs font-medium text-zinc-500 mb-1 md:hidden">Barang
                                        Jadi</label>
                                    <div class="relative">
                                        <input type="text" x-model="item.search" @click="item.showDropdown = true"
                                            @focus="item.showDropdown = true" placeholder="Cari barang jadi..."
                                            class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                                        <input type="hidden" :name="'output['+index+'][product_id]'"
                                            :value="item.product_id">

                                        <!-- Mobile Modal / Desktop Dropdown -->
                                        <div x-show="item.showDropdown" x-transition
                                            class="fixed inset-0 z-[60] bg-white dark:bg-zinc-800 md:absolute md:z-50 md:inset-auto md:w-full md:mt-1 md:rounded-lg md:shadow-lg md:border md:border-zinc-200 md:dark:border-zinc-700 md:max-h-60 overflow-hidden flex flex-col">

                                            <!-- Mobile Header -->
                                            <div
                                                class="flex items-center justify-between p-3 border-b border-zinc-200 dark:border-zinc-700 md:hidden">
                                                <span class="font-semibold text-zinc-900 dark:text-white">Pilih Barang
                                                    Jadi</span>
                                                <button type="button" @click="item.showDropdown = false"
                                                    class="p-1 text-zinc-500">
                                                    <i data-lucide="x" class="w-5 h-5"></i>
                                                </button>
                                            </div>

                                            <!-- Search Input (Mobile Sticky) -->
                                            <div
                                                class="p-2 border-b border-zinc-200 dark:border-zinc-700 md:hidden bg-zinc-50 dark:bg-zinc-900">
                                                <input type="text" x-model="item.search"
                                                    placeholder="Cari barang jadi..."
                                                    class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white"
                                                    @click.stop>
                                            </div>

                                            <!-- List -->
                                            <div class="overflow-y-auto flex-1 md:max-h-60 p-2 md:p-0">
                                                <template x-for="opt in filterProducts(item.search)" :key="opt.id">
                                                    <div @click="selectProduct(item, opt)"
                                                        class="px-3 py-3 md:py-2 text-sm cursor-pointer hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-900 dark:text-white border-b border-zinc-100 dark:border-zinc-800 md:border-none">
                                                        <div class="font-medium" x-text="opt.name"></div>
                                                        <div class="text-xs text-zinc-500 flex gap-2">
                                                            <span x-text="opt.code"></span>
                                                            <span>•</span>
                                                            <span x-text="opt.unit"></span>
                                                        </div>
                                                    </div>
                                                </template>
                                                <div x-show="filterProducts(item.search).length === 0"
                                                    class="p-4 text-center text-sm text-zinc-500">
                                                    Tidak ditemukan
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex gap-3 w-full md:w-auto">
                                    <!-- Quantity -->
                                    <div class="flex-1 md:w-24">
                                        <label
                                            class="block text-xs font-medium text-zinc-500 mb-1 md:hidden">Qty</label>
                                        <input type="number" :name="'output['+index+'][quantity]'"
                                            x-model="item.quantity" step="any" min="0.01" placeholder="Qty" required
                                            class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                                    </div>

                                    <!-- Unit Display (Read-only) -->
                                    <div class="w-20 md:w-20">
                                        <label
                                            class="block text-xs font-medium text-zinc-500 mb-1 md:hidden">Satuan</label>
                                        <input type="text" :value="item.unit" readonly
                                            class="w-full px-2 py-2 text-sm rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-700 text-zinc-600 dark:text-zinc-400 text-center">
                                        <input type="hidden" :name="'output['+index+'][unit]'" :value="item.unit">
                                    </div>
                                </div>

                                <!-- Remove Button -->
                                <div class="flex justify-end w-full md:w-auto mt-2 md:mt-0">
                                    <button type="button" @click="outputItems.splice(index, 1)"
                                        x-show="outputItems.length > 1"
                                        class="px-3 py-2 md:p-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg flex items-center gap-1">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        <span class="md:hidden">Hapus Item</span>
                                    </button>
                                </div>
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
                            <span class="text-zinc-500">Tipe SPK</span>
                            <span class="font-medium text-zinc-900 dark:text-white">Produksi Barang Jadi</span>
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

                    @if(!auth()->user()->isOwner())
                        <div
                            class="mt-4 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                            <p class="text-xs text-yellow-700 dark:text-yellow-400">SPK akan menunggu persetujuan Owner</p>
                        </div>
                    @endif

                    <button type="submit" @click="validateForm($event)"
                        class="w-full mt-5 py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        Simpan SPK
                    </button>
                    <a href="{{ route('spk.index') }}"
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
                    outputItems: [{ product_id: '', quantity: '', unit: '', search: '', showDropdown: false }],

                    filterProducts(search) {
                        if (!Array.isArray(this.outputOptions)) return [];
                        // Always return all if no search (or top 10)
                        if (!search) return this.outputOptions.slice(0, 20);
                        const s = search.toLowerCase();
                        return this.outputOptions.filter(o => o.name.toLowerCase().includes(s) || o.code.toLowerCase().includes(s)).slice(0, 20);
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