<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
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
    <h1>{{ $title }}</h1>
    <p style="text-align: center; margin-bottom: 15px;">Dicetak: {{ now()->format('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Kode</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Spesifikasi</th>
                <th class="text-right">Stok</th>
                <th class="text-right">Min Stok</th>
                <th>Satuan</th>
                @if($showPrices)
                    <th class="text-right">Harga Beli</th>
                    <th class="text-right">Harga Jual</th>
                @endif
                <th>Supplier</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $i => $product)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $product->code }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name ?? '-' }}</td>
                    <td>{{ $product->spec_type ? ($product->spec_type === 'high_spec' ? 'High Spec' : 'Medium Spec') : '-' }}
                    </td>
                    <td class="text-right">{{ number_format($product->current_stock, 2) }}</td>
                    <td class="text-right">{{ number_format($product->min_stock, 2) }}</td>
                    <td>{{ $product->unit }}</td>
                    @if($showPrices)
                        <td class="text-right">{{ number_format($product->purchase_price ?? 0, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($product->selling_price ?? 0, 0, ',', '.') }}</td>
                    @endif
                    <td>
                        @php
                            $supplierTypes = [
                                'supplier_resmi' => 'Supplier Resmi',
                                'agen' => 'Agen',
                                'internal' => 'Internal',
                            ];
                        @endphp
                        {{ $supplierTypes[$product->supplier_type] ?? '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Total: {{ $products->count() }} produk
    </div>
</body>

</html>