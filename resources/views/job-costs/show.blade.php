<x-app-layout>
    @section('title', 'Detail Job Cost')

    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">{{ $jobCost->job_cost_number }}</h2>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $jobCost->date->format('d F Y') }}</p>
            </div>
            @php
                $statusColors = [
                    'draft' => 'bg-zinc-100 dark:bg-zinc-700 text-zinc-600 dark:text-zinc-400',
                    'pending' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400',
                    'approved' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400',
                    'rejected' => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400',
                ];
            @endphp
            <span
                class="px-3 py-1 text-sm rounded {{ $statusColors[$jobCost->status] ?? '' }}">{{ $jobCost->status_label }}</span>
        </div>

        @if(session('success'))
            <div class="mb-4 p-3 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-3 rounded-lg bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- Info -->
        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-5 mb-4">
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-zinc-500 dark:text-zinc-400">Deskripsi</p>
                    <p class="font-medium text-zinc-900 dark:text-white">{{ $jobCost->description }}</p>
                </div>
                <div>
                    <p class="text-zinc-500 dark:text-zinc-400">Dibuat Oleh</p>
                    <p class="font-medium text-zinc-900 dark:text-white">{{ $jobCost->creator->name ?? '-' }}</p>
                </div>
                @if($jobCost->notes)
                    <div class="col-span-2">
                        <p class="text-zinc-500 dark:text-zinc-400">Catatan</p>
                        <p class="text-zinc-900 dark:text-white">{{ $jobCost->notes }}</p>
                    </div>
                @endif
                @if($jobCost->approver)
                    <div>
                        <p class="text-zinc-500 dark:text-zinc-400">Disetujui Oleh</p>
                        <p class="font-medium text-zinc-900 dark:text-white">{{ $jobCost->approver->name }}</p>
                    </div>
                    <div>
                        <p class="text-zinc-500 dark:text-zinc-400">Tanggal Approval</p>
                        <p class="font-medium text-zinc-900 dark:text-white">
                            {{ $jobCost->approved_at?->format('d/m/Y H:i') }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Items -->
        <div
            class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 overflow-hidden mb-4">
            <div class="p-4 border-b border-zinc-200 dark:border-zinc-700">
                <h3 class="font-semibold text-sm text-zinc-900 dark:text-white">Item Produk</h3>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-400">Produk
                        </th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-zinc-600 dark:text-zinc-400">Qty</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-400">Catatan
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @foreach($jobCost->items as $item)
                        <tr>
                            <td class="px-4 py-3 text-zinc-900 dark:text-white">
                                {{ $item->product->code ?? '-' }} - {{ $item->product->name ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-right text-zinc-900 dark:text-white">
                                {{ number_format($item->quantity, 2) }} {{ $item->product->unit ?? '' }}
                            </td>
                            <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400">{{ $item->notes ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between">
            <a href="{{ route('job-costs.index') }}"
                class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-900">← Kembali</a>

            <div class="flex gap-2">
                @if($jobCost->status === 'draft')
                    <a href="{{ route('job-costs.edit', $jobCost) }}"
                        class="px-4 py-2 text-sm bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg">Edit</a>
                    <form action="{{ route('job-costs.submit', $jobCost) }}" method="POST"
                        onsubmit="return confirm('Ajukan Job Cost ini untuk approval?')">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Submit untuk
                            Approval</button>
                    </form>
                @endif

                @if($jobCost->status === 'pending' && auth()->user()->isOwner())
                    <form action="{{ route('job-costs.approve', $jobCost) }}" method="POST"
                        onsubmit="return confirm('Setujui Job Cost ini? Stok akan dikurangi.')">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2 text-sm bg-green-600 hover:bg-green-700 text-white rounded-lg">Approve</button>
                    </form>
                    <form action="{{ route('job-costs.reject', $jobCost) }}" method="POST"
                        onsubmit="return confirm('Tolak Job Cost ini?')">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2 text-sm bg-red-600 hover:bg-red-700 text-white rounded-lg">Reject</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>