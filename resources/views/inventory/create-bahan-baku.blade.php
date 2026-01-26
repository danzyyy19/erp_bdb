<x-app-layout>
    @section('title', 'Tambah Bahan Baku')

    <x-slot name="header">
        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">Tambah Bahan Baku</h2>
        <p class="text-zinc-500 dark:text-zinc-400 mt-1">Form penambahan bahan baku baru</p>
    </x-slot>

    <div class="max-w-2xl">
        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-6">
            <form method="POST" action="{{ route('inventory.bahan-baku.store') }}" class="space-y-5">
                @csrf

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Kode Produk
                            *</label>
                        <input type="text" name="code" value="{{ old('code') }}" required
                            class="w-full px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                        @error('code')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Nama *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Tipe
                        Spesifikasi</label>
                    <select name="spec_type"
                        class="w-full px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                        <option value="">-- Pilih --</option>
                        <option value="high_spec" {{ old('spec_type') == 'high_spec' ? 'selected' : '' }}>High Spec
                        </option>
                        <option value="medium_spec" {{ old('spec_type') == 'medium_spec' ? 'selected' : '' }}>Medium Spec
                        </option>
                    </select>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Stok Awal
                            *</label>
                        <input type="number" name="current_stock" value="{{ old('current_stock', 0) }}" min="0"
                            step="0.01" required
                            class="w-full px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Stok Minimum
                            *</label>
                        <input type="number" name="min_stock" value="{{ old('min_stock', 0) }}" min="0" step="0.01"
                            required
                            class="w-full px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Satuan *</label>
                        <select name="unit" required
                            class="w-full px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                            <option value="kg" {{ old('unit', 'kg') == 'kg' ? 'selected' : '' }}>kg</option>
                            <option value="liter" {{ old('unit') == 'liter' ? 'selected' : '' }}>liter</option>
                            <option value="pcs" {{ old('unit') == 'pcs' ? 'selected' : '' }}>pcs</option>
                            <option value="pack" {{ old('unit') == 'pack' ? 'selected' : '' }}>pack</option>
                            <option value="box" {{ old('unit') == 'box' ? 'selected' : '' }}>box</option>
                            <option value="roll" {{ old('unit') == 'roll' ? 'selected' : '' }}>roll</option>
                            <option value="meter" {{ old('unit') == 'meter' ? 'selected' : '' }}>meter</option>
                            <option value="lembar" {{ old('unit') == 'lembar' ? 'selected' : '' }}>lembar</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Deskripsi</label>
                    <textarea name="description" rows="3"
                        class="w-full px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">{{ old('description') }}</textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4">
                    <a href="{{ route('inventory.bahan-baku') }}"
                        class="px-4 py-2 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg">Batal</a>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>