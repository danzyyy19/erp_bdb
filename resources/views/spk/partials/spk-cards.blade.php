@forelse($spks as $spk)
    <div class="bg-white dark:bg-zinc-900 p-4 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-800">
        <div class="flex justify-between items-start mb-3">
            <div>
                <div class="font-bold text-zinc-900 dark:text-white">{{ $spk->spk_number }}</div>
                <div class="text-xs text-zinc-500 dark:text-zinc-400">
                    <span class="font-medium">{{ $spk->creator->name ?? '-' }}</span>
                </div>
            </div>
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
            <span class="px-2 py-1 text-xs font-medium rounded {{ $statusClasses[$spk->status] ?? '' }}">
                {{ $spk->status_label }}
            </span>
        </div>

        <div class="space-y-2 text-sm mb-4">
            <div class="flex justify-between">
                <span class="text-zinc-500 dark:text-zinc-400">Tgl Produksi:</span>
                <span
                    class="font-medium text-zinc-900 dark:text-white">{{ $spk->production_date?->format('d M Y') ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-zinc-500 dark:text-zinc-400">Deadline:</span>
                <span class="font-medium text-zinc-900 dark:text-white">{{ $spk->deadline?->format('d M Y') ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-zinc-500 dark:text-zinc-400">Output:</span>
                <span
                    class="font-medium text-zinc-900 dark:text-white">{{ $spk->items->where('item_type', 'output')->count() }}
                    item</span>
            </div>
        </div>

        <div class="flex justify-end gap-2 pt-3 border-t border-zinc-100 dark:border-zinc-800">
            <a href="{{ route('spk.show', $spk) }}"
                class="px-3 py-1.5 text-xs bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 rounded-md">
                Detail
            </a>
            @if($spk->status === 'pending' && (auth()->user()->isOwner() || auth()->user()->isOperasional()))
                <a href="{{ route('spk.edit', $spk) }}"
                    class="px-3 py-1.5 text-xs bg-yellow-50 dark:bg-yellow-900/30 hover:bg-yellow-100 dark:hover:bg-yellow-900/50 text-yellow-700 dark:text-yellow-400 rounded-md">
                    Edit
                </a>
            @endif
        </div>
    </div>
@empty
    <div class="text-center p-8 text-zinc-500 dark:text-zinc-400">
        Tidak ada SPK.
    </div>
@endforelse