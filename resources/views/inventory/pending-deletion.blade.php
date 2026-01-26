<x-app-layout>
    @section('title', 'Pending Deletion - Inventory')

    <div class="mb-4">
        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">Permintaan Hapus Produk</h2>
        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Daftar produk yang diminta dihapus oleh Operasional</p>
    </div>

    @if($products->count() > 0)
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
                                Nama Produk</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                                Kategori</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                                Stok</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                                Diminta Oleh</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            <tr
                                class="border-b border-zinc-100 dark:border-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                                <td class="px-4 py-3 font-mono text-zinc-900 dark:text-white">{{ $product->code }}</td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('inventory.show', $product->uuid) }}"
                                        class="font-medium text-blue-600 dark:text-blue-400 hover:underline">
                                        {{ $product->name }}
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">{{ $product->category->name }}</td>
                                <td class="px-4 py-3 text-center text-zinc-600 dark:text-zinc-400">
                                    {{ number_format($product->current_stock, 0) }} {{ $product->unit }}
                                </td>
                                <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">
                                    {{ $product->creator->name ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <form action="{{ route('inventory.approve-deletion', $product->uuid) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="px-3 py-1.5 text-xs bg-green-600 hover:bg-green-700 text-white rounded">
                                                Setujui Hapus
                                            </button>
                                        </form>
                                        <form action="{{ route('inventory.reject-deletion', $product->uuid) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="px-3 py-1.5 text-xs bg-red-600 hover:bg-red-700 text-white rounded">
                                                Tolak
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if($products->hasPages())
            <div class="mt-4">
                {{ $products->links() }}
            </div>
        @endif
    @else
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-green-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="text-xl font-semibold text-zinc-900 dark:text-white mb-2">Tidak Ada Permintaan Hapus</h3>
            <p class="text-zinc-500 dark:text-zinc-400">Semua permintaan hapus sudah diproses.</p>
        </div>
    @endif
</x-app-layout>