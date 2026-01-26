<x-app-layout>
    {{-- @var \App\Models\Spk $spk --}}
    @section('title', 'Detail SPK')

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $spk->spk_number }}</h2>
                <p class="text-zinc-500 dark:text-zinc-400 mt-1">Detail Surat Perintah Kerja</p>
            </div>
            <div class="flex items-center gap-3">
                <span
                    class="px-3 py-1.5 text-sm font-medium rounded-full bg-{{ $spk->status_color }}-100 dark:bg-{{ $spk->status_color }}-900/30 text-{{ $spk->status_color }}-700 dark:text-{{ $spk->status_color }}-400">
                    {{ $spk->status_label }}
                </span>
                <a href="{{ route('spk.print', $spk) }}" target="_blank"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 rounded-lg">
                    Print
                </a>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Info Card -->
            <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-6">
                <h3 class="font-semibold text-zinc-900 dark:text-white mb-4">Informasi SPK</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-zinc-500 dark:text-zinc-400">Dibuat Oleh</p>
                        <p class="font-medium text-zinc-900 dark:text-white">{{ $spk->creator->name }}</p>
                    </div>
                    <div>
                        <p class="text-zinc-500 dark:text-zinc-400">Tanggal Dibuat</p>
                        <p class="font-medium text-zinc-900 dark:text-white">{{ $spk->created_at->format('d M Y H:i') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-zinc-500 dark:text-zinc-400">Tanggal Produksi</p>
                        <p class="font-medium text-zinc-900 dark:text-white">
                            {{ $spk->production_date?->format('d M Y') ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-zinc-500 dark:text-zinc-400">Deadline</p>
                        <p class="font-medium text-zinc-900 dark:text-white">
                            {{ $spk->deadline?->format('d M Y') ?? '-' }}</p>
                    </div>
                    @if($spk->approver)
                        <div>
                            <p class="text-zinc-500 dark:text-zinc-400">Disetujui Oleh</p>
                            <p class="font-medium text-zinc-900 dark:text-white">{{ $spk->approver->name }}</p>
                        </div>
                        <div>
                            <p class="text-zinc-500 dark:text-zinc-400">Tanggal Disetujui</p>
                            <p class="font-medium text-zinc-900 dark:text-white">
                                {{ $spk->approved_at?->format('d M Y H:i') }}</p>
                        </div>
                    @endif
                </div>
                @if($spk->notes)
                    <div class="mt-4 pt-4 border-t border-zinc-200 dark:border-zinc-800">
                        <p class="text-zinc-500 dark:text-zinc-400 text-sm">Catatan</p>
                        <p class="text-zinc-900 dark:text-white">{{ $spk->notes }}</p>
                    </div>
                @endif
            </div>

            <!-- PROGRESS OUTPUT - Only show when in_progress -->
            @if($spk->status === 'in_progress')
                <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800">
                    <div class="p-5 border-b border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
                        <h3 class="font-semibold text-zinc-900 dark:text-white">Log Produksi Harian</h3>
                        @php
                            $outputItems = $spk->items->where('item_type', 'output');
                            $totalTarget = $outputItems->sum('quantity_planned');
                            $totalProduced = $outputItems->sum(fn($item) => $item->total_produced);
                            $overallProgress = $totalTarget > 0 ? round(($totalProduced / $totalTarget) * 100, 1) : 0;
                        @endphp
                        <span
                            class="text-sm font-medium px-3 py-1 rounded-full {{ $overallProgress >= 100 ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' }}">
                            {{ $overallProgress }}%
                        </span>
                    </div>
                    <div class="p-5 space-y-4">
                        <!-- Output Items Progress -->
                        @foreach($spk->items->where('item_type', 'output') as $item)
                            @php
                                $produced = $item->total_produced;
                                $target = $item->quantity_planned;
                                $progress = $target > 0 ? round(($produced / $target) * 100, 1) : 0;
                            @endphp
                            <div class="border border-zinc-200 dark:border-zinc-700 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <div>
                                        <p class="font-medium text-zinc-900 dark:text-white">{{ $item->product->name }}</p>
                                        <p class="text-xs text-zinc-500">{{ $item->product->code }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p
                                            class="text-sm font-medium {{ $progress >= 100 ? 'text-green-600 dark:text-green-400' : 'text-zinc-900 dark:text-white' }}">
                                            {{ number_format($produced, 0) }} / {{ number_format($target, 0) }}
                                            {{ $item->unit }}
                                        </p>
                                        <p class="text-xs text-zinc-500">{{ $progress }}%</p>
                                    </div>
                                </div>
                                <!-- Progress Bar -->
                                <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-full h-2 mb-3">
                                    <div class="h-2 rounded-full {{ $progress >= 100 ? 'bg-green-500' : 'bg-blue-500' }}"
                                        style="width: {{ min(100, $progress) }}%"></div>
                                </div>
                            </div>
                        @endforeach

                        <!-- Add Log Form - SIMPLIFIED -->
                        <form method="POST" action="{{ route('spk.add-log', $spk) }}"
                            class="bg-zinc-50 dark:bg-zinc-800/50 rounded-lg p-4 border border-zinc-200 dark:border-zinc-700">
                            @csrf
                            <h4 class="text-sm font-medium text-zinc-900 dark:text-white mb-3">+ Tambah Progress</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-xs text-zinc-500 mb-1">Produk Output</label>
                                    <select name="spk_item_id" required
                                        class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                                        @foreach($spk->items->where('item_type', 'output') as $item)
                                            <option value="{{ $item->id }}">{{ $item->product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs text-zinc-500 mb-1">Qty Selesai</label>
                                    <input type="number" name="quantity" min="0.01" step="0.01" required placeholder="0"
                                        class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                                </div>
                                <div>
                                    <label class="block text-xs text-zinc-500 mb-1">Tanggal</label>
                                    <input type="date" name="work_date" value="{{ date('Y-m-d') }}" required
                                        class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                                </div>
                            </div>
                            <div class="mt-3 text-right">
                                <button type="submit"
                                    class="px-4 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                                    Simpan
                                </button>
                            </div>
                        </form>

                        <!-- Production Logs Table - SIMPLIFIED -->
                        @if($spk->productionLogs->count() > 0)
                            <div class="mt-4">
                                <h4 class="text-sm font-medium text-zinc-900 dark:text-white mb-2">Riwayat Log</h4>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-xs">
                                        <thead class="bg-zinc-100 dark:bg-zinc-800">
                                            <tr>
                                                <th class="px-3 py-2 text-left">Tanggal</th>
                                                <th class="px-3 py-2 text-left">Produk</th>
                                                <th class="px-3 py-2 text-center">Qty</th>
                                                <th class="px-3 py-2 text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                                            @foreach($spk->productionLogs->sortByDesc('work_date') as $log)
                                                <tr>
                                                    <td class="px-3 py-2">{{ $log->work_date->format('d/m/Y') }}</td>
                                                    <td class="px-3 py-2">{{ $log->spkItem->product->name ?? '-' }}</td>
                                                    <td class="px-3 py-2 text-center font-medium">
                                                        {{ number_format($log->quantity, 0) }}</td>
                                                    <td class="px-3 py-2 text-center">
                                                        <form method="POST" action="{{ route('spk.delete-log', $log->id) }}"
                                                            onsubmit="return confirm('Hapus log ini?')" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-500 hover:text-red-700">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Output -->
            <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800">
                <div class="p-5 border-b border-zinc-200 dark:border-zinc-800">
                    <h3 class="font-semibold text-zinc-900 dark:text-white">Output (Barang Jadi)</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                            <tr>
                                <th class="px-4 py-3 text-center text-xs font-medium text-zinc-500 uppercase">Produk
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-zinc-500 uppercase">Qty Target
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-zinc-500 uppercase">Qty
                                    Tercapai</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-zinc-500 uppercase">Progress
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                            @forelse($spk->items->where('item_type', 'output') as $item)
                                <tr>
                                    <td class="px-4 py-3">
                                        <p class="font-medium text-zinc-900 dark:text-white">{{ $item->product->name }}</p>
                                        <p class="text-xs text-zinc-500">{{ $item->product->code }}</p>
                                    </td>
                                    <td class="px-4 py-3 text-right text-zinc-900 dark:text-white">
                                        {{ number_format($item->quantity_planned, 0) }} {{ $item->unit }}</td>
                                    <td class="px-4 py-3 text-right text-zinc-900 dark:text-white">
                                        {{ number_format($item->quantity_used, 0) }} {{ $item->unit }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span
                                            class="px-2 py-0.5 text-xs font-medium rounded {{ $item->progress_percentage >= 100 ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' }}">
                                            {{ $item->progress_percentage }}%
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-zinc-500">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Actions Sidebar -->
        <div class="space-y-6">
            <!-- Actions Card -->
            <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-6">
                <h3 class="font-semibold text-zinc-900 dark:text-white mb-4">Aksi</h3>
                <div class="space-y-3">
                    @if($spk->status === 'pending')
                        @can('approve', $spk)
                            <form method="POST" action="{{ route('spk.approve', $spk) }}">
                                @csrf
                                <button type="submit"
                                    class="w-full py-2.5 px-4 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg">
                                    Setujui SPK
                                </button>
                            </form>
                            <form method="POST" action="{{ route('spk.reject', $spk) }}">
                                @csrf
                                <button type="submit"
                                    class="w-full py-2.5 px-4 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg">
                                    Tolak SPK
                                </button>
                            </form>
                        @endcan

                        @can('delete', $spk)
                            <form method="POST" action="{{ route('spk.destroy', $spk) }}"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus SPK ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full py-2.5 px-4 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg">
                                    Hapus SPK
                                </button>
                            </form>
                        @endcan
                    @endif

                    @if($spk->status === 'approved')
                        @can('start', $spk)
                            <form method="POST" action="{{ route('spk.start', $spk) }}">
                                @csrf
                                <button type="submit"
                                    class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg">
                                    Mulai Produksi
                                </button>
                            </form>
                        @endcan
                    @endif

                    @if($spk->status === 'in_progress')
                        @can('complete', $spk)
                            @php
                                $hasLogs = $spk->productionLogs->count() > 0;
                            @endphp
                            <form method="POST" action="{{ route('spk.complete', $spk) }}"
                                onsubmit="return confirm('Selesaikan produksi? Stok bahan baku akan dikurangi dan stok barang jadi akan ditambahkan.')">
                                @csrf
                                <button type="submit"
                                    class="w-full py-2.5 px-4 {{ $hasLogs ? 'bg-green-600 hover:bg-green-700' : 'bg-zinc-400 cursor-not-allowed' }} text-white font-medium rounded-lg"
                                    {{ $hasLogs ? '' : 'disabled' }}>
                                    {{ $hasLogs ? 'Selesaikan Produksi' : 'Belum Ada Log Produksi' }}
                                </button>
                            </form>
                        @endcan
                    @endif

                    @if(in_array($spk->status, ['approved', 'in_progress']))
                        <a href="{{ route('fpb.create-from-spk', $spk) }}"
                            class="block w-full py-2.5 px-4 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-lg text-center flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Buat FPB
                        </a>
                    @endif

                    <a href="{{ route('spk.print', $spk) }}" target="_blank"
                        class="block w-full py-2.5 px-4 bg-zinc-600 hover:bg-zinc-700 text-white font-medium rounded-lg text-center flex items-center justify-center gap-2">
                        <i data-lucide="printer" class="w-4 h-4"></i>
                        Print SPK
                    </a>

                    <a href="{{ route('spk.index') }}"
                        class="block w-full py-2.5 px-4 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-900 dark:text-white font-medium rounded-lg text-center">
                        Kembali ke Daftar
                    </a>
                </div>
            </div>

            <!-- Production History (Sidebar) -->
            @if($spk->productionLogs->count() > 0 && $spk->status !== 'in_progress')
                <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800">
                    <div class="p-5 border-b border-zinc-200 dark:border-zinc-800">
                        <h3 class="font-semibold text-zinc-900 dark:text-white">Riwayat Produksi</h3>
                    </div>
                    <div class="divide-y divide-zinc-200 dark:divide-zinc-800 max-h-80 overflow-y-auto">
                        @foreach($spk->productionLogs->sortByDesc('work_date') as $log)
                            <div class="p-4">
                                <div class="flex items-center justify-between mb-1">
                                    <span
                                        class="text-xs font-medium text-zinc-900 dark:text-white">{{ $log->work_date->format('d M Y') }}</span>
                                    <span
                                        class="text-xs font-bold text-green-600 dark:text-green-400">+{{ number_format($log->quantity, 0) }}</span>
                                </div>
                                <p class="text-xs text-zinc-600 dark:text-zinc-400">{{ $log->spkItem->product->name ?? '-' }}
                                </p>
                                @if($log->worker_name)
                                    <p class="text-xs text-zinc-500">Pelaksana: {{ $log->worker_name }}</p>
                                @endif
                                @if($log->start_time && $log->end_time)
                                    <p class="text-xs text-zinc-400">{{ \Carbon\Carbon::parse($log->start_time)->format('H:i') }} -
                                        {{ \Carbon\Carbon::parse($log->end_time)->format('H:i') }}</p>
                                @endif
                                @if($log->notes)
                                    <p class="text-xs text-zinc-500 italic mt-1">{{ $log->notes }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>