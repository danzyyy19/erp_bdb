@forelse($spks as $spk)
    <tr class="border-b border-zinc-100 dark:border-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
        <td class="px-4 py-3 font-mono text-sm text-zinc-900 dark:text-white border-r border-zinc-100 dark:border-zinc-800">
            {{ $spk->spk_number }}
        </td>
        <td
            class="px-3 py-3 text-sm text-center text-zinc-600 dark:text-zinc-400 border-r border-zinc-100 dark:border-zinc-800">
            {{ $spk->creator->name ?? '-' }}
        </td>
        <td
            class="px-3 py-3 text-sm text-center text-zinc-600 dark:text-zinc-400 border-r border-zinc-100 dark:border-zinc-800 whitespace-nowrap">
            {{ $spk->production_date?->format('d M Y') ?? '-' }}
        </td>
        <td
            class="px-3 py-3 text-sm text-center text-zinc-600 dark:text-zinc-400 border-r border-zinc-100 dark:border-zinc-800 whitespace-nowrap">
            {{ $spk->deadline?->format('d M Y') ?? '-' }}
        </td>
        <td
            class="px-3 py-3 text-sm text-center text-zinc-600 dark:text-zinc-400 border-r border-zinc-100 dark:border-zinc-800">
            {{ $spk->items->where('item_type', 'output')->count() }} item
        </td>
        <td class="px-3 py-3 text-center border-r border-zinc-100 dark:border-zinc-800">
            @php
                $statusClasses = [
                    'pending' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400',
                    'approved' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400',
                    'in_progress' => 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400',
                    'completed' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400',
                    'rejected' => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400',
                    'cancelled' => 'bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-400',
                ];
            @endphp
            <span
                class="px-2 py-1 text-xs font-medium rounded {{ $statusClasses[$spk->status] ?? '' }}">{{ $spk->status_label }}</span>
        </td>
        <td class="px-3 py-3 text-center">
            <div class="flex items-center justify-center gap-2">
                <a href="{{ route('spk.show', $spk) }}"
                    class="px-2 py-1 text-xs text-blue-600 dark:text-blue-400 hover:underline">Detail</a>
                @if($spk->status === 'pending' && (auth()->user()->isOwner() || auth()->user()->isOperasional()))
                    <span class="text-zinc-300 dark:text-zinc-700">|</span>
                    <a href="{{ route('spk.edit', $spk) }}"
                        class="px-2 py-1 text-xs text-amber-600 dark:text-amber-400 hover:underline">Edit</a>
                @endif
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="px-4 py-8 text-center text-sm text-zinc-500">Tidak ada SPK</td>
    </tr>
@endforelse