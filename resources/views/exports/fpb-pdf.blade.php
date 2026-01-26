<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Daftar FPB</title>
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
    <h1>Daftar Form Permintaan Barang (FPB)</h1>
    <p style="text-align: center; margin-bottom: 15px;">Dicetak: {{ now()->format('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>No. FPB</th>
                <th>Tanggal</th>
                <th>No. SPK</th>
                <th>Status</th>
                <th>Dibuat Oleh</th>
                <th>Disetujui Oleh</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $statusLabels = [
                    'pending' => 'Pending',
                    'approved' => 'Disetujui',
                    'rejected' => 'Ditolak',
                    'completed' => 'Selesai',
                ];
            @endphp
            @foreach($fpbs as $i => $fpb)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $fpb->fpb_number }}</td>
                    <td>{{ $fpb->created_at->format('d/m/Y') }}</td>
                    <td>{{ $fpb->spk->spk_number ?? '-' }}</td>
                    <td>{{ $statusLabels[$fpb->status] ?? $fpb->status }}</td>
                    <td>{{ $fpb->creator->name ?? '-' }}</td>
                    <td>{{ $fpb->approver->name ?? '-' }}</td>
                    <td>{{ Str::limit($fpb->notes, 50) ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Total: {{ $fpbs->count() }} FPB
    </div>
</body>

</html>