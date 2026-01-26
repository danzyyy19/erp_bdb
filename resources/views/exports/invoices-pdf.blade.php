<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Daftar Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
        }

        h1 {
            text-align: center;
            font-size: 16px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 5px 8px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 20px;
            font-size: 9px;
            text-align: right;
            color: #666;
        }
    </style>
</head>

<body>
    <h1>Daftar Invoice</h1>
    <p style="text-align: center; margin-bottom: 15px;">Dicetak: {{ now()->format('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>No. Invoice</th>
                <th>Tanggal</th>
                <th>Jatuh Tempo</th>
                <th>Customer</th>
                <th class="text-right">Total</th>
                <th class="text-right">Terbayar</th>
                <th class="text-right">Sisa</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @php
                $statusLabels = [
                    'draft' => 'Draft',
                    'pending_approval' => 'Pending Approval',
                    'sent' => 'Terkirim',
                    'partial' => 'Sebagian',
                    'paid' => 'Lunas',
                    'cancelled' => 'Dibatalkan',
                ];
                $totalAmount = 0;
                $totalPaid = 0;
            @endphp
            @foreach($invoices as $i => $inv)
                @php
                    $totalAmount += $inv->total_amount ?? 0;
                    $totalPaid += $inv->paid_amount ?? 0;
                @endphp
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $inv->invoice_number }}</td>
                    <td>{{ $inv->invoice_date ? $inv->invoice_date->format('d/m/Y') : '-' }}</td>
                    <td>{{ $inv->due_date ? $inv->due_date->format('d/m/Y') : '-' }}</td>
                    <td>{{ $inv->customer->name ?? '-' }}</td>
                    <td class="text-right">Rp {{ number_format($inv->total_amount ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($inv->paid_amount ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right">Rp
                        {{ number_format(($inv->total_amount ?? 0) - ($inv->paid_amount ?? 0), 0, ',', '.') }}</td>
                    <td>{{ $statusLabels[$inv->status] ?? $inv->status }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" class="text-right">Total:</th>
                <th class="text-right">Rp {{ number_format($totalAmount, 0, ',', '.') }}</th>
                <th class="text-right">Rp {{ number_format($totalPaid, 0, ',', '.') }}</th>
                <th class="text-right">Rp {{ number_format($totalAmount - $totalPaid, 0, ',', '.') }}</th>
                <th></th>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Total: {{ $invoices->count() }} invoice
    </div>
</body>

</html>