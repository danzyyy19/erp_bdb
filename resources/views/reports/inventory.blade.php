<x-app-layout>
    @section('title', 'Laporan Inventory')

    <!-- Category Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-5">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-semibold text-zinc-900 dark:text-white">Bahan Baku</h3>
                <span
                    class="px-2 py-1 text-xs rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400">
                    {{ $categorySummary['bahan_baku']['count'] }} items
                </span>
            </div>
            <p class="text-2xl font-bold text-zinc-900 dark:text-white">
                {{ number_format($categorySummary['bahan_baku']['total_stock'], 0) }}</p>
            <p class="text-sm text-zinc-500">Total Stok</p>
            @if($categorySummary['bahan_baku']['low_stock'] > 0)
                <p class="text-sm text-red-500 mt-2"><i data-lucide="alert-triangle" class="w-4 h-4 inline"></i>
                    {{ $categorySummary['bahan_baku']['low_stock'] }} stok rendah</p>
            @endif
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-5">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-semibold text-zinc-900 dark:text-white">Packaging</h3>
                <span
                    class="px-2 py-1 text-xs rounded-full bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400">
                    {{ $categorySummary['packaging']['count'] }} items
                </span>
            </div>
            <p class="text-2xl font-bold text-zinc-900 dark:text-white">
                {{ number_format($categorySummary['packaging']['total_stock'], 0) }}</p>
            <p class="text-sm text-zinc-500">Total Stok</p>
            @if($categorySummary['packaging']['low_stock'] > 0)
                <p class="text-sm text-red-500 mt-2"><i data-lucide="alert-triangle" class="w-4 h-4 inline"></i>
                    {{ $categorySummary['packaging']['low_stock'] }} stok rendah</p>
            @endif
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-5">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-semibold text-zinc-900 dark:text-white">Barang Jadi</h3>
                <span
                    class="px-2 py-1 text-xs rounded-full bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">
                    {{ $categorySummary['barang_jadi']['count'] }} items
                </span>
            </div>
            <p class="text-2xl font-bold text-zinc-900 dark:text-white">
                {{ number_format($categorySummary['barang_jadi']['total_stock'], 0) }}</p>
            <p class="text-sm text-zinc-500">Total Stok</p>
            @if($categorySummary['barang_jadi']['low_stock'] > 0)
                <p class="text-sm text-red-500 mt-2"><i data-lucide="alert-triangle" class="w-4 h-4 inline"></i>
                    {{ $categorySummary['barang_jadi']['low_stock'] }} stok rendah</p>
            @endif
        </div>
    </div>

    <!-- Low Stock Alert -->
    @if($lowStockProducts->count() > 0)
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-5 mb-6">
            <h3 class="font-semibold text-red-700 dark:text-red-400 mb-3 flex items-center gap-2">
                <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                Produk Stok Rendah
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($lowStockProducts as $product)
                    <div class="bg-white dark:bg-zinc-900 rounded-lg p-3 border border-red-100 dark:border-red-900">
                        <p class="font-medium text-zinc-900 dark:text-white">{{ $product->name }}</p>
                        <p class="text-sm text-zinc-500">{{ $product->code }}</p>
                        <p class="text-sm text-red-600 dark:text-red-400 mt-1">
                            Stok: {{ number_format($product->current_stock, 0) }} / Min:
                            {{ number_format($product->min_stock, 0) }} {{ $product->unit }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- All Products -->
    <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        <div class="p-4 border-b border-zinc-200 dark:border-zinc-800">
            <h3 class="font-semibold text-zinc-900 dark:text-white">Semua Produk</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50 border-b border-zinc-200 dark:border-zinc-700">
                    <tr>
                        <th
                            class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase border-r border-zinc-200 dark:border-zinc-700">
                            Produk</th>
                        <th
                            class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase border-r border-zinc-200 dark:border-zinc-700">
                            Kategori</th>
                        <th
                            class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase border-r border-zinc-200 dark:border-zinc-700">
                            Stok</th>
                        <th
                            class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase border-r border-zinc-200 dark:border-zinc-700">
                            Min Stok</th>
                        <th
                            class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                            Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr
                            class="border-b border-zinc-100 dark:border-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                            <td class="px-4 py-3 border-r border-zinc-100 dark:border-zinc-800">
                                <p class="font-medium text-zinc-900 dark:text-white">{{ $product->name }}</p>
                                <p class="text-xs text-zinc-500">{{ $product->code }}</p>
                            </td>
                            <td
                                class="px-4 py-3 text-zinc-600 dark:text-zinc-400 border-r border-zinc-100 dark:border-zinc-800">
                                {{ $product->category->name }}</td>
                            <td
                                class="px-4 py-3 text-right font-medium text-zinc-900 dark:text-white border-r border-zinc-100 dark:border-zinc-800">
                                {{ number_format($product->current_stock, 0) }} {{ $product->unit }}
                            </td>
                            <td class="px-4 py-3 text-right text-zinc-500 border-r border-zinc-100 dark:border-zinc-800">
                                {{ number_format($product->min_stock, 0) }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($product->isLowStock())
                                    <span
                                        class="px-2 py-0.5 text-xs rounded bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400">Rendah</span>
                                @else
                                    <span
                                        class="px-2 py-0.5 text-xs rounded bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">Aman</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-zinc-500">Tidak ada produk</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
