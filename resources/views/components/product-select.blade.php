@props(['name', 'products', 'selected' => '', 'required' => false, 'placeholder' => 'Pilih produk...'])

<div x-data="{
    open: false,
    search: '',
    selectedId: '{{ $selected }}',
    selectedText: '',
    products: @js($products->map(fn($p) => [
        'id' => $p->id,
        'code' => $p->code,
        'name' => $p->name,
        'stock' => $p->current_stock,
        'unit' => $p->unit,
        'text' => $p->code . ' - ' . $p->name
    ])),
    get filtered() {
        if (!this.search) return this.products;
        const q = this.search.toLowerCase();
        return this.products.filter(p => 
            p.code.toLowerCase().includes(q) || 
            p.name.toLowerCase().includes(q)
        );
    },
    init() {
        if (this.selectedId) {
            const found = this.products.find(p => p.id == this.selectedId);
            if (found) this.selectedText = found.text;
        }
    },
    select(product) {
        this.selectedId = product.id;
        this.selectedText = product.text;
        this.search = '';
        this.open = false;
    },
    clear() {
        this.selectedId = '';
        this.selectedText = '';
        this.search = '';
    }
}" @click.away="open = false" class="relative">

    <!-- Trigger Button -->
    <button type="button" @click="open = !open"
        class="w-full flex items-center justify-between px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-left"
        :class="open ? 'ring-2 ring-blue-500 border-blue-500' : ''">
        <span :class="selectedText ? 'text-zinc-900 dark:text-white' : 'text-zinc-400 dark:text-zinc-500'"
            x-text="selectedText || '{{ $placeholder }}'"></span>
        <i data-lucide="chevron-down" class="w-4 h-4 text-zinc-400 transition-transform"
            :class="open && 'rotate-180'"></i>
    </button>

    <!-- Hidden Input -->
    <input type="hidden" name="{{ $name }}" x-model="selectedId" {{ $required ? 'required' : '' }}>

    <!-- Dropdown -->
    <div x-show="open" x-transition
        class="absolute z-50 w-full mt-1 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg shadow-xl overflow-hidden">

        <!-- Search Input -->
        <div class="p-2 border-b border-zinc-200 dark:border-zinc-700">
            <input type="text" x-model="search" @keydown.escape="open = false" placeholder="Cari material..."
                class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-600 bg-zinc-50 dark:bg-zinc-900 text-zinc-900 dark:text-white placeholder-zinc-400"
                x-ref="searchInput">
        </div>

        <!-- Options List -->
        <div class="max-h-60 overflow-y-auto">
            <template x-for="product in filtered" :key="product.id">
                <button type="button" @click="select(product)"
                    class="w-full px-3 py-2 text-left hover:bg-zinc-100 dark:hover:bg-zinc-700 border-b border-zinc-100 dark:border-zinc-700/50 last:border-0"
                    :class="selectedId == product.id && 'bg-blue-50 dark:bg-blue-900/20'">
                    <div class="font-medium text-sm text-zinc-900 dark:text-white"
                        x-text="product.code + ' - ' + product.name"></div>
                    <div class="text-xs text-zinc-500 dark:text-zinc-400">
                        Stok: <span x-text="product.stock"></span> <span x-text="product.unit"></span>
                    </div>
                </button>
            </template>

            <!-- No Results -->
            <div x-show="filtered.length === 0" class="px-3 py-4 text-center text-sm text-zinc-500 dark:text-zinc-400">
                Tidak ditemukan
            </div>
        </div>
    </div>
</div>