<x-app-layout>
    @section('title', 'Penjualan')

    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">Laporan Penjualan</h2>
            <p class="text-zinc-500 dark:text-zinc-400 mt-1">Overview penjualan</p>
        </div>
    </x-slot>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-5">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Total Penjualan</p>
            <p class="text-2xl font-bold text-zinc-900 dark:text-white mt-1">Rp
                {{ number_format($summary['total_sales'] ?? 0, 0, ',', '.') }}
            </p>
        </div>
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-5">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Sudah Dibayar</p>
            <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">Rp
                {{ number_format($summary['paid_amount'] ?? 0, 0, ',', '.') }}
            </p>
        </div>
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-5">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Belum Dibayar</p>
            <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400 mt-1">Rp
                {{ number_format($summary['pending_amount'] ?? 0, 0, ',', '.') }}
            </p>
        </div>
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-5">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Jumlah Invoice</p>
            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400 mt-1">{{ $summary['invoice_count'] ?? 0 }}</p>
        </div>
    </div>


    <!-- Mobile Card View -->
    <div class="grid grid-cols-1 gap-4 md:hidden mb-6">
        @forelse($invoices as $invoice)
            <div class="bg-white dark:bg-zinc-900 p-4 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-800">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <div class="font-bold text-blue-600 dark:text-blue-400 font-mono">{{ $invoice->invoice_number }}
                        </div>
                        <div class="text-xs text-zinc-500 dark:text-zinc-400">
                            {{ $invoice->invoice_date->format('d M Y') }}
                        </div>
                    </div>
                    <span
                        class="px-2 py-1 text-xs rounded-full bg-{{ $invoice->status_color }}-100 dark:bg-{{ $invoice->status_color }}-900/30 text-{{ $invoice->status_color }}-700 dark:text-{{ $invoice->status_color }}-400">
                        {{ $invoice->status_label }}
                    </span>
                </div>

                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-zinc-500 dark:text-zinc-400">Customer:</span>
                        <span
                            class="font-medium text-zinc-900 dark:text-white">{{ $invoice->customer?->name ?? 'Walk-in' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-zinc-500 dark:text-zinc-400">Total:</span>
                        <span class="font-bold text-zinc-900 dark:text-white">Rp
                            {{ number_format($invoice->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center p-8 text-zinc-500 dark:text-zinc-400">
                Tidak ada data penjualan.
            </div>
        @endforelse
    </div>

    <!-- Desktop Table View -->
    <div
        class="hidden md:block bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                    <tr>
                        <th class="px-4 py-3 text-center text-xs font-medium text-zinc-500 uppercase">No. Invoice</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-zinc-500 uppercase">Customer</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-zinc-500 uppercase">Tanggal</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-zinc-500 uppercase">Total</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-zinc-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                    @forelse($invoices as $invoice)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                            <td class="px-4 py-4 font-medium text-blue-600 dark:text-blue-400">
                                {{ $invoice->invoice_number }}
                            </td>
                            <td class="px-4 py-4 text-zinc-900 dark:text-white">{{ $invoice->customer?->name ?? 'Walk-in' }}
                            </td>
                            <td class="px-4 py-4 text-zinc-600 dark:text-zinc-400">
                                {{ $invoice->invoice_date->format('d M Y') }}
                            </td>
                            <td class="px-4 py-4 text-right font-medium text-zinc-900 dark:text-white">Rp
                                {{ number_format($invoice->total, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-4">
                                <span
                                    class="px-2 py-1 text-xs rounded-full bg-{{ $invoice->status_color }}-100 dark:bg-{{ $invoice->status_color }}-900/30 text-{{ $invoice->status_color }}-700 dark:text-{{ $invoice->status_color }}-400">
                                    {{ $invoice->status_label }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center text-zinc-500">Tidak ada data penjualan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($invoices->hasPages())
            <div class="px-4 py-3 border-t border-zinc-200 dark:border-zinc-800">{{ $invoices->links() }}</div>
        @endif
    </div>
</x-app-layout>