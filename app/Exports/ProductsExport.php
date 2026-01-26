<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $categoryType;
    protected $showPrices;

    public function __construct(?string $categoryType = null, bool $showPrices = false)
    {
        $this->categoryType = $categoryType;
        $this->showPrices = $showPrices;
    }

    public function collection()
    {
        $query = Product::with('category')->active();

        if ($this->categoryType) {
            $query->whereHas('category', fn($q) => $q->where('type', $this->categoryType));
        }

        return $query->orderBy('code')->get();
    }

    public function headings(): array
    {
        $headers = [
            'Kode',
            'Nama Produk',
            'Kategori',
            'Spesifikasi',
            'Stok Saat Ini',
            'Stok Minimum',
            'Satuan',
        ];

        if ($this->showPrices) {
            $headers[] = 'Harga Beli';
            $headers[] = 'Harga Jual';
        }

        $headers[] = 'Supplier';

        return $headers;
    }

    public function map($product): array
    {
        $row = [
            $product->code,
            $product->name,
            $product->category->name ?? '-',
            $product->spec_type ? ($product->spec_type === 'high_spec' ? 'High Spec' : 'Medium Spec') : '-',
            number_format($product->current_stock, 2),
            number_format($product->min_stock, 2),
            $product->unit,
        ];

        if ($this->showPrices) {
            $row[] = number_format($product->purchase_price ?? 0, 0, ',', '.');
            $row[] = number_format($product->selling_price ?? 0, 0, ',', '.');
        }

        $supplierTypes = [
            'supplier_resmi' => 'Supplier Resmi',
            'agen' => 'Agen',
            'internal' => 'Internal',
        ];
        $row[] = $supplierTypes[$product->supplier_type] ?? '-';

        return $row;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
