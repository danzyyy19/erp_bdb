<x-app-layout>
    @section('title', 'Master Data Inventory')

    <!-- Filters -->
    <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-4 mb-4">
        <form method="GET" action="{{ route('inventory.index') }}" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama atau kode..."
                    class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
            </div>
            <div class="w-40">
                <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Kategori</label>
                <select name="category"
                    class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-32">
                <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Status Stok</label>
                <select name="stock_status"
                    class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                    <option value="">Semua</option>
                    <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Stok Menipis</option>
                    <option value="normal" {{ request('stock_status') == 'normal' ? 'selected' : '' }}>Stok Aman</option>
                </select>
            </div>
            <button type="submit"
                class="px-4 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Filter</button>
            @if(request()->hasAny(['search', 'category', 'stock_status']))
                <a href="{{ route('inventory.index') }}"
                    class="px-3 py-2 text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white">Reset</a>
            @endif
        </form>
    </div>

    <!-- Action Button -->
    <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
        <div class="flex gap-2">
            @if(auth()->user()->isOwner() || auth()->user()->isOperasional())
                <a href="{{ route('inventory.bahan-baku.create') }}"
                    class="px-4 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Tambah Produk</a>
            @endif
        </div>
        <x-export-buttons excel-route="export.products.excel" pdf-route="export.products.pdf" />
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-4">
            <p class="text-xs text-zinc-500 dark:text-zinc-400">Total Produk</p>
            <p class="text-xl font-bold text-zinc-900 dark:text-white">{{ $products->total() }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-4">
            <p class="text-xs text-zinc-500 dark:text-zinc-400">Stok Rendah</p>
            <p class="text-xl font-bold text-red-600 dark:text-red-400">{{ $lowStockCount ?? 0 }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-4">
            <p class="text-xs text-zinc-500 dark:text-zinc-400">Kategori</p>
            <p class="text-xl font-bold text-zinc-900 dark:text-white">{{ $categories->count() }}</p>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50 border-b border-zinc-200 dark:border-zinc-700">
                    <tr>
                        <th
                            class="px-4 py-3 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                            Kode</th>
                        <th
                            class="px-4 py-3 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                            Nama</th>
                        <th
                            class="px-4 py-3 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                            Kategori</th>
                        <th
                            class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                            Stok</th>
                        <th
                            class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                            Min</th>
                        @if(auth()->user()->isOwner() || auth()->user()->isFinance())
                            <th
                                class="px-4 py-3 text-right text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                                Harga Beli</th>
                            <th
                                class="px-4 py-3 text-right text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                                Harga Jual</th>
                        @endif
                        <th
                            class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                            Status</th>
                        <th
                            class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse($products as $product)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                            <td class="px-4 py-3 text-zinc-900 dark:text-white font-medium">{{ $product->code }}</td>
                            <td class="px-4 py-3 text-zinc-900 dark:text-white">{{ $product->name }}</td>
                            <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400">{{ $product->category->name ?? '-' }}
                            </td>
                            <td
                                class="px-4 py-3 text-center {{ $product->isLowStock() ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-zinc-900 dark:text-white' }}">
                                {{ number_format($product->current_stock, 0) }} {{ $product->unit }}
                            </td>
                            <td class="px-4 py-3 text-center text-zinc-500 dark:text-zinc-400">
                                {{ number_format($product->min_stock, 0) }}</td>
                            @if(auth()->user()->isOwner() || auth()->user()->isFinance())
                                <td class="px-4 py-3 text-right text-zinc-900 dark:text-white">Rp
                                    {{ number_format($product->purchase_price ?? 0, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right text-zinc-900 dark:text-white">Rp
                                    {{ number_format($product->selling_price ?? 0, 0, ',', '.') }}</td>
                            @endif
                            <td class="px-4 py-3 text-center">
                                @if($product->isLowStock())
                                    <span
                                        class="px-2 py-0.5 text-xs rounded bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400">Rendah</span>
                                @else
                                    <span
                                        class="px-2 py-0.5 text-xs rounded bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">Aman</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('inventory.show', $product) }}"
                                        class="p-1.5 text-zinc-500 hover:text-blue-600 dark:text-zinc-400 dark:hover:text-blue-400"
                                        title="Detail">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    @if(auth()->user()->isOwner() || auth()->user()->isOperasional())
                                        <a href="{{ route('inventory.edit', $product) }}"
                                            class="p-1.5 text-zinc-500 hover:text-yellow-600 dark:text-zinc-400 dark:hover:text-yellow-400"
                                            title="Edit">
                                            <i data-lucide="edit" class="w-4 h-4"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-8 text-center text-zinc-500 dark:text-zinc-400">
                                Tidak ada produk ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($products->hasPages())
            <div class="px-4 py-3 border-t border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</x-app-layout>