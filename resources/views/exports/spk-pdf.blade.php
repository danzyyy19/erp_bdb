<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Daftar SPK</title>
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
    <h1>Daftar Surat Perintah Kerja (SPK)</h1>
    <p style="text-align: center; margin-bottom: 15px;">Dicetak: {{ now()->format('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>No. SPK</th>
                <th>Tanggal</th>
                <th>Tipe</th>
                <th>Status</th>
                <th>Target</th>
                <th>Selesai</th>
                <th>Dibuat</th>
                <th>Disetujui</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @php
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
            @endphp
            @foreach($spks as $i => $spk)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $spk->spk_number }}</td>
                    <td>{{ $spk->created_at->format('d/m/Y') }}</td>
                    <td>{{ $typeLabels[$spk->type] ?? $spk->type ?? '-' }}</td>
                    <td>{{ $statusLabels[$spk->status] ?? $spk->status }}</td>
                    <td>{{ $spk->target_date ? $spk->target_date->format('d/m/Y') : '-' }}</td>
                    <td>{{ $spk->completed_at ? $spk->completed_at->format('d/m/Y') : '-' }}</td>
                    <td>{{ $spk->creator->name ?? '-' }}</td>
                    <td>{{ $spk->approver->name ?? '-' }}</td>
                    <td>{{ Str::limit($spk->notes, 50) ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Total: {{ $spks->count() }} SPK
    </div>
</body>

</html>