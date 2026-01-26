<?php

namespace App\Exports;

use App\Models\StockMovement;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StockMovementsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = StockMovement::with(['product', 'user']);

        if (!empty($this->filters['product_id'])) {
            $query->where('product_id', $this->filters['product_id']);
        }

        if (!empty($this->filters['type'])) {
            $query->where('type', $this->filters['type']);
        }

        if (!empty($this->filters['date_from'])) {
            $query->whereDate('created_at', '>=', $this->filters['date_from']);
        }

        if (!empty($this->filters['date_to'])) {
            $query->whereDate('created_at', '<=', $this->filters['date_to']);
        }

        if (!empty($this->filters['category_type'])) {
            $query->whereHas(
                'product',
                fn($q) =>
                $q->whereHas('category', fn($c) => $c->where('type', $this->filters['category_type']))
            );
        }

        return $query->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Kode Produk',
            'Nama Produk',
            'Tipe',
            'Jumlah',
            'Stok Sebelum',
            'Stok Sesudah',
            'Referensi',
            'Catatan',
            'User',
        ];
    }

    public function map($movement): array
    {
        $typeLabels = [
            'in' => 'Masuk',
            'out' => 'Keluar',
            'adjustment' => 'Penyesuaian',
        ];

        return [
            $movement->created_at->format('d/m/Y H:i'),
            $movement->product->code ?? '-',
            $movement->product->name ?? '-',
            $typeLabels[$movement->type] ?? $movement->type,
            number_format($movement->quantity, 2),
            number_format($movement->stock_before, 2),
            number_format($movement->stock_after, 2),
            $movement->reference_type ?? '-',
            $movement->notes ?? '-',
            $movement->user->name ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
