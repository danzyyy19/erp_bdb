<x-app-layout>
    @section('title', 'Pending Approval')

    <div class="mb-4">
        <h2 class="text-xl font-bold text-zinc-900 dark:text-white">Item Menunggu Persetujuan</h2>
        <p class="text-sm text-zinc-500 dark:text-zinc-400">Item yang diajukan oleh Operasional</p>
    </div>

    @if($products->isEmpty())
        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-8 text-center">
            <p class="text-zinc-500 dark:text-zinc-400">Tidak ada item yang menunggu persetujuan.</p>
        </div>
    @else
        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-zinc-50 dark:bg-zinc-800/50 border-b border-zinc-200 dark:border-zinc-700">
                        <tr>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                                Kode</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                                Nama</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                                Kategori</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                                Stok Awal</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                                Diajukan Oleh</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                                Tanggal</th>
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
                                <td class="px-4 py-3 text-zinc-900 dark:text-white">{{ $product->name }}</td>
                                <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">{{ $product->category->name ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-right text-zinc-900 dark:text-white">
                                    {{ number_format($product->current_stock, 0) }} {{ $product->unit }}</td>
                                <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">{{ $product->creator->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400">
                                    {{ $product->created_at->format('d M Y H:i') }}</td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <form method="POST" action="{{ route('inventory.approve', $product) }}" class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="px-3 py-1 text-xs bg-green-600 hover:bg-green-700 text-white rounded">Setujui</button>
                                        </form>
                                        <form method="POST" action="{{ route('inventory.reject', $product) }}" class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="px-3 py-1 text-xs bg-red-600 hover:bg-red-700 text-white rounded">Tolak</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($products->hasPages())
                <div class="px-4 py-3 border-t border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    @endif
</x-app-layout>
