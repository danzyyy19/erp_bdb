<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $purchase->purchase_number }} - Purchase Order</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 10mm 10mm 10mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            background: #f0f0f0;
        }

        .print-container {
            background: white;
            max-width: 210mm;
            margin: 0 auto;
            padding: 10mm;
        }

        /* HEADER */
        .header {
            display: flex;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .logo-section {
            width: 80px;
        }

        .logo-section img {
            width: 70px;
            height: auto;
        }

        .company-section {
            flex: 1;
        }

        .company-name {
            font-size: 22px;
            font-weight: bold;
            color: #c41e3a;
            font-family: 'Times New Roman', serif;
        }

        .company-tagline {
            font-size: 10px;
            font-style: italic;
            color: #333;
        }

        .company-address {
            font-size: 9px;
            color: #333;
            margin-top: 3px;
        }

        .po-title {
            text-align: right;
            font-size: 28px;
            font-weight: bold;
            color: #c41e3a;
            margin-top: 10px;
        }

        /* INFO SECTION */
        .info-section {
            display: flex;
            gap: 20px;
            margin: 15px 0;
            font-size: 10px;
        }

        .info-left {
            flex: 1;
        }

        .info-right {
            width: 250px;
            border: 1px solid #000;
        }

        .info-row {
            display: flex;
            margin-bottom: 3px;
        }

        .info-label {
            width: 80px;
            font-weight: bold;
        }

        .info-value {
            flex: 1;
        }

        .info-right-row {
            display: flex;
            border-bottom: 1px solid #ddd;
            padding: 4px 8px;
        }

        .info-right-row:last-child {
            border-bottom: none;
        }

        .info-right-label {
            width: 110px;
            font-weight: bold;
        }

        .info-right-value {
            flex: 1;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        table th {
            background: transparent;
            border: 1px solid #000;
            padding: 8px 5px;
            font-size: 10px;
            font-weight: bold;
            text-align: center;
        }

        table td {
            border: 1px solid #000;
            padding: 6px 5px;
            font-size: 10px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        /* SUMMARY */
        .summary-section {
            width: 280px;
            margin-left: auto;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            font-size: 10px;
            border-bottom: 1px solid #ddd;
        }

        .summary-row.total {
            font-weight: bold;
            font-size: 12px;
            border-top: 2px solid #000;
            border-bottom: none;
            padding-top: 8px;
        }

        /* NOTES */
        .notes-section {
            margin: 20px 0;
            padding: 10px;
            border: 1px solid #ddd;
            font-size: 10px;
        }

        .notes-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        /* SIGNATURE */
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
        }

        .signature-box {
            text-align: center;
            width: 30%;
        }

        .signature-title {
            font-weight: bold;
            font-size: 10px;
            margin-bottom: 60px;
        }

        .signature-name {
            font-weight: bold;
            font-size: 10px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }

        /* PRINT BUTTONS */
        .print-buttons {
            text-align: center;
            padding: 15px;
            background: #f3f4f6;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        .print-buttons button {
            padding: 10px 25px;
            font-size: 13px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            margin: 0 5px;
        }

        .btn-print {
            background: #c41e3a;
            color: white;
        }

        .btn-close {
            background: #6b7280;
            color: white;
        }

        @media print {
            body {
                background: white;
            }

            .print-container {
                padding: 0;
            }

            .print-buttons {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="print-container">
        <div class="print-buttons">
            <button class="btn-print" onclick="window.print()">Print PO</button>
            <button class="btn-close" onclick="window.close()">Tutup</button>
            <span style="margin-left: 20px; color: #666;">Format: A4 Portrait</span>
        </div>

        <!-- HEADER -->
        <div class="header">
            <div class="logo-section">
                <img src="{{ asset('images/logo-bdb.png') }}" alt="Logo">
            </div>
            <div class="company-section">
                <div class="company-name">CV. BERKAH DOA BUNDA</div>
                <div class="company-tagline">Epoxy Coating Specialist, Manufactur and Trading Chemical</div>
                <div class="company-address">
                    Jl. Diklat Pemda RT.001/RW.015 No. 6 Sukabakti - Curug - Tangerang<br>
                    Phone: 0852-1271-4789<br>
                    Email: cv.berkahdoabunda@gmail.com
                </div>
            </div>
            <div class="po-title">PURCHASE ORDER</div>
        </div>

        <!-- INFO SECTION -->
        <div class="info-section">
            <div class="info-left">
                <div style="font-weight: bold; margin-bottom: 5px;">KEPADA:</div>
                <div class="info-row">
                    <span class="info-label">SUPPLIER</span>
                    <span class="info-value">: {{ $purchase->supplier?->name ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">ALAMAT</span>
                    <span class="info-value">: {{ $purchase->supplier?->address ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">TELP/HP</span>
                    <span class="info-value">: {{ $purchase->supplier?->phone ?? '-' }}</span>
                </div>
            </div>
            <div class="info-right">
                <div class="info-right-row">
                    <span class="info-right-label">PO NO.</span>
                    <span class="info-right-value">: {{ $purchase->purchase_number }}</span>
                </div>
                <div class="info-right-row">
                    <span class="info-right-label">TANGGAL</span>
                    <span class="info-right-value">: {{ $purchase->purchase_date->translatedFormat('d F Y') }}</span>
                </div>
                <div class="info-right-row">
                    <span class="info-right-label">STATUS</span>
                    <span class="info-right-value">: {{ strtoupper($purchase->status) }}</span>
                </div>
            </div>
        </div>

        <!-- ITEMS TABLE -->
        <table>
            <thead>
                <tr>
                    <th style="width: 30px;">NO.</th>
                    <th>NAMA BARANG</th>
                    <th style="width: 80px;">KODE</th>
                    <th style="width: 60px;">QTY</th>
                    <th style="width: 60px;">SATUAN</th>
                    <th style="width: 100px;">HARGA</th>
                    <th style="width: 100px;">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchase->items as $key => $item)
                    <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td>{{ $item->product->name ?? '-' }}</td>
                        <td class="text-center">{{ $item->product->code ?? '-' }}</td>
                        <td class="text-center">{{ number_format((float) $item->quantity, 2) }}</td>
                        <td class="text-center">{{ $item->product->unit ?? '-' }}</td>
                        <td class="text-right">{{ number_format((float) $item->unit_price, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format((float) $item->total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- SUMMARY -->
        <div class="summary-section">
            <div class="summary-row">
                <span>SUBTOTAL</span>
                <span>Rp {{ number_format((float) $purchase->subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="summary-row">
                <span>PPN ({{ number_format((float) $purchase->tax_percentage, 0) }}%)</span>
                <span>Rp {{ number_format((float) $purchase->tax, 0, ',', '.') }}</span>
            </div>
            @if((float) $purchase->discount > 0)
                <div class="summary-row">
                    <span>DISKON</span>
                    <span>- Rp {{ number_format((float) $purchase->discount, 0, ',', '.') }}</span>
                </div>
            @endif
            <div class="summary-row total">
                <span>GRAND TOTAL</span>
                <span>Rp {{ number_format((float) $purchase->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        @if($purchase->notes)
            <!-- NOTES -->
            <div class="notes-section">
                <div class="notes-title">CATATAN:</div>
                <div>{{ $purchase->notes }}</div>
            </div>
        @endif

        <!-- SIGNATURE -->
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-title">DIBUAT OLEH</div>
                <div class="signature-name">{{ $purchase->creator?->name ?? '-' }}</div>
            </div>
            <div class="signature-box">
                <div class="signature-title">DISETUJUI OLEH</div>
                <div class="signature-name">{{ $purchase->approver?->name ?? '-' }}</div>
            </div>
            <div class="signature-box">
                <div class="signature-title">PENERIMA PO (SUPPLIER)</div>
                <div class="signature-name">{{ $purchase->supplier?->name ?? '-' }}</div>
            </div>
        </div>
    </div>
</body>

</html>