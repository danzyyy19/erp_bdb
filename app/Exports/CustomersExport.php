<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CustomersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return Customer::where('is_active', true)->orderBy('name')->get();
    }

    public function headings(): array
    {
        return [
            'Kode',
            'Nama',
            'Email',
            'Telepon',
            'Alamat',
            'Total Transaksi',
        ];
    }

    public function map($customer): array
    {
        return [
            $customer->code ?? '-',
            $customer->name,
            $customer->email ?? '-',
            $customer->phone ?? '-',
            $customer->address ?? '-',
            $customer->invoices()->count(),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
