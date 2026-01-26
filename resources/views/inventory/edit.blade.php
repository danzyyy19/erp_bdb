<x-app-layout>
    @section('title', 'Edit ' . $product->name)

    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">Edit {{ $product->name }}</h2>
            <p class="text-zinc-500 dark:text-zinc-400 mt-1">{{ $product->code }}</p>
        </div>
    </x-slot>

    <div class="max-w-2xl">
        <form method="POST" action="{{ route('inventory.update', $product) }}"
            class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Kode Produk
                        *</label>
                    <input type="text" name="code" value="{{ old('code', $product->code) }}" required
                        class="w-full px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800">
                    @error('code')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Nama Produk
                        *</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                        class="w-full px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800">
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Kategori *</label>
                    <select name="category_id" required
                        class="w-full px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Spec Type</label>
                    <select name="spec_type"
                        class="w-full px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800">
                        <option value="">- Pilih -</option>
                        <option value="high_spec" {{ old('spec_type', $product->spec_type) == 'high_spec' ? 'selected' : '' }}>High Spec</option>
                        <option value="medium_spec" {{ old('spec_type', $product->spec_type) == 'medium_spec' ? 'selected' : '' }}>Medium Spec</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Satuan *</label>
                    <input type="text" name="unit" value="{{ old('unit', $product->unit) }}" required
                        class="w-full px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800">
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Unit Packing <span
                            class="text-xs text-zinc-400">(untuk print)</span></label>
                    <input type="text" name="unit_packing" value="{{ old('unit_packing', $product->unit_packing) }}"
                        placeholder="Contoh: DRIGEN@20Kg"
                        class="w-full px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800">
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Tipe
                        Supplier</label>
                    <select name="supplier_type"
                        class="w-full px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800">
                        <option value="">- Pilih -</option>
                        <option value="supplier_resmi" {{ old('supplier_type', $product->supplier_type) == 'supplier_resmi' ? 'selected' : '' }}>Supplier Resmi</option>
                        <option value="agen" {{ old('supplier_type', $product->supplier_type) == 'agen' ? 'selected' : '' }}>Agen</option>
                        <option value="internal" {{ old('supplier_type', $product->supplier_type) == 'internal' ? 'selected' : '' }}>Internal</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Stok Saat
                        Ini</label>
                    <input type="text" value="{{ number_format($product->current_stock, 2) }} {{ $product->unit }}"
                        disabled
                        class="w-full px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800 text-zinc-500">
                    <p class="text-xs text-zinc-400 mt-1">Gunakan menu Adjust Stock untuk mengubah stok</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Minimum Stok
                        *</label>
                    <input type="number" name="min_stock" value="{{ old('min_stock', $product->min_stock) }}"
                        step="0.01" min="0" required
                        class="w-full px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800">
                </div>



                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Deskripsi</label>
                    <textarea name="description" rows="2"
                        class="w-full px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800">{{ old('description', $product->description) }}</textarea>
                </div>
            </div>

            <div class="flex items-center gap-3 mt-6">
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                    Update
                </button>
                <a href="{{ route('inventory.show', $product) }}"
                    class="px-4 py-2 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-app-layout>