<x-app-layout>
    @section('title', 'Detail Surat Jalan - ' . $deliveryNote->sj_number)

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="mb-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
            <p class="text-green-700 dark:text-green-400">{{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
            <p class="text-red-700 dark:text-red-400">{{ session('error') }}</p>
        </div>
    @endif

    <div class="space-y-6">
        {{-- Header Card with Status --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 overflow-hidden">
            {{-- Status Bar --}}
            <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-700 
                @if($deliveryNote->status === 'pending') bg-yellow-50 dark:bg-yellow-900/20
                @elseif($deliveryNote->status === 'approved') bg-blue-50 dark:bg-blue-900/20
                @elseif($deliveryNote->status === 'delivered') bg-green-50 dark:bg-green-900/20
                @else bg-zinc-50 dark:bg-zinc-800/50
                @endif">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center gap-4">
                        <div>
                            <h1 class="text-xl font-bold text-zinc-900 dark:text-white">{{ $deliveryNote->sj_number }}</h1>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">
                                Dibuat {{ $deliveryNote->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold
                            @if($deliveryNote->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300
                            @elseif($deliveryNote->status === 'approved') bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300
                            @elseif($deliveryNote->status === 'delivered') bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300
                            @else bg-zinc-100 text-zinc-800 dark:bg-zinc-700 dark:text-zinc-300
                            @endif">
                            {{ $deliveryNote->status_label }}
                        </span>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        {{-- Print Button --}}
                        @if(in_array($deliveryNote->status, ['approved', 'delivered']))
                            <a href="{{ route('delivery-notes.print', $deliveryNote) }}" target="_blank"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-zinc-600 hover:bg-zinc-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z"></path></svg>
                                Cetak
                            </a>
                        @endif
                        
                        {{-- Tandai Terkirim --}}
                        @if($deliveryNote->status === 'approved')
                            <form action="{{ route('delivery-notes.delivered', $deliveryNote) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors"
                                    onclick="return confirm('Tandai surat jalan ini sebagai terkirim?')">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8"></path></svg>
                                    Tandai Terkirim
                                </button>
                            </form>
                        @endif

                        {{-- Create Invoice Button (only for delivered and no existing invoice) --}}
                        @if($deliveryNote->status === 'delivered' && !$deliveryNote->invoice_id)
                            @if(auth()->user()->isOwner() || auth()->user()->isFinance())
                                <a href="{{ route('invoice.create-from-delivery-note', $deliveryNote) }}"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    Buat Invoice
                                </a>
                            @endif
                        @endif

                        {{-- View Invoice Button if invoice exists --}}
                        @if($deliveryNote->invoice_id)
                            <a href="{{ route('invoice.show', $deliveryNote->invoice) }}"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 text-sm font-medium rounded-lg transition-colors hover:bg-indigo-200 dark:hover:bg-indigo-900/50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Lihat Invoice
                            </a>
                        @endif
                        
                        <a href="{{ route('delivery-notes.index') }}"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-zinc-200 hover:bg-zinc-300 dark:bg-zinc-700 dark:hover:bg-zinc-600 text-zinc-700 dark:text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>

            {{-- APPROVAL ALERT BANNER --}}
            @if($deliveryNote->status === 'pending')
                <div class="px-6 py-4 bg-yellow-100 dark:bg-yellow-900/30 border-b border-yellow-200 dark:border-yellow-800">
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-yellow-500/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <div>
                                <p class="font-semibold text-yellow-800 dark:text-yellow-200">Menunggu Approval Owner</p>
                                <p class="text-sm text-yellow-700 dark:text-yellow-300">
                                    @if(auth()->user()->isOwner())
                                        Klik tombol "APPROVE SEKARANG" di atas untuk menyetujui surat jalan ini.
                                    @else
                                        Surat jalan ini harus disetujui oleh Owner sebelum bisa dicetak. (Login sebagai Owner untuk approve)
                                    @endif
                                </p>
                            </div>
                        </div>
                        @if(auth()->user()->isOwner())
                            <form action="{{ route('delivery-notes.approve', $deliveryNote) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition-colors shadow-lg"
                                    onclick="return confirm('Setujui surat jalan ini?')">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    APPROVE
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endif
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Left: Pengiriman Info --}}
                    <div class="space-y-4">
                        <h3 class="text-sm font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Informasi Pengiriman</h3>
                        <div class="bg-zinc-50 dark:bg-zinc-800/50 rounded-lg overflow-hidden">
                            <table class="w-full text-sm">
                                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                                    <tr>
                                        <td class="px-4 py-2.5 text-zinc-500 dark:text-zinc-400 w-32">Tanggal Kirim</td>
                                        <td class="px-4 py-2.5 font-medium text-zinc-900 dark:text-white">{{ $deliveryNote->delivery_date?->format('d M Y') ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-2.5 text-zinc-500 dark:text-zinc-400">Pengemudi</td>
                                        <td class="px-4 py-2.5 font-medium text-zinc-900 dark:text-white">{{ $deliveryNote->driver_name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-2.5 text-zinc-500 dark:text-zinc-400">Kendaraan</td>
                                        <td class="px-4 py-2.5 font-medium text-zinc-900 dark:text-white">{{ $deliveryNote->vehicle_number ?? '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Right: Customer Info --}}
                    <div class="space-y-4">
                        <h3 class="text-sm font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Informasi Penerima</h3>
                        <div class="bg-zinc-50 dark:bg-zinc-800/50 rounded-lg overflow-hidden">
                            <table class="w-full text-sm">
                                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                                    <tr>
                                        <td class="px-4 py-2.5 text-zinc-500 dark:text-zinc-400 w-32">Customer</td>
                                        <td class="px-4 py-2.5 font-medium text-zinc-900 dark:text-white">{{ $deliveryNote->customer?->name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-2.5 text-zinc-500 dark:text-zinc-400">Penerima</td>
                                        <td class="px-4 py-2.5 font-medium text-zinc-900 dark:text-white">{{ $deliveryNote->recipient_name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-2.5 text-zinc-500 dark:text-zinc-400">Telepon</td>
                                        <td class="px-4 py-2.5 font-medium text-zinc-900 dark:text-white">{{ $deliveryNote->customer?->phone ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-2.5 text-zinc-500 dark:text-zinc-400">Alamat</td>
                                        <td class="px-4 py-2.5 font-medium text-zinc-900 dark:text-white">{{ $deliveryNote->delivery_address ?? '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Items Table --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 overflow-hidden">
            <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-700">
                <h3 class="font-semibold text-zinc-900 dark:text-white">Daftar Barang</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                        <tr>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase w-12">No</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase">Nama Barang</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase w-32">Kode</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase w-24">Qty</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase w-20">Satuan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                        @forelse($deliveryNote->items as $index => $item)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                                <td class="px-4 py-3 text-center text-zinc-600 dark:text-zinc-400">{{ $index + 1 }}</td>
                                <td class="px-4 py-3">
                                    <p class="font-medium text-zinc-900 dark:text-white">{{ $item->product?->name ?? '-' }}</p>
                                </td>
                                <td class="px-4 py-3 text-center text-zinc-600 dark:text-zinc-400">{{ $item->product?->code ?? '-' }}</td>
                                <td class="px-4 py-3 text-center font-semibold text-zinc-900 dark:text-white">{{ number_format($item->quantity, 0) }}</td>
                                <td class="px-4 py-3 text-center text-zinc-600 dark:text-zinc-400">{{ $item->unit }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-zinc-500">Tidak ada item</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Notes & Timeline Row --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Notes --}}
            @if($deliveryNote->notes)
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6">
                <h3 class="font-semibold text-zinc-900 dark:text-white mb-3">Catatan</h3>
                <p class="text-zinc-700 dark:text-zinc-300 text-sm">{{ $deliveryNote->notes }}</p>
            </div>
            @endif

            {{-- Timeline --}}
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6 {{ !$deliveryNote->notes ? 'md:col-span-2' : '' }}">
                <h3 class="font-semibold text-zinc-900 dark:text-white mb-4">Riwayat</h3>
                <div class="relative pl-6 space-y-4">
                    {{-- Line --}}
                    <div class="absolute left-2 top-2 bottom-2 w-px bg-zinc-200 dark:bg-zinc-700"></div>
                    
                    {{-- Dibuat --}}
                    <div class="relative flex items-start gap-3">
                        <div class="absolute -left-4 w-4 h-4 bg-blue-500 rounded-full border-2 border-white dark:border-zinc-900"></div>
                        <div>
                            <p class="text-sm font-medium text-zinc-900 dark:text-white">Dibuat</p>
                            <p class="text-xs text-zinc-500">{{ $deliveryNote->created_at->format('d M Y, H:i') }} oleh {{ $deliveryNote->creator?->name ?? '-' }}</p>
                        </div>
                    </div>
                    
                    {{-- Disetujui --}}
                    @if($deliveryNote->approved_at)
                    <div class="relative flex items-start gap-3">
                        <div class="absolute -left-4 w-4 h-4 bg-indigo-500 rounded-full border-2 border-white dark:border-zinc-900"></div>
                        <div>
                            <p class="text-sm font-medium text-zinc-900 dark:text-white">Disetujui</p>
                            <p class="text-xs text-zinc-500">{{ $deliveryNote->approved_at->format('d M Y, H:i') }} oleh {{ $deliveryNote->approver?->name ?? '-' }}</p>
                        </div>
                    </div>
                    @endif
                    
                    {{-- Terkirim --}}
                    @if($deliveryNote->delivered_at)
                    <div class="relative flex items-start gap-3">
                        <div class="absolute -left-4 w-4 h-4 bg-green-500 rounded-full border-2 border-white dark:border-zinc-900"></div>
                        <div>
                            <p class="text-sm font-medium text-zinc-900 dark:text-white">Terkirim</p>
                            <p class="text-xs text-zinc-500">{{ $deliveryNote->delivered_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>