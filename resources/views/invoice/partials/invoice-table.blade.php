@forelse($invoices as $invoice)
    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/30 transition-colors border-b border-zinc-200 dark:border-zinc-700">
        <td class="px-4 py-3 text-center border-r border-zinc-200 dark:border-zinc-700">
            <a href="{{ route('invoice.show', $invoice) }}"
                class="font-medium text-blue-600 dark:text-blue-400 hover:underline">
                {{ $invoice->invoice_number }}
            </a>
        </td>
        <td class="px-4 py-3 text-center text-zinc-700 dark:text-zinc-300 border-r border-zinc-200 dark:border-zinc-700">
            {{ $invoice->customer?->name ?? '-' }}
        </td>
        <td class="px-4 py-3 text-center text-zinc-600 dark:text-zinc-400 border-r border-zinc-200 dark:border-zinc-700">
            {{ $invoice->invoice_date->format('d M Y') }}
        </td>
        <td class="px-4 py-3 text-center text-zinc-600 dark:text-zinc-400 border-r border-zinc-200 dark:border-zinc-700">
            {{ $invoice->due_date?->format('d M Y') ?? '-' }}
        </td>
        <td
            class="px-4 py-3 text-right font-medium text-zinc-900 dark:text-white border-r border-zinc-200 dark:border-zinc-700">
            Rp {{ number_format($invoice->total, 0, ',', '.') }}
        </td>
        <td class="px-4 py-3 text-center border-r border-zinc-200 dark:border-zinc-700">
            <span
                class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-{{ $invoice->status_color }}-100 dark:bg-{{ $invoice->status_color }}-900/30 text-{{ $invoice->status_color }}-700 dark:text-{{ $invoice->status_color }}-400">
                {{ $invoice->status_label }}
            </span>
        </td>
        <td class="px-4 py-3 text-center">
            <div class="flex items-center justify-center gap-1">
                <a href="{{ route('invoice.show', $invoice) }}"
                    class="p-1.5 text-zinc-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors"
                    title="Detail">
                    <i data-lucide="eye" class="w-4 h-4"></i>
                </a>
                <a href="{{ route('invoice.print', $invoice) }}" target="_blank"
                    class="p-1.5 text-zinc-500 hover:text-zinc-900 dark:hover:text-white hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg transition-colors"
                    title="Print">
                    <i data-lucide="printer" class="w-4 h-4"></i>
                </a>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="px-4 py-12 text-center text-zinc-500">
            <div class="flex flex-col items-center gap-2">
                <i data-lucide="file-x" class="w-8 h-8 text-zinc-300"></i>
                <span>Tidak ada invoice</span>
            </div>
        </td>
    </tr>
@endforelse
