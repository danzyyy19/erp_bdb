<x-app-layout>
    @section('title', 'Laporan Produksi')

    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">Laporan Produksi</h2>
            <p class="text-zinc-500 dark:text-zinc-400 mt-1">Analisis produksi</p>
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
                        <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
                    @endfor
                </select>
            </div>
            <div class="w-28">
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Tahun</label>
                <select name="year"
                    class="w-full px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800">
                    @for($y = now()->year; $y >= now()->year - 5; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <button type="submit"
                class="px-4 py-2 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 rounded-lg font-medium">Filter</button>
        </form>
    </div>

    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-5 mb-6">
        <h3 class="font-semibold text-zinc-900 dark:text-white mb-4">SPK Selesai per Bulan ({{ $year }})</h3>
        <div class="h-64 flex items-end gap-2">
            @foreach($productionChart as $m => $count)
                @php
                    $max = max($productionChart) ?: 1;
                    $height = ($count / $max) * 100;
                @endphp
                <div class="flex-1 flex flex-col items-center gap-1">
                    <span class="text-xs text-zinc-500">{{ $count }}</span>
                    <div class="w-full bg-blue-500 rounded-t" style="height: {{ $height }}%"></div>
                    <span class="text-xs text-zinc-500">{{ DateTime::createFromFormat('!m', $m)->format('M') }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                    <tr>
                        <th class="px-4 py-3 text-center text-xs font-medium text-zinc-500 uppercase">SPK</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-zinc-500 uppercase">Tgl Selesai</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-zinc-500 uppercase">Output</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                    @forelse($spks as $spk)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                            <td class="px-4 py-4 font-medium text-zinc-900 dark:text-white">{{ $spk->spk_number }}</td>
                            <td class="px-4 py-4 text-zinc-600 dark:text-zinc-400">
                                {{ $spk->completed_at?->format('d M Y H:i') }}</td>
                            <td class="px-4 py-4 text-zinc-600 dark:text-zinc-400">
                                @foreach($spk->items->where('item_type', 'output') as $item)
                                    {{ $item->product->name }} ({{ $item->quantity_used }} {{ $item->unit }})@if(!$loop->last),
                                    @endif
                                @endforeach
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-12 text-center text-zinc-500">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
