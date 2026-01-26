<x-app-layout>
    @section('title', 'Detail Purchase Order')

    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">{{ $purchase->purchase_number }}</h2>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $purchase->purchase_date->format('d F Y') }}</p>
            </div>
            @php
                $statusColors = [
                    'draft' => 'bg-zinc-100 dark:bg-zinc-700 text-zinc-600 dark:text-zinc-400',
                    'pending' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400',
                    'approved' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400',
                    'received' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400',
                    'cancelled' => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400',
                ];
            @endphp
            <span
                class="px-3 py-1 text-sm rounded {{ $statusColors[$purchase->status] ?? '' }}">{{ $purchase->status_label }}</span>
        </div>

        @if(session('success'))
            <div class="mb-4 p-3 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-3 rounded-lg bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- Info -->
        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-5 mb-4">
            <div class="grid grid-cols-3 gap-4 text-sm">
                <div>
                    <p class="text-zinc-500 dark:text-zinc-400">Supplier</p>
                    <p class="font-medium text-zinc-900 dark:text-white">{{ $purchase->supplier->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-zinc-500 dark:text-zinc-400">Dibuat Oleh</p>
                    <p class="font-medium text-zinc-900 dark:text-white">{{ $purchase->creator->name ?? '-' }}</p>
                </div>
                @if($purchase->approver)
                    <div>
                        <p class="text-zinc-500 dark:text-zinc-400">Disetujui Oleh</p>
                        <p class="font-medium text-zinc-900 dark:text-white">{{ $purchase->approver->name }}</p>
                    </div>
                @endif
                @if($purchase->notes)
                    <div class="col-span-3">
                        <p class="text-zinc-500 dark:text-zinc-400">Catatan</p>
                        <p class="text-zinc-900 dark:text-white">{{ $purchase->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Items -->
        <div
            class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 overflow-hidden mb-4">
            <div class="p-4 border-b border-zinc-200 dark:border-zinc-700">
                <h3 class="font-semibold text-sm text-zinc-900 dark:text-white">Item Produk</h3>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-400">Produk
                        </th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-zinc-600 dark:text-zinc-400">Qty</th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-zinc-600 dark:text-zinc-400">Harga
                        </th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-zinc-600 dark:text-zinc-400">Total
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @foreach($purchase->items as $item)
                        <tr>
                            <td class="px-4 py-3 text-zinc-900 dark:text-white">{{ $item->product->code ?? '-' }} -
                                {{ $item->product->name ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-right text-zinc-900 dark:text-white">
                                {{ number_format($item->quantity, 2) }}
                            </td>
                            <td class="px-4 py-3 text-right text-zinc-900 dark:text-white">Rp
                                {{ number_format($item->unit_price, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-right text-zinc-900 dark:text-white">Rp
                                {{ number_format($item->total, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-zinc-50 dark:bg-zinc-800/50 border-t border-zinc-200 dark:border-zinc-700">
                    <tr>
                        <td colspan="3" class="px-4 py-2 text-right text-sm text-zinc-600 dark:text-zinc-400">Subtotal
                        </td>
                        <td class="px-4 py-2 text-right font-medium text-zinc-900 dark:text-white">Rp
                            {{ number_format($purchase->subtotal, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="px-4 py-2 text-right text-sm text-zinc-600 dark:text-zinc-400">PPN
                            ({{ $purchase->tax_percentage }}%)</td>
                        <td class="px-4 py-2 text-right font-medium text-zinc-900 dark:text-white">Rp
                            {{ number_format($purchase->tax, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3"
                            class="px-4 py-2 text-right text-sm font-semibold text-zinc-900 dark:text-white">Total</td>
                        <td class="px-4 py-2 text-right font-bold text-zinc-900 dark:text-white">Rp
                            {{ number_format($purchase->total_amount, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between">
            <a href="{{ route('purchases.index') }}"
                class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-900">← Kembali</a>

            <div class="flex gap-2">
                @if($purchase->status === 'draft')
                    <form action="{{ route('purchases.destroy', $purchase) }}" method="POST"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus PO ini?');" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-4 py-2 text-sm bg-red-600 hover:bg-red-700 text-white rounded-lg">Hapus</button>
                    </form>
                    <a href="{{ route('purchases.edit', $purchase) }}"
                        class="px-4 py-2 text-sm bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg">Edit</a>
                    <form action="{{ route('purchases.submit', $purchase) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Submit untuk
                            Approval</button>
                    </form>
                @endif

                @if($purchase->status === 'pending' && auth()->user()->isOwner())
                    <form action="{{ route('purchases.approve', $purchase) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2 text-sm bg-green-600 hover:bg-green-700 text-white rounded-lg">Approve</button>
                    </form>
                    <form action="{{ route('purchases.reject', $purchase) }}" method="POST"
                        onsubmit="return confirm('Batalkan PO ini?')">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2 text-sm bg-red-600 hover:bg-red-700 text-white rounded-lg">Cancel</button>
                    </form>
                @endif

                @if($purchase->status === 'approved' && auth()->user()->isFinance())
                    <form action="{{ route('purchases.receive', $purchase) }}" method="POST"
                        onsubmit="return confirm('Konfirmasi penerimaan barang? Stok akan ditambahkan.')">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2 text-sm bg-green-600 hover:bg-green-700 text-white rounded-lg">Terima
                            Barang</button>
                    </form>
                @endif

                <a href="{{ route('purchases.print', $purchase) }}" target="_blank"
                    class="px-4 py-2 text-sm bg-zinc-600 hover:bg-zinc-700 text-white rounded-lg">
                    <i data-lucide="printer" class="w-4 h-4 inline mr-1"></i>Print
                </a>
            </div>
        </div>
    </div>
</x-app-layout>