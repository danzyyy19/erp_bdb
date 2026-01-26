<x-app-layout>
    @section('title', 'Detail Customer')

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $customer->name }}</h2>
                <p class="text-zinc-500 dark:text-zinc-400 mt-1">Detail Customer</p>
            </div>
            <a href="{{ route('customers.edit', $customer) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 rounded-lg font-medium transition-colors">
                <i data-lucide="edit" class="w-4 h-4"></i>
                <span>Edit</span>
            </a>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6">
            <h3 class="font-semibold text-zinc-900 dark:text-white mb-4">Informasi</h3>
            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-zinc-500 dark:text-zinc-400">Email</p>
                    <p class="font-medium text-zinc-900 dark:text-white">{{ $customer->email ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-zinc-500 dark:text-zinc-400">Telepon</p>
                    <p class="font-medium text-zinc-900 dark:text-white">{{ $customer->phone ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-zinc-500 dark:text-zinc-400">Alamat</p>
                    <p class="font-medium text-zinc-900 dark:text-white">{{ $customer->address ?? '-' }}</p>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800">
            <div class="p-5 border-b border-zinc-200 dark:border-zinc-800">
                <h3 class="font-semibold text-zinc-900 dark:text-white">Invoice Terbaru</h3>
            </div>
            <div class="divide-y divide-zinc-200 dark:divide-zinc-800">
                @forelse($customer->invoices as $invoice)
                    <div class="p-4 flex items-center justify-between">
                        <div>
                            <a href="{{ route('invoice.show', $invoice) }}"
                                class="font-medium text-blue-600 dark:text-blue-400 hover:underline">{{ $invoice->invoice_number }}</a>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">
                                {{ $invoice->invoice_date->format('d M Y') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="font-medium text-zinc-900 dark:text-white">Rp
                                {{ number_format($invoice->total, 0, ',', '.') }}
                            </p>
                            <span
                                class="text-xs px-2 py-0.5 rounded-full bg-{{ $invoice->status_color }}-100 dark:bg-{{ $invoice->status_color }}-900/30 text-{{ $invoice->status_color }}-700 dark:text-{{ $invoice->status_color }}-400">{{ $invoice->status_label }}</span>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-zinc-500">Belum ada invoice</div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>