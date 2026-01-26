<x-app-layout>
    @section('title', 'Detail FPB')

    <div class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Header -->
                <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <h2 class="text-xl font-bold text-zinc-900 dark:text-white">{{ $fpb->fpb_number }}</h2>
                            <p class="text-sm text-zinc-500 mt-1">Dibuat {{ $fpb->created_at->format('d M Y H:i') }}
                                oleh {{ $fpb->creator?->name ?? '-' }}</p>
                        </div>
                        <span class="px-3 py-1 text-sm font-medium rounded-full
                            @if($fpb->status === 'pending') bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400
                            @elseif($fpb->status === 'approved') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                            @else bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 @endif">
                            {{ $fpb->status_label }}
                        </span>
                    </div>
                </div>

                <!-- Info Cards -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-4">
                        <p class="text-xs text-zinc-500 uppercase tracking-wide">SPK</p>
                        @if($fpb->spk)
                            <a href="{{ route('spk.show', $fpb->spk) }}"
                                class="text-lg font-semibold text-blue-600 hover:underline">
                                {{ $fpb->spk->spk_number }}
                            </a>
                        @else
                            <p class="text-lg font-semibold text-zinc-900 dark:text-white">-</p>
                        @endif
                    </div>
                    <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-4">
                        <p class="text-xs text-zinc-500 uppercase tracking-wide">Tanggal Request</p>
                        <p class="text-lg font-semibold text-zinc-900 dark:text-white">
                            {{ $fpb->request_date->format('d M Y') }}</p>
                    </div>
                </div>

                <!-- Items Table -->
                <div
                    class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 overflow-hidden">
                    <div class="px-5 py-4 border-b border-zinc-200 dark:border-zinc-800">
                        <h3 class="font-semibold text-zinc-900 dark:text-white">Material yang Diminta</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-zinc-50 dark:bg-zinc-800">
                                <tr>
                                    <th class="px-4 py-3 text-left font-medium text-zinc-600 dark:text-zinc-300">Kode
                                    </th>
                                    <th class="px-4 py-3 text-left font-medium text-zinc-600 dark:text-zinc-300">Nama
                                        Material</th>
                                    <th class="px-4 py-3 text-right font-medium text-zinc-600 dark:text-zinc-300">Qty
                                        Diminta</th>
                                    <th class="px-4 py-3 text-right font-medium text-zinc-600 dark:text-zinc-300">Stok
                                        Saat Ini</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                                @foreach($fpb->items as $item)
                                    <tr>
                                        <td class="px-4 py-3 font-mono text-xs text-zinc-500">
                                            {{ $item->product?->code ?? '-' }}</td>
                                        <td class="px-4 py-3 text-zinc-900 dark:text-white">
                                            {{ $item->product?->name ?? '-' }}</td>
                                        <td class="px-4 py-3 text-right font-medium text-zinc-900 dark:text-white">
                                            {{ number_format($item->quantity_requested, 2) }} {{ $item->unit }}</td>
                                        <td class="px-4 py-3 text-right">
                                            @if($item->product)
                                                @if($item->product->current_stock >= $item->quantity_requested)
                                                    <span
                                                        class="text-green-600">{{ number_format($item->product->current_stock, 2) }}
                                                        {{ $item->product->unit }}</span>
                                                @else
                                                    <span class="text-red-600">{{ number_format($item->product->current_stock, 2) }}
                                                        {{ $item->product->unit }}</span>
                                                @endif
                                            @else
                                                <span class="text-zinc-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Notes -->
                @if($fpb->notes)
                    <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-5">
                        <h3 class="font-semibold text-zinc-900 dark:text-white mb-2">Catatan</h3>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ $fpb->notes }}</p>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Actions -->
                <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-5">
                    <h3 class="font-semibold text-zinc-900 dark:text-white mb-4">Aksi</h3>
                    <div class="space-y-3">
                        @if($fpb->status === 'pending')
                            @if(auth()->user()->role === 'owner')
                                <form method="POST" action="{{ route('fpb.approve', $fpb) }}">
                                    @csrf
                                    <button type="submit"
                                        onclick="return confirm('Approve FPB ini? Stok material akan dikurangi.')"
                                        class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium">
                                        ✓ Approve FPB
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('fpb.reject', $fpb) }}">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Tolak FPB ini?')"
                                        class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium">
                                        ✗ Tolak FPB
                                    </button>
                                </form>
                            @else
                                <div class="text-center py-4 text-zinc-500 text-sm">
                                    Menunggu approval dari Owner
                                </div>
                            @endif
                        @else
                            <div class="text-center py-4">
                                @if($fpb->status === 'approved')
                                    <div class="text-green-600">
                                        <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="text-sm font-medium">FPB Disetujui</p>
                                        @if($fpb->approver)
                                            <p class="text-xs text-zinc-500 mt-1">Oleh {{ $fpb->approver->name }} pada
                                                {{ $fpb->approved_at->format('d/m/Y H:i') }}</p>
                                        @endif
                                    </div>
                                @else
                                    <div class="text-red-600">
                                        <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="text-sm font-medium">FPB Ditolak</p>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <a href="{{ route('fpb.print', $fpb) }}" target="_blank"
                            class="block w-full px-4 py-2 border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-300 rounded-lg text-sm font-medium text-center hover:bg-zinc-50 dark:hover:bg-zinc-800">
                            🖨️ Print FPB
                        </a>
                        <a href="{{ route('fpb.index') }}"
                            class="block w-full px-4 py-2 border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-300 rounded-lg text-sm font-medium text-center hover:bg-zinc-50 dark:hover:bg-zinc-800">
                            ← Kembali
                        </a>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-5">
                    <h3 class="font-semibold text-zinc-900 dark:text-white mb-4">Timeline</h3>
                    <div class="space-y-4">
                        <div class="flex gap-3">
                            <div class="w-2 h-2 mt-2 rounded-full bg-blue-500"></div>
                            <div>
                                <p class="text-sm font-medium text-zinc-900 dark:text-white">Dibuat</p>
                                <p class="text-xs text-zinc-500">{{ $fpb->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        @if($fpb->approved_at)
                            <div class="flex gap-3">
                                <div
                                    class="w-2 h-2 mt-2 rounded-full {{ $fpb->status === 'approved' ? 'bg-green-500' : 'bg-red-500' }}">
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-zinc-900 dark:text-white">
                                        {{ $fpb->status === 'approved' ? 'Disetujui' : 'Ditolak' }}</p>
                                    <p class="text-xs text-zinc-500">{{ $fpb->approved_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>