<x-app-layout>
    @section('title', 'Laporan Penjualan')

    <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-4 mb-4"
        x-data="salesFilter()">
        <div class="flex flex-wrap items-end gap-3">
            <div class="w-28">
                <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Bulan</label>
                <select x-model="month" @change="submitFilter()"
                    class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                    <option value="">Semua</option>
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}">{{ DateTime::createFromFormat('!m', $i)->format('M') }}</option>
                    @endfor
                </select>
            </div>
            <div class="w-24">
                <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Tahun</label>
                <select x-model="year" @change="submitFilter()"
                    class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                    @for($y = now()->year; $y >= now()->year - 5; $y--)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
            </div>
        </div>
    </div>

    <!-- Summary - Without prices for owner -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-5">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Jumlah Invoice</p>
            <p class="text-2xl font-bold text-zinc-900 dark:text-white mt-1">{{ $summary['invoice_count'] ?? 0 }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-5">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Total Item Terjual</p>
            <p class="text-2xl font-bold text-zinc-900 dark:text-white mt-1">
                {{ $invoices->sum(fn($i) => $i->items->sum('quantity')) }}</p>
        </div>
    </div>

    <!-- Invoice Table - Without prices -->
    <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50 border-b border-zinc-200 dark:border-zinc-700">
                    <tr>
                        <th
                            class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase border-r border-zinc-200 dark:border-zinc-700">
                            Invoice</th>
                        <th
                            class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase border-r border-zinc-200 dark:border-zinc-700">
                            Customer</th>
                        <th
                            class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase border-r border-zinc-200 dark:border-zinc-700">
                            Tanggal</th>
                        <th
                            class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase border-r border-zinc-200 dark:border-zinc-700">
                            Jumlah Item</th>
                        <th
                            class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                            Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $invoice)
                        <tr
                            class="border-b border-zinc-100 dark:border-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                            <td
                                class="px-4 py-3 font-medium text-zinc-900 dark:text-white border-r border-zinc-100 dark:border-zinc-800">
                                {{ $invoice->invoice_number }}</td>
                            <td
                                class="px-4 py-3 text-zinc-600 dark:text-zinc-400 border-r border-zinc-100 dark:border-zinc-800">
                                {{ $invoice->customer?->name ?? '-' }}</td>
                            <td
                                class="px-4 py-3 text-zinc-600 dark:text-zinc-400 border-r border-zinc-100 dark:border-zinc-800">
                                {{ $invoice->invoice_date->format('d M Y') }}</td>
                            <td
                                class="px-4 py-3 text-right font-medium text-zinc-900 dark:text-white border-r border-zinc-100 dark:border-zinc-800">
                                {{ $invoice->items->sum('quantity') }}</td>
                            <td class="px-4 py-3 text-center">
                                <span
                                    class="px-2 py-0.5 text-xs rounded {{ $invoice->status == 'paid' ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' : 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400' }}">
                                    {{ $invoice->status == 'paid' ? 'Lunas' : 'Belum Lunas' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-zinc-500">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        <script>
            function salesFilter() {
                return {
                    month: '{{ $month ?? '' }}',
                    year: '{{ $year ?? now()->year }}',
                    submitFilter() {
                        const params = new URLSearchParams();
                        if (this.month) params.set('month', this.month);
                        if (this.year) params.set('year', this.year);
                        window.location.href = '{{ route('reports.sales') }}' + (params.toString() ? '?' + params.toString() : '');
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>
