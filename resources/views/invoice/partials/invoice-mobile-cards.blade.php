@forelse($invoices as $invoice)
    <div class="bg-white dark:bg-zinc-900 p-4 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-800">
        <div class="flex justify-between items-start mb-3">
            <div>
                <a href="{{ route('invoice.show', $invoice) }}"
                    class="font-bold text-blue-600 dark:text-blue-400 font-mono">
                    {{ $invoice->invoice_number }}
                </a>
                <div class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                    {{ $invoice->invoice_date->format('d M Y') }}
                </div>
            </div>
            <span
                class="px-2 py-1 text-xs rounded-full bg-{{ $invoice->status_color }}-100 dark:bg-{{ $invoice->status_color }}-900/30 text-{{ $invoice->status_color }}-700 dark:text-{{ $invoice->status_color }}-400">
                {{ $invoice->status_label }}
            </span>
        </div>

        <div class="space-y-2 text-sm">
            <div class="flex justify-between">
                <span class="text-zinc-500 dark:text-zinc-400">Customer:</span>
                <span class="font-medium text-zinc-900 dark:text-white">{{ $invoice->customer?->name ?? 'Walk-in' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-zinc-500 dark:text-zinc-400">Total:</span>
                <span class="font-bold text-zinc-900 dark:text-white">Rp
                    {{ number_format($invoice->total, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="mt-4 pt-3 border-t border-zinc-100 dark:border-zinc-800 flex justify-end gap-2">
            <a href="{{ route('invoice.print', $invoice) }}" target="_blank"
                class="px-3 py-1.5 text-xs bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 rounded-lg transition-colors">
                Print
            </a>
            <a href="{{ route('invoice.show', $invoice) }}"
                class="px-3 py-1.5 text-xs bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/20 dark:hover:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-lg transition-colors">
                Detail
            </a>
        </div>
    </div>
@empty
    <div
        class="text-center p-8 text-zinc-500 dark:text-zinc-400 bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800">
        <div class="flex flex-col items-center gap-2">
            <i data-lucide="file-x" class="w-8 h-8 text-zinc-300"></i>
            <span>Tidak ada invoice</span>
        </div>
    </div>
@endforelse