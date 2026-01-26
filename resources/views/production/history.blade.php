<x-app-layout>
    @section('title', 'History Produksi')

    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">History Produksi</h2>
            <p class="text-zinc-500 dark:text-zinc-400 mt-1">Log aktivitas produksi</p>
        </div>
    </x-slot>

    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                    <tr>
                        <th class="px-4 py-3 text-center text-xs font-medium text-zinc-500 uppercase">Waktu</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-zinc-500 uppercase">SPK</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-zinc-500 uppercase">Aksi</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-zinc-500 uppercase">User</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-zinc-500 uppercase">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                    @forelse($logs as $log)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                            <td class="px-4 py-4 text-zinc-900 dark:text-white">{{ $log->created_at->format('d M Y H:i') }}
                            </td>
                            <td class="px-4 py-4 font-medium text-blue-600 dark:text-blue-400">{{ $log->spk->spk_number }}
                            </td>
                            <td class="px-4 py-4">
                                <span
                                    class="px-2 py-1 text-xs rounded-full bg-{{ $log->action_color }}-100 dark:bg-{{ $log->action_color }}-900/30 text-{{ $log->action_color }}-700 dark:text-{{ $log->action_color }}-400">
                                    {{ $log->action_label }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-zinc-600 dark:text-zinc-400">{{ $log->user->name }}</td>
                            <td class="px-4 py-4 text-zinc-500 dark:text-zinc-400">{{ $log->notes ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center text-zinc-500">Tidak ada log produksi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
            <div class="px-4 py-3 border-t border-zinc-200 dark:border-zinc-800">{{ $logs->links() }}</div>
        @endif
    </div>
</x-app-layout>
