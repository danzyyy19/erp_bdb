@forelse($movements as $movement)
    <tr class="border-b border-zinc-100 dark:border-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
        <td
            class="px-4 py-3 text-zinc-900 dark:text-white border-r border-zinc-100 dark:border-zinc-800 whitespace-nowrap text-center">
            {{ $movement->created_at->format('d M Y H:i') }}
        </td>
        <td class="px-4 py-3 border-r border-zinc-100 dark:border-zinc-800 text-center">
            <p class="font-medium text-zinc-900 dark:text-white">{{ $movement->product->name }}</p>
            <p class="text-xs text-zinc-500">{{ $movement->product->code }}</p>
        </td>
        <td class="px-4 py-3 text-center border-r border-zinc-100 dark:border-zinc-800">
            @php
                $typeClasses = [
                    'in' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400',
                    'out' => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400',
                    'adjustment' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400',
                    'production_in' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400',
                    'production_out' => 'bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400',
                ];
            @endphp
            <span
                class="px-2 py-0.5 text-xs font-medium rounded {{ $typeClasses[$movement->type] ?? 'bg-zinc-100 text-zinc-700' }}">
                {{ $movement->type_label }}
            </span>
        </td>
        <td
            class="px-4 py-3 text-center font-medium border-r border-zinc-100 dark:border-zinc-800 {{ $movement->isStockIn() ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
            {{ $movement->isStockIn() ? '+' : '-' }}{{ number_format($movement->quantity, 0) }}
        </td>
        <td class="px-4 py-3 text-center text-zinc-500 border-r border-zinc-100 dark:border-zinc-800">
            {{ number_format($movement->stock_before, 0) }}
        </td>
        <td
            class="px-4 py-3 text-center font-medium text-zinc-900 dark:text-white border-r border-zinc-100 dark:border-zinc-800">
            {{ number_format($movement->stock_after, 0) }}
        </td>
        <td class="px-4 py-3 text-center text-zinc-600 dark:text-zinc-400 border-r border-zinc-100 dark:border-zinc-800">
            {{ $movement->user->name ?? '-' }}
        </td>
        <td
            class="px-4 py-3 text-center text-zinc-500 max-w-[150px] truncate border-r border-zinc-100 dark:border-zinc-800">
            {{ $movement->notes ?? '-' }}
        </td>
        <td class="px-4 py-3 text-center">
            @if($movement->reference_type === 'spk' && $movement->reference_id)
                @php $spk = \App\Models\Spk::find($movement->reference_id); @endphp
                @if($spk)
                    <a href="{{ route('spk.show', $spk) }}"
                        class="inline-flex items-center gap-1 px-2 py-1 text-xs bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors"
                        title="Lihat SPK">
                        <i data-lucide="file-text" class="w-3 h-3"></i>
                        SPK
                    </a>
                @else
                    <span class="text-zinc-400">-</span>
                @endif
            @elseif($movement->reference_type === 'invoice' && $movement->reference_id)
                @php $invoice = \App\Models\Invoice::find($movement->reference_id); @endphp
                @if($invoice)
                    <a href="{{ route('invoice.show', $invoice) }}"
                        class="inline-flex items-center gap-1 px-2 py-1 text-xs bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded hover:bg-green-200 dark:hover:bg-green-900/50 transition-colors"
                        title="Lihat Invoice">
                        <i data-lucide="receipt" class="w-3 h-3"></i>
                        INV
                    </a>
                @else
                    <span class="text-zinc-400">-</span>
                @endif
            @else
                <span class="text-zinc-400">-</span>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="9" class="px-4 py-8 text-center text-zinc-500">Tidak ada riwayat stok</td>
    </tr>
@endforelse