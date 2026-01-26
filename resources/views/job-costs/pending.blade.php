<x-app-layout>
    @section('title', 'Job Cost Pending Approval')

    <div class="mb-4 flex items-center justify-between">
        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Job Cost Pending Approval</h2>
        <a href="{{ route('job-costs.index') }}" class="text-sm text-blue-600 hover:underline">Lihat Semua JC</a>
    </div>

    <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50 border-b border-zinc-200 dark:border-zinc-700">
                    <tr>
                        <th
                            class="px-4 py-3 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                            No. JC</th>
                        <th
                            class="px-4 py-3 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                            Tanggal</th>
                        <th
                            class="px-4 py-3 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                            Deskripsi</th>
                        <th
                            class="px-4 py-3 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                            Dibuat Oleh</th>
                        <th
                            class="px-4 py-3 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                            Items</th>
                        <th
                            class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse($jobCosts as $jc)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                            <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">
                                <a href="{{ route('job-costs.show', $jc) }}"
                                    class="text-blue-600 hover:underline">{{ $jc->job_cost_number }}</a>
                            </td>
                            <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400">{{ $jc->date->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-zinc-900 dark:text-white">{{ Str::limit($jc->description, 40) }}</td>
                            <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400">{{ $jc->creator->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400">
                                <ul class="text-xs">
                                    @foreach($jc->items as $item)
                                        <li>{{ $item->product->name ?? '-' }}: {{ number_format($item->quantity, 2) }}
                                            {{ $item->product->unit ?? '' }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <form action="{{ route('job-costs.approve', $jc) }}" method="POST"
                                        onsubmit="return confirm('Setujui Job Cost ini? Stok akan dikurangi.')">
                                        @csrf
                                        <button type="submit"
                                            class="px-3 py-1 text-xs bg-green-600 hover:bg-green-700 text-white rounded">Approve</button>
                                    </form>
                                    <form action="{{ route('job-costs.reject', $jc) }}" method="POST"
                                        onsubmit="return confirm('Tolak Job Cost ini?')">
                                        @csrf
                                        <button type="submit"
                                            class="px-3 py-1 text-xs bg-red-600 hover:bg-red-700 text-white rounded">Reject</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-zinc-500 dark:text-zinc-400">Tidak ada job
                                cost pending approval.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($jobCosts->hasPages())
            <div class="px-4 py-3 border-t border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50">
                {{ $jobCosts->links() }}
            </div>
        @endif
    </div>
</x-app-layout>