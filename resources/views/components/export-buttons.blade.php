@props([
    'excelRoute' => null,
    'pdfRoute' => null,
    'excelParams' => [],
    'pdfParams' => [],
])

<div class="flex items-center gap-2">
    @if($excelRoute)
        <a href="{{ route($excelRoute, $excelParams) }}" 
           class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-lg bg-green-600 hover:bg-green-700 text-white transition-colors">
            <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
            <span>Excel</span>
        </a>
    @endif
    
    @if($pdfRoute)
        <a href="{{ route($pdfRoute, $pdfParams) }}" 
           class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-lg bg-red-600 hover:bg-red-700 text-white transition-colors">
            <i data-lucide="file-text" class="w-4 h-4"></i>
            <span>PDF</span>
        </a>
    @endif
</div>
