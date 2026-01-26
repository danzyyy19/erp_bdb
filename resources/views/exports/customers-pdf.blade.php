<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Daftar Customer</title>
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
    <h1>Daftar Customer</h1>
    <p style="text-align: center; margin-bottom: 15px;">Dicetak: {{ now()->format('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Alamat</th>
                <th class="text-center">Total Transaksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $i => $customer)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $customer->code ?? '-' }}</td>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->email ?? '-' }}</td>
                    <td>{{ $customer->phone ?? '-' }}</td>
                    <td>{{ Str::limit($customer->address, 50) ?? '-' }}</td>
                    <td class="text-center">{{ $customer->invoices()->count() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Total: {{ $customers->count() }} customer
    </div>
</body>

</html>