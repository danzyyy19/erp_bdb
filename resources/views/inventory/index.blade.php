<x-app-layout>
    @section('title', 'Semua Produk')

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">Semua Produk</h2>
                <p class="text-zinc-500 dark:text-zinc-400 mt-1">Daftar seluruh produk inventory</p>
            </div>
            <a href="{{ route('inventory.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Tambah Produk</span>
            </a>
        </div>
    </x-slot>

    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-4 mb-6">
        <form method="GET" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Cari</label>
                <div class="relative">
                    <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-zinc-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Nama atau kode produk..."
                        class="w-full pl-10 pr-4 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800">
                </div>
            </div>
            <div class="w-48">
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Kategori</label>
                <select name="category"
                    class="w-full px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800">
                    <option value="">Semua</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit"
                class="px-4 py-2 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 rounded-lg font-medium">
                Filter
            </button>
        </form>
    </div>

    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                    <tr>
                        <th class="px-4 py-3 text-center text-xs font-medium text-zinc-500 uppercase">Kode</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-zinc-500 uppercase">Nama</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-zinc-500 uppercase">Kategori</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-zinc-500 uppercase">Stok</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-zinc-500 uppercase">Min Stok</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-zinc-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-zinc-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                    @forelse($products as $product)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                            <td class="px-4 py-4 font-medium text-zinc-900 dark:text-white">{{ $product->code }}</td>
                            <td class="px-4 py-4 text-zinc-900 dark:text-white">{{ $product->name }}</td>
                            <td class="px-4 py-4 text-zinc-600 dark:text-zinc-400">{{ $product->category->name }}</td>
                            <td
                                class="px-4 py-4 text-right {{ $product->isLowStock() ? 'text-red-600 dark:text-red-400 font-medium' : 'text-zinc-900 dark:text-white' }}">
                                {{ number_format($product->current_stock, 0) }} {{ $product->unit }}</td>
                            <td class="px-4 py-4 text-right text-zinc-600 dark:text-zinc-400">
                                {{ number_format($product->min_stock, 0) }}</td>
                            <td class="px-4 py-4">
                                @if($product->isLowStock())
                                    <span
                                        class="px-2 py-1 text-xs rounded-full bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400">Rendah</span>
                                @else
                                    <span
                                        class="px-2 py-1 text-xs rounded-full bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">Aman</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-right">
                                <a href="{{ route('inventory.show', $product) }}"
                                    class="p-2 text-zinc-500 hover:text-zinc-900 dark:hover:text-white hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg inline-block">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-zinc-500">Tidak ada produk</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($products->hasPages())
            <div class="px-4 py-3 border-t border-zinc-200 dark:border-zinc-800">{{ $products->links() }}</div>
        @endif
    </div>
</x-app-layout>
