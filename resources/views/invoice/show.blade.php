<x-app-layout>
    @section('title', 'Detail Invoice')

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $invoice->invoice_number }}</h2>
                <p class="text-zinc-500 dark:text-zinc-400 mt-1">Detail Invoice</p>
            </div>
            <div class="flex items-center gap-3">
                <span
                    class="px-3 py-1.5 text-sm font-medium rounded-full bg-{{ $invoice->status_color }}-100 dark:bg-{{ $invoice->status_color }}-900/30 text-{{ $invoice->status_color }}-700 dark:text-{{ $invoice->status_color }}-400">
                    {{ $invoice->status_label }}
                </span>
                <a href="{{ route('invoice.print', $invoice) }}" target="_blank"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 rounded-lg transition-colors">
                    <i data-lucide="printer" class="w-4 h-4"></i>
                    Print
                </a>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Customer Info -->
            <div
                class="relative bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6 overflow-hidden">
                @if($invoice->status === 'paid')
                    <div
                        class="absolute top-1/2 right-10 -translate-y-1/2 transform -rotate-12 border-4 border-green-500 text-green-500 text-5xl font-black opacity-30 pointer-events-none select-none z-0 px-6 py-2 rounded-xl">
                        LUNAS
                    </div>
                @endif
                <h3 class="font-semibold text-zinc-900 dark:text-white mb-4 relative z-10">Informasi Customer</h3>
                @if($invoice->customer)
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-zinc-500 dark:text-zinc-400">Nama</p>
                            <p class="font-medium text-zinc-900 dark:text-white">{{ $invoice->customer->name }}</p>
                        </div>
                        @if($invoice->customer->company)
                            <div>
                                <p class="text-zinc-500 dark:text-zinc-400">Perusahaan</p>
                                <p class="font-medium text-zinc-900 dark:text-white">{{ $invoice->customer->company }}</p>
                            </div>
                        @endif
                        @if($invoice->customer->phone)
                            <div>
                                <p class="text-zinc-500 dark:text-zinc-400">Telepon</p>
                                <p class="font-medium text-zinc-900 dark:text-white">{{ $invoice->customer->phone }}</p>
                            </div>
                        @endif
                        @if($invoice->customer->email)
                            <div>
                                <p class="text-zinc-500 dark:text-zinc-400">Email</p>
                                <p class="font-medium text-zinc-900 dark:text-white">{{ $invoice->customer->email }}</p>
                            </div>
                        @endif
                    </div>
                @else
                    <p class="text-zinc-500">Walk-in Customer</p>
                @endif
            </div>

            <!-- Items -->
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800">
                <div class="p-5 border-b border-zinc-200 dark:border-zinc-800">
                    <h3 class="font-semibold text-zinc-900 dark:text-white">Item Invoice</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                            <tr>
                                <th class="px-4 py-3 text-center text-xs font-medium text-zinc-500 uppercase">Produk
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-zinc-500 uppercase">Qty</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-zinc-500 uppercase">Harga</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-zinc-500 uppercase">Subtotal
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                            @foreach($invoice->items as $item)
                                <tr>
                                    <td class="px-4 py-3">
                                        <p class="font-medium text-zinc-900 dark:text-white">{{ $item->product->name }}</p>
                                        <p class="text-sm text-zinc-500">{{ $item->product->code }}</p>
                                    </td>
                                    <td class="px-4 py-3 text-right text-zinc-900 dark:text-white">
                                        {{ number_format($item->quantity) }}
                                    </td>
                                    <td class="px-4 py-3 text-right text-zinc-900 dark:text-white">Rp
                                        {{ number_format($item->unit_price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-right font-medium text-zinc-900 dark:text-white">Rp
                                        {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Invoice Info -->
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6">
                <h3 class="font-semibold text-zinc-900 dark:text-white mb-4">Informasi Invoice</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-zinc-500">Tanggal</span>
                        <span class="text-zinc-900 dark:text-white">{{ $invoice->invoice_date->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-zinc-500">Jatuh Tempo</span>
                        <span
                            class="text-zinc-900 dark:text-white">{{ $invoice->due_date?->format('d M Y') ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-zinc-500">Dibuat Oleh</span>
                        <span class="text-zinc-900 dark:text-white">{{ $invoice->creator->name }}</span>
                    </div>
                    @if($invoice->deliveryNote)
                        <div class="flex justify-between">
                            <span class="text-zinc-500">Surat Jalan</span>
                            <a href="{{ route('delivery-notes.show', $invoice->deliveryNote) }}"
                                class="text-blue-600 dark:text-blue-400 hover:underline font-medium">
                                {{ $invoice->deliveryNote->sj_number }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Summary -->
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6">
                <h3 class="font-semibold text-zinc-900 dark:text-white mb-4">Ringkasan</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-zinc-500">Subtotal</span>
                        <span class="text-zinc-900 dark:text-white">Rp
                            {{ number_format((float) $invoice->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if($invoice->tax_amount > 0)
                        <div class="flex justify-between">
                            <span class="text-zinc-500">Pajak ({{ $invoice->tax_percent }}%)</span>
                            <span class="text-zinc-900 dark:text-white">Rp
                                {{ number_format((float) $invoice->tax_amount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    @if($invoice->discount > 0)
                        <div class="flex justify-between">
                            <span class="text-zinc-500">Diskon</span>
                            <span class="text-red-500">- Rp
                                {{ number_format((float) $invoice->discount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <hr class="border-zinc-200 dark:border-zinc-800">
                    <div class="flex justify-between text-lg">
                        <span class="font-semibold text-zinc-900 dark:text-white">Total</span>
                        <span class="font-bold text-blue-600 dark:text-blue-400">Rp
                            {{ number_format((float) $invoice->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6">
                <div class="space-y-3">
                    @if($invoice->status !== 'cancelled')
                        <a href="{{ route('invoice.edit', $invoice) }}"
                            class="block w-full py-2.5 px-4 mb-3 bg-yellow-500 hover:bg-yellow-600 text-white font-medium rounded-lg text-center transition-colors flex items-center justify-center gap-2">
                            <i data-lucide="edit" class="w-4 h-4"></i>
                            Edit Invoice
                        </a>
                    @endif

                    @if($invoice->isPendingApproval() && auth()->user()->isOwner())
                        <form method="POST" action="{{ route('invoice.approve', $invoice) }}">
                            @csrf
                            <button type="submit"
                                class="w-full py-2.5 px-4 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors flex items-center justify-center gap-2">
                                <i data-lucide="check" class="w-4 h-4"></i>
                                Setujui & Rilis
                            </button>
                        </form>
                        <form method="POST" action="{{ route('invoice.reject', $invoice) }}"
                            onsubmit="return confirm('Yakin tolak invoice ini?')">
                            @csrf
                            <button type="submit"
                                class="w-full py-2.5 px-4 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors flex items-center justify-center gap-2">
                                <i data-lucide="x" class="w-4 h-4"></i>
                                Tolak
                            </button>
                        </form>
                    @elseif($invoice->status !== 'paid' && $invoice->status !== 'pending_approval' && $invoice->status !== 'cancelled')
                        <div class="space-y-3" x-data="{ paymentAmount: {{ $invoice->remaining_amount }} }">
                            <div class="p-3 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-zinc-500">Total Invoice:</span>
                                    <span class="font-medium text-zinc-900 dark:text-white">Rp
                                        {{ number_format((float) $invoice->total, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-zinc-500">Sudah Dibayar:</span>
                                    <span class="font-medium text-green-600">Rp
                                        {{ number_format((float) $invoice->paid_amount, 0, ',', '.') }}</span>
                                </div>
                                <div
                                    class="flex justify-between text-sm border-t border-zinc-200 dark:border-zinc-700 pt-2">
                                    <span class="text-zinc-500">Sisa:</span>
                                    <span class="font-bold text-red-600">Rp
                                        {{ number_format($invoice->remaining_amount, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('invoice.payment', $invoice) }}">
                                @csrf
                                <label class="block text-xs text-zinc-500 mb-1">Jumlah Pembayaran</label>
                                <input type="number" name="amount" x-model="paymentAmount" min="1"
                                    max="{{ $invoice->remaining_amount }}" step="1"
                                    class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white mb-2">
                                <div class="flex gap-2 mb-2">
                                    <button type="button" @click="paymentAmount = {{ $invoice->remaining_amount }}"
                                        class="flex-1 px-2 py-1 text-xs bg-zinc-200 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300 rounded hover:bg-zinc-300 dark:hover:bg-zinc-600">Lunaskan</button>
                                    <button type="button"
                                        @click="paymentAmount = Math.round({{ $invoice->remaining_amount }} / 2)"
                                        class="flex-1 px-2 py-1 text-xs bg-zinc-200 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300 rounded hover:bg-zinc-300 dark:hover:bg-zinc-600">50%</button>
                                </div>
                                <button type="submit"
                                    class="w-full py-2.5 px-4 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors flex items-center justify-center gap-2">
                                    <i data-lucide="banknote" class="w-4 h-4"></i>
                                    Catat Pembayaran
                                </button>
                            </form>
                        </div>
                    @endif
                    @if($invoice->isPendingApproval())
                        <div
                            class="p-3 bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg text-center">
                            <p class="text-sm text-orange-700 dark:text-orange-400 font-medium">
                                <i data-lucide="clock" class="w-4 h-4 inline mr-1"></i>
                                Menunggu persetujuan Owner
                            </p>
                        </div>
                    @endif
                    <a href="{{ route('invoice.print', $invoice) }}" target="_blank"
                        class="block w-full py-2.5 px-4 bg-zinc-600 hover:bg-zinc-700 text-white font-medium rounded-lg text-center transition-colors flex items-center justify-center gap-2">
                        <i data-lucide="printer" class="w-4 h-4"></i>
                        Print Invoice
                    </a>
                    <a href="{{ route('invoice.index') }}"
                        class="block w-full py-2.5 px-4 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-900 dark:text-white font-medium rounded-lg text-center transition-colors">
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>