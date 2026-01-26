<?php

namespace App\Exports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InvoicesExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Invoice::with(['customer', 'creator']);

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (!empty($this->filters['customer_id'])) {
            $query->where('customer_id', $this->filters['customer_id']);
        }

        if (!empty($this->filters['date_from'])) {
            $query->whereDate('invoice_date', '>=', $this->filters['date_from']);
        }

        if (!empty($this->filters['date_to'])) {
            $query->whereDate('invoice_date', '<=', $this->filters['date_to']);
        }

        return $query->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'No. Invoice',
            'Tanggal',
            'Jatuh Tempo',
            'Customer',
            'Total',
            'Terbayar',
            'Sisa',
            'Status',
            'Dibuat Oleh',
        ];
    }

    public function map($invoice): array
    {
        $statusLabels = [
            'draft' => 'Draft',
            'pending_approval' => 'Pending Approval',
            'sent' => 'Terkirim',
            'partial' => 'Sebagian',
            'paid' => 'Lunas',
            'cancelled' => 'Dibatalkan',
        ];

        return [
            $invoice->invoice_number,
            $invoice->invoice_date ? $invoice->invoice_date->format('d/m/Y') : '-',
            $invoice->due_date ? $invoice->due_date->format('d/m/Y') : '-',
            $invoice->customer->name ?? '-',
            'Rp ' . number_format($invoice->total_amount ?? 0, 0, ',', '.'),
            'Rp ' . number_format($invoice->paid_amount ?? 0, 0, ',', '.'),
            'Rp ' . number_format(($invoice->total_amount ?? 0) - ($invoice->paid_amount ?? 0), 0, ',', '.'),
            $statusLabels[$invoice->status] ?? $invoice->status,
            $invoice->creator->name ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
