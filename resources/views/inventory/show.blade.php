<x-app-layout>
    @section('title', $product->name)

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $product->name }}</h2>
                <p class="text-zinc-500 dark:text-zinc-400 mt-1">{{ $product->code }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('inventory.edit', $product) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 rounded-lg transition-colors">
                    <i data-lucide="edit" class="w-4 h-4"></i>
                    Edit
                </a>
                @if(auth()->user()->isOwner() || auth()->user()->isOperasional())
                    <form action="{{ route('inventory.destroy', $product->uuid) }}" method="POST" class="inline"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-red-100 dark:bg-red-900/30 hover:bg-red-200 dark:hover:bg-red-900/50 text-red-700 dark:text-red-400 rounded-lg transition-colors">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                            Hapus
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Product Info -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6">
            <h3 class="font-semibold text-zinc-900 dark:text-white mb-4">Informasi Produk</h3>
            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-zinc-500 dark:text-zinc-400">Kategori</p>
                    <p class="font-medium text-zinc-900 dark:text-white">{{ $product->category->name }}</p>
                </div>
                @if($product->spec_type)
                    <div>
                        <p class="text-zinc-500 dark:text-zinc-400">Spesifikasi</p>
                        <p class="font-medium text-zinc-900 dark:text-white">
                            {{ $product->spec_type == 'high_spec' ? 'High Spec' : 'Medium Spec' }}
                        </p>
                    </div>
                @endif
                <div>
                    <p class="text-zinc-500 dark:text-zinc-400">Satuan</p>
                    <p class="font-medium text-zinc-900 dark:text-white">{{ $product->unit }}</p>
                </div>
                @if($product->supplier_type)
                    <div>
                        <p class="text-zinc-500 dark:text-zinc-400">Tipe Supplier</p>
                        <p class="font-medium text-zinc-900 dark:text-white">
                            {{ ucfirst(str_replace('_', ' ', $product->supplier_type)) }}
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Stock Info -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6">
            <h3 class="font-semibold text-zinc-900 dark:text-white mb-4">Informasi Stok</h3>
            <div class="text-center mb-4">
                <p
                    class="text-4xl font-bold {{ $product->isLowStock() ? 'text-red-600 dark:text-red-400' : 'text-zinc-900 dark:text-white' }}">
                    {{ number_format($product->current_stock, 0) }}
                </p>
                <p class="text-zinc-500">{{ $product->unit }}</p>
            </div>
            <div class="h-3 bg-zinc-100 dark:bg-zinc-800 rounded-full overflow-hidden mb-2">
                @php
                    $percentage = $product->min_stock > 0 ? min(100, ($product->current_stock / $product->min_stock) * 100) : 100;
                    $color = $percentage <= 50 ? 'bg-red-500' : ($percentage <= 100 ? 'bg-yellow-500' : 'bg-green-500');
                @endphp
                <div class="h-full {{ $color }}" style="width: {{ min(100, $percentage) }}%"></div>
            </div>
            <p class="text-sm text-zinc-500 text-center">Minimum: {{ number_format($product->min_stock, 0) }}
                {{ $product->unit }}
            </p>
            @if($product->isLowStock())
                <div
                    class="mt-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg text-center">
                    <p class="text-sm text-red-700 dark:text-red-400 font-medium">
                        <i data-lucide="alert-triangle" class="w-4 h-4 inline mr-1"></i>
                        Stok Rendah!
                    </p>
                </div>
            @endif
        </div>


    </div>

    <!-- Stock History -->
    <div class="mt-6 bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800">
        <div class="p-5 border-b border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
            <h3 class="font-semibold text-zinc-900 dark:text-white">Riwayat Stok Terbaru</h3>
            <a href="{{ route('inventory.stock-history', ['product_id' => $product->id]) }}"
                class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                Lihat Semua
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                    <tr>
                        <th class="px-4 py-3 text-center text-xs font-medium text-zinc-500 uppercase">Tanggal</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-zinc-500 uppercase">Tipe</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-zinc-500 uppercase">Qty</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-zinc-500 uppercase">Sebelum</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-zinc-500 uppercase">Sesudah</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-zinc-500 uppercase">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                    @forelse($product->stockMovements as $movement)
                        <tr>
                            <td class="px-4 py-3 text-zinc-900 dark:text-white">
                                {{ $movement->created_at->format('d M Y H:i') }}
                            </td>
                            <td class="px-4 py-3">
                                <span
                                    class="px-2 py-1 text-xs rounded-full bg-{{ $movement->isStockIn() ? 'green' : 'red' }}-100 dark:bg-{{ $movement->isStockIn() ? 'green' : 'red' }}-900/30 text-{{ $movement->isStockIn() ? 'green' : 'red' }}-700 dark:text-{{ $movement->isStockIn() ? 'green' : 'red' }}-400">
                                    {{ $movement->type_label }}
                                </span>
                            </td>
                            <td
                                class="px-4 py-3 text-right font-medium {{ $movement->isStockIn() ? 'text-green-600' : 'text-red-600' }}">
                                {{ $movement->isStockIn() ? '+' : '-' }}{{ number_format($movement->quantity, 2) }}
                            </td>
                            <td class="px-4 py-3 text-right text-zinc-600 dark:text-zinc-400">
                                {{ number_format($movement->stock_before, 2) }}
                            </td>
                            <td class="px-4 py-3 text-right font-medium text-zinc-900 dark:text-white">
                                {{ number_format($movement->stock_after, 2) }}
                            </td>
                            <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400">{{ $movement->notes ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-zinc-500">Belum ada riwayat stok</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>