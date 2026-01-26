<x-app-layout>
    @section('title', 'Daftar Purchase Order')

    <style>
        @media (min-width: 768px) {
            #po-mobile-view {
                display: none !important;
            }

            #po-desktop-view {
                display: block !important;
            }
        }

        @media (max-width: 767.98px) {
            #po-mobile-view {
                display: grid !important;
            }

            #po-desktop-view {
                display: none !important;
            }
        }
    </style>

    <!-- Filters -->
    <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-4 mb-4">
        <form method="GET" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="No PO atau supplier..."
                    class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
            </div>
            <div class="w-40">
                <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Supplier</label>
                <select name="supplier_id"
                    class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                    <option value="">Semua</option>
                    @foreach($suppliers as $s)
                        <option value="{{ $s->id }}" {{ request('supplier_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="w-32">
                <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Status</label>
                <select name="status"
                    class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                    <option value="">Semua</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="received" {{ request('status') == 'received' ? 'selected' : '' }}>Received</option>
                </select>
            </div>
            <button type="submit"
                class="px-4 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Filter</button>
        </form>
    </div>

    <!-- Action Button -->
    <div class="mb-4 flex justify-between">
        <a href="{{ route('purchases.create') }}"
            class="px-4 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Buat Purchase Order</a>
    </div>

    <!-- Mobile Card View (Protected) -->
    <div id="po-mobile-view" class="grid grid-cols-1 gap-4 md:hidden">
        @forelse($purchases as $po)
            <div class="bg-white dark:bg-zinc-900 p-4 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-800">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <div class="font-bold text-zinc-900 dark:text-white">{{ $po->purchase_number }}</div>
                        <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ $po->purchase_date->format('d/m/Y') }}
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs rounded {{ $statusColors[$po->status] ?? '' }}">
                        {{ $po->status_label }}
                    </span>
                </div>

                <div class="space-y-2 text-sm mb-4">
                    <div class="flex justify-between">
                        <span class="text-zinc-500 dark:text-zinc-400">Supplier:</span>
                        <span class="font-medium text-zinc-900 dark:text-white">{{ $po->supplier->name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-zinc-500 dark:text-zinc-400">Total:</span>
                        <span class="font-bold text-zinc-900 dark:text-white">Rp
                            {{ number_format($po->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-zinc-100 dark:border-zinc-800">
                    <a href="{{ route('purchases.show', $po) }}"
                        class="px-3 py-1.5 text-xs bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 rounded-md">
                        Detail
                    </a>
                    @if(in_array($po->status, ['draft', 'pending']))
                        <a href="{{ route('purchases.edit', $po) }}"
                            class="px-3 py-1.5 text-xs bg-yellow-50 dark:bg-yellow-900/30 hover:bg-yellow-100 dark:hover:bg-yellow-900/50 text-yellow-700 dark:text-yellow-400 rounded-md">
                            Edit
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center p-8 text-zinc-500 dark:text-zinc-400">
                Tidak ada purchase order.
            </div>
        @endforelse
    </div>

    <!-- Desktop Table View (Protected) -->
    <div id="po-desktop-view"
        class="hidden md:block bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50 border-b border-zinc-200 dark:border-zinc-700">
                    <tr>
                        <th
                            class="px-4 py-3 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                            No. PO</th>
                        <th
                            class="px-4 py-3 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                            Tanggal</th>
                        <th
                            class="px-4 py-3 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                            Supplier</th>
                        <th
                            class="px-4 py-3 text-right text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                            Total</th>
                        <th
                            class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                            Status</th>
                        <th
                            class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse($purchases as $po)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                            <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">{{ $po->purchase_number }}</td>
                            <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400">{{ $po->purchase_date->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-3 text-zinc-900 dark:text-white">{{ $po->supplier->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-right text-zinc-900 dark:text-white">Rp
                                {{ number_format($po->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span
                                    class="px-2 py-0.5 text-xs rounded {{ $statusColors[$po->status] ?? '' }}">{{ $po->status_label }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('purchases.show', $po) }}"
                                        class="p-1.5 text-zinc-500 hover:text-blue-600" title="Detail">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    @if(in_array($po->status, ['draft', 'pending']))
                                        <a href="{{ route('purchases.edit', $po) }}"
                                            class="p-1.5 text-zinc-500 hover:text-yellow-600" title="Edit">
                                            <i data-lucide="edit" class="w-4 h-4"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-zinc-500 dark:text-zinc-400">Tidak ada
                                purchase order.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($purchases->hasPages())
        <div class="px-4 py-3 border-t border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50">
            {{ $purchases->links() }}
        </div>
    @endif
</x-app-layout>