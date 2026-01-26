@forelse($movements as $movement)
    <tr class="border-b border-zinc-100 dark:border-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
        <td
            class="px-4 py-3 text-center text-zinc-900 dark:text-white border-r border-zinc-100 dark:border-zinc-800 whitespace-nowrap">
            {{ $movement->created_at->format('d M Y H:i') }}
        </td>
        <td class="px-4 py-3 text-center border-r border-zinc-100 dark:border-zinc-800">
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
        <td class="px-4 py-3 text-center text-zinc-600 dark:text-zinc-400">
            {{ $movement->user->name ?? '-' }}
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="px-4 py-8 text-center text-zinc-500">Tidak ada riwayat</td>
    </tr>
@endforelse