<x-app-layout>
    @section('title', 'Tambah Produk')

    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">Tambah Produk</h2>
            <p class="text-zinc-500 dark:text-zinc-400 mt-1">Tambah produk baru ke inventory</p>
        </div>
    </x-slot>

    <div class="max-w-2xl">
        <form method="POST" action="{{ route('inventory.store') }}"
            class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Kode Produk
                        *</label>
                    <input type="text" name="code" value="{{ old('code') }}" required
                        class="w-full px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800">
                    @error('code')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Nama Produk
                        *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800">
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Kategori *</label>
                    <select name="category_id" required
                        class="w-full px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Spec Type (untuk
                        Bahan Baku)</label>
                    <select name="spec_type"
                        class="w-full px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800">
                        <option value="">- Pilih -</option>
                        <option value="high_spec" {{ old('spec_type') == 'high_spec' ? 'selected' : '' }}>High Spec
                        </option>
                        <option value="medium_spec" {{ old('spec_type') == 'medium_spec' ? 'selected' : '' }}>Medium Spec
                        </option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Satuan *</label>
                    <input type="text" name="unit" value="{{ old('unit', 'Kg') }}" required
                        class="w-full px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800">
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Unit Packing <span
                            class="text-xs text-zinc-400">(untuk print)</span></label>
                    <input type="text" name="unit_packing" value="{{ old('unit_packing') }}"
                        placeholder="Contoh: DRIGEN@20Kg"
                        class="w-full px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800">
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Tipe
                        Supplier</label>
                    <select name="supplier_type"
                        class="w-full px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800">
                        <option value="">- Pilih -</option>
                        <option value="supplier_resmi" {{ old('supplier_type') == 'supplier_resmi' ? 'selected' : '' }}>
                            Supplier Resmi</option>
                        <option value="agen" {{ old('supplier_type') == 'agen' ? 'selected' : '' }}>Agen</option>
                        <option value="internal" {{ old('supplier_type') == 'internal' ? 'selected' : '' }}>Internal
                        </option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Stok Awal *</label>
                    <input type="number" name="current_stock" value="{{ old('current_stock', 0) }}" step="0.01" min="0"
                        required
                        class="w-full px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800">
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Minimum Stok
                        *</label>
                    <input type="number" name="min_stock" value="{{ old('min_stock', 0) }}" step="0.01" min="0" required
                        class="w-full px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800">
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Harga Beli</label>
                    <input type="number" name="purchase_price" value="{{ old('purchase_price', 0) }}" step="1" min="0"
                        class="w-full px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800">
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Harga Jual</label>
                    <input type="number" name="selling_price" value="{{ old('selling_price', 0) }}" step="1" min="0"
                        class="w-full px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Deskripsi</label>
                    <textarea name="description" rows="2"
                        class="w-full px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="flex items-center gap-3 mt-6">
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                    Simpan
                </button>
                <a href="{{ route('inventory.index') }}"
                    class="px-4 py-2 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-app-layout>