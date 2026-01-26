<x-app-layout>
    @section('title', 'Laporan Produksi')

    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">Laporan Produksi</h2>
            <p class="text-zinc-500 dark:text-zinc-400 mt-1">Laporan SPK dan produksi</p>
        </div>
    </x-slot>

    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-4 mb-6">
        <form method="GET" class="flex flex-wrap items-end gap-4">
            <div class="w-32">
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Bulan</label>
                <select name="month"
                    class="w-full px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800">
                    <option value="">Semua</option>
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
                    @endfor
                </select>
            </div>
            <div class="w-28">
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Tahun</label>
                <select name="year"
                    class="w-full px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800">
                    @for($y = now()->year; $y >= now()->year - 5; $y--)
                        <option value="{{ $y }}" {{ (request('year') ?? now()->year) == $y ? 'selected' : '' }}>{{ $y }}
                        </option>
                    @endfor
                </select>
            </div>
            <button type="submit"
                class="px-4 py-2 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 rounded-lg font-medium transition-colors">
                Filter
            </button>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-5">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Total SPK</p>
            <p class="text-2xl font-bold text-zinc-900 dark:text-white mt-1">{{ $summary['total'] ?? 0 }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-5">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Dalam Proses</p>
            <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 mt-1">{{ $summary['in_progress'] ?? 0 }}
            </p>
        </div>
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-5">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Selesai</p>
            <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">{{ $summary['completed'] ?? 0 }}</p>
        </div>
    </div>

    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                    <tr>
                        <th class="px-4 py-3 text-center text-xs font-medium text-zinc-500 uppercase">No. SPK</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-zinc-500 uppercase">Tgl Produksi</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-zinc-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-zinc-500 uppercase">Dibuat Oleh</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                    @forelse($spks as $spk)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                            <td class="px-4 py-4 font-medium text-zinc-900 dark:text-white">{{ $spk->spk_number }}</td>
                            <td class="px-4 py-4 text-zinc-600 dark:text-zinc-400">
                                {{ $spk->production_date?->format('d M Y') }}</td>
                            <td class="px-4 py-4">
                                <span
                                    class="px-2 py-1 text-xs rounded-full bg-{{ $spk->status_color }}-100 dark:bg-{{ $spk->status_color }}-900/30 text-{{ $spk->status_color }}-700 dark:text-{{ $spk->status_color }}-400">
                                    {{ $spk->status_label }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-zinc-600 dark:text-zinc-400">{{ $spk->creator->name }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-12 text-center text-zinc-500">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
