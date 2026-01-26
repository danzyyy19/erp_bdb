<x-app-layout>
    @section('title', 'Buat FPB')

    <div x-data="fpbForm()" class="space-y-6">
        <form method="POST" action="{{ route('fpb.store') }}">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Form -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- SPK Selection -->
                    <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-5">
                        <h3 class="font-semibold text-zinc-900 dark:text-white mb-4">Pilih SPK</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-zinc-600 dark:text-zinc-400 mb-1">SPK
                                    *</label>
                                <!-- Searchable SPK Dropdown -->
                                <div class="relative" x-data="{ open: false, search: '' }">
                                    <input type="hidden" name="spk_id" x-model="selectedSpkId" required>
                                    <div @click="open = !open"
                                        class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white cursor-pointer flex items-center justify-between">
                                        <span x-text="selectedSpkLabel || 'Pilih SPK...'"
                                            :class="selectedSpkLabel ? 'text-zinc-900 dark:text-white' : 'text-zinc-400'"></span>
                                        <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                    <div x-show="open" @click.away="open = false; search = ''"
                                        class="absolute z-50 mt-1 w-full bg-white dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 rounded-lg shadow-lg max-h-72 overflow-hidden">
                                        <!-- Search Input -->
                                        <div class="p-2 border-b border-zinc-200 dark:border-zinc-700">
                                            <input type="text" x-model="search" placeholder="Cari SPK..."
                                                class="w-full px-3 py-2 text-sm rounded border border-zinc-300 dark:border-zinc-600 bg-zinc-50 dark:bg-zinc-900 text-zinc-900 dark:text-white"
                                                @click.stop>
                                        </div>
                                        <div class="max-h-56 overflow-y-auto">
                                            <!-- All SPKs -->
                                            @if($spks->count() > 0)
                                                <div
                                                    class="px-3 py-2 text-xs font-semibold text-zinc-600 bg-zinc-100 dark:bg-zinc-700 sticky top-0">
                                                    🔧 Pilih SPK</div>
                                                @foreach($spks as $spk)
                                                    @php $spkProduct = $spk->items->where('item_type', 'output')->first()?->product?->name ?? 'N/A'; @endphp
                                                    <div x-show="search === '' || '{{ strtolower($spk->spk_number . ' ' . $spkProduct) }}'.includes(search.toLowerCase())"
                                                        @click="selectedSpkId = '{{ $spk->id }}'; selectedSpkLabel = '{{ $spk->spk_number }} - {{ $spkProduct }}'; open = false; search = ''"
                                                        class="px-3 py-2 hover:bg-zinc-50 dark:hover:bg-zinc-700 cursor-pointer border-b border-zinc-100 dark:border-zinc-700">
                                                        <div class="font-medium text-sm text-zinc-900 dark:text-white">
                                                            {{ $spk->spk_number }}</div>
                                                        <div class="text-xs text-zinc-500">{{ $spkProduct }}</div>
                                                    </div>
                                                @endforeach
                                            @endif

                                            @if($spks->isEmpty())
                                                <div class="px-3 py-4 text-center text-zinc-500 text-sm">Tidak ada SPK
                                                    tersedia</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-zinc-600 dark:text-zinc-400 mb-1">Tanggal
                                    Request *</label>
                                <input type="date" name="request_date" value="{{ date('Y-m-d') }}" required
                                    class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                            </div>
                        </div>
                    </div>

                    <!-- Material Items -->
                    <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-5">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-semibold text-zinc-900 dark:text-white">Material yang Diminta</h3>
                            <button type="button" @click="addItem()"
                                class="px-3 py-1 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah Item
                            </button>
                        </div>

                        <div class="space-y-3">
                            <template x-for="(item, index) in items" :key="index">
                                <div
                                    class="flex gap-3 items-start p-3 bg-zinc-50 dark:bg-zinc-800/50 rounded-lg border border-zinc-200 dark:border-zinc-700">
                                    <div class="flex-1">
                                        <label class="block text-xs text-zinc-500 mb-1">Material</label>
                                        <!-- Searchable Material Dropdown -->
                                        <div class="relative" x-data="{ open: false, search: '' }">
                                            <input type="hidden" :name="'items['+index+'][product_id]'"
                                                x-model="item.product_id" required>
                                            <div @click="open = !open"
                                                class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white cursor-pointer flex items-center justify-between">
                                                <span x-text="getMaterialLabel(item.product_id) || 'Pilih material...'"
                                                    :class="item.product_id ? 'text-zinc-900 dark:text-white' : 'text-zinc-400'"></span>
                                                <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </div>
                                            <div x-show="open" @click.away="open = false; search = ''"
                                                class="absolute z-50 mt-1 w-full bg-white dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 rounded-lg shadow-lg max-h-60 overflow-hidden">
                                                <!-- Search Input -->
                                                <div class="p-2 border-b border-zinc-200 dark:border-zinc-700">
                                                    <input type="text" x-model="search" placeholder="Cari material..."
                                                        class="w-full px-3 py-2 text-sm rounded border border-zinc-300 dark:border-zinc-600 bg-zinc-50 dark:bg-zinc-900 text-zinc-900 dark:text-white"
                                                        @click.stop>
                                                </div>
                                                <div class="max-h-48 overflow-y-auto">
                                                    <template
                                                        x-for="mat in materialsData.filter(m => search === '' || m.label.toLowerCase().includes(search.toLowerCase()))"
                                                        :key="mat.id">
                                                        <div @click="item.product_id = mat.id; open = false; search = ''"
                                                            class="px-3 py-2 hover:bg-blue-50 dark:hover:bg-zinc-700 cursor-pointer border-b border-zinc-100 dark:border-zinc-700">
                                                            <div class="font-medium text-sm text-zinc-900 dark:text-white"
                                                                x-text="mat.code + ' - ' + mat.name"></div>
                                                            <div class="text-xs text-zinc-500"
                                                                x-text="'Stok: ' + mat.stock + ' ' + mat.unit"></div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-28">
                                        <label class="block text-xs text-zinc-500 mb-1">Qty</label>
                                        <input type="number" :name="'items['+index+'][quantity]'"
                                            x-model="item.quantity" step="any" min="0.01" required
                                            class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                                    </div>
                                    <div class="w-20">
                                        <label class="block text-xs text-zinc-500 mb-1">Satuan</label>
                                        <input type="text" :value="getMaterialUnit(item.product_id)" readonly
                                            class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-700 text-zinc-600 dark:text-zinc-400 text-center">
                                    </div>
                                    <div class="pt-5">
                                        <button type="button" @click="removeItem(index)"
                                            class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </template>

                            <div x-show="items.length === 0" class="text-center py-8 text-zinc-500">
                                <p>Belum ada item. Klik "Tambah Item" untuk menambahkan material.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-5">
                        <h3 class="font-semibold text-zinc-900 dark:text-white mb-4">Catatan</h3>
                        <textarea name="notes" rows="3" placeholder="Catatan tambahan..."
                            class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white"></textarea>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- SPK Info -->
                    <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-5"
                        x-show="selectedSpkId">
                        <h3 class="font-semibold text-zinc-900 dark:text-white mb-4">Info SPK</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-zinc-500">SPK Dipilih</span>
                                <span class="font-medium text-zinc-900 dark:text-white"
                                    x-text="selectedSpkLabel"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-5">
                        <h3 class="font-semibold text-zinc-900 dark:text-white mb-4">Aksi</h3>
                        <div class="space-y-3">
                            <button type="submit" :disabled="items.length === 0 || !selectedSpkId"
                                class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-zinc-400 text-white rounded-lg text-sm font-medium">
                                Simpan FPB
                            </button>
                            <a href="{{ route('fpb.index') }}"
                                class="block w-full px-4 py-2 border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-300 rounded-lg text-sm font-medium text-center hover:bg-zinc-50 dark:hover:bg-zinc-800">
                                Batal
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            function fpbForm() {
                return {
                    selectedSpkId: '{{ isset($selectedSpk) ? $selectedSpk->id : '' }}',
                    selectedSpkLabel: '{{ isset($selectedSpk) ? $selectedSpk->spk_number . " - " . ($selectedSpk->items->where("item_type", "output")->first()?->product?->name ?? "N/A") : "" }}',
                    items: [
                        @if(isset($spkMaterials) && $spkMaterials->count() > 0)
                            @foreach($spkMaterials as $item)
                                { product_id: '{{ $item->product_id }}', quantity: {{ $item->quantity_planned }} },
                            @endforeach
                        @else
                            { product_id: '', quantity: '' }
                        @endif
                        ],
                    materialsData: [
                        @foreach($materials as $material)
                            { id: '{{ $material->id }}', code: '{{ $material->code }}', name: '{{ $material->name }}', stock: {{ $material->current_stock }}, unit: '{{ $material->unit }}', label: '{{ $material->code }} - {{ $material->name }}' },
                        @endforeach
                        ],
                    getMaterialLabel(productId) {
                        if (!productId) return '';
                        const m = this.materialsData.find(m => m.id == productId);
                        return m ? m.code + ' - ' + m.name : '';
                    },
                    getMaterialUnit(productId) {
                        if (!productId) return '-';
                        const m = this.materialsData.find(m => m.id == productId);
                        return m ? m.unit : '-';
                    },
                    addItem() {
                        this.items.push({ product_id: '', quantity: '' });
                    },
                    removeItem(index) {
                        if (this.items.length > 1) {
                            this.items.splice(index, 1);
                        }
                    },
                    loadSpkItems() {
                        // Could add AJAX to load SPK items dynamically
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>