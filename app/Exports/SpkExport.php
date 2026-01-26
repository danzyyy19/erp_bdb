<?php

namespace App\Exports;

use App\Models\Spk;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SpkExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Spk::with(['creator', 'approver']);

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (!empty($this->filters['date_from'])) {
            $query->whereDate('created_at', '>=', $this->filters['date_from']);
        }

        if (!empty($this->filters['date_to'])) {
            $query->whereDate('created_at', '<=', $this->filters['date_to']);
        }

        return $query->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'No. SPK',
            'Tanggal',
            'Tipe',
            'Status',
            'Target Selesai',
            'Selesai',
            'Dibuat Oleh',
            'Disetujui Oleh',
            'Catatan',
        ];
    }

    public function map($spk): array
    {
        $statusLabels = [
            'pending' => 'Pending',
            'approved' => 'Disetujui',
            'in_progress' => 'Proses',
            'completed' => 'Selesai',
            'rejected' => 'Ditolak',
        ];

        $typeLabels = [
            'base' => 'Base',
            'finishgood' => 'Finish Good',
        ];

        return [
            $spk->spk_number,
            $spk->created_at->format('d/m/Y'),
            $typeLabels[$spk->type] ?? $spk->type ?? '-',
            $statusLabels[$spk->status] ?? $spk->status,
            $spk->target_date ? $spk->target_date->format('d/m/Y') : '-',
            $spk->completed_at ? $spk->completed_at->format('d/m/Y') : '-',
            $spk->creator->name ?? '-',
            $spk->approver->name ?? '-',
            $spk->notes ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
