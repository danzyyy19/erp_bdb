<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Riwayat Stok</title>
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

        .badge-in {
            background: #dcfce7;
            color: #166534;
            padding: 2px 6px;
            border-radius: 4px;
        }

        .badge-out {
            background: #fee2e2;
            color: #991b1b;
            padding: 2px 6px;
            border-radius: 4px;
        }

        .badge-adj {
            background: #fef3c7;
            color: #92400e;
            padding: 2px 6px;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <h1>Riwayat Pergerakan Stok</h1>
    <p style="text-align: center; margin-bottom: 15px;">Dicetak: {{ now()->format('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Tanggal</th>
                <th>Kode</th>
                <th>Produk</th>
                <th>Tipe</th>
                <th class="text-right">Jumlah</th>
                <th class="text-right">Sebelum</th>
                <th class="text-right">Sesudah</th>
                <th>Referensi</th>
                <th>Catatan</th>
                <th>User</th>
            </tr>
        </thead>
        <tbody>
            @foreach($movements as $i => $m)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $m->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $m->product->code ?? '-' }}</td>
                    <td>{{ $m->product->name ?? '-' }}</td>
                    <td>
                        @if($m->type === 'in')
                            <span class="badge-in">Masuk</span>
                        @elseif($m->type === 'out')
                            <span class="badge-out">Keluar</span>
                        @else
                            <span class="badge-adj">Penyesuaian</span>
                        @endif
                    </td>
                    <td class="text-right">{{ number_format($m->quantity, 2) }}</td>
                    <td class="text-right">{{ number_format($m->stock_before, 2) }}</td>
                    <td class="text-right">{{ number_format($m->stock_after, 2) }}</td>
                    <td>{{ $m->reference_type ?? '-' }}</td>
                    <td>{{ Str::limit($m->notes, 30) ?? '-' }}</td>
                    <td>{{ $m->user->name ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Total: {{ $movements->count() }} pergerakan
    </div>
</body>

</html>