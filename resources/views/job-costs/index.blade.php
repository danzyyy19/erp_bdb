<x-app-layout>
    @section('title', 'Daftar Job Cost')

    <!-- Filters -->
    <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-4 mb-4">
        <form method="GET" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="No. JC atau deskripsi..."
                    class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
            </div>
            <div class="w-32">
                <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Status</label>
                <select name="status"
                    class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                    <option value="">Semua</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="w-36">
                <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Dari Tanggal</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                    class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
            </div>
            <div class="w-36">
                <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Sampai</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                    class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
            </div>
            <button type="submit"
                class="px-4 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Filter</button>
        </form>
    </div>

    <!-- Action Button -->
    <div class="mb-4 flex justify-between">
        <a href="{{ route('job-costs.create') }}"
            class="px-4 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Buat Job Cost</a>
    </div>

    <!-- Table -->
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
                            Dibuat</th>
                        <th
                            class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                            Status</th>
                        <th
                            class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @php
                        $statusColors = [
                            'draft' => 'bg-zinc-100 dark:bg-zinc-700 text-zinc-600 dark:text-zinc-400',
                            'pending' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400',
                            'approved' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400',
                            'rejected' => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400',
                        ];
                    @endphp
                    @forelse($jobCosts as $jc)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                            <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">{{ $jc->job_cost_number }}</td>
                            <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400">{{ $jc->date->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-zinc-900 dark:text-white">{{ Str::limit($jc->description, 40) }}</td>
                            <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400">{{ $jc->creator->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-center">
                                <span
                                    class="px-2 py-0.5 text-xs rounded {{ $statusColors[$jc->status] ?? '' }}">{{ $jc->status_label }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('job-costs.show', $jc) }}"
                                        class="p-1.5 text-zinc-500 hover:text-blue-600" title="Detail">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    @if(in_array($jc->status, ['draft', 'pending']))
                                        <a href="{{ route('job-costs.edit', $jc) }}"
                                            class="p-1.5 text-zinc-500 hover:text-yellow-600" title="Edit">
                                            <i data-lucide="edit" class="w-4 h-4"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-zinc-500 dark:text-zinc-400">Tidak ada job
                                cost.</td>
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