<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $deliveryNote->sj_number }} - Surat Jalan</title>
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
            margin-bottom: 10px;
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

        .document-title {
            text-align: right;
            font-size: 32px;
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
            width: 280px;
        }

        .info-row {
            display: flex;
            margin-bottom: 3px;
        }

        .info-label {
            width: 110px;
            font-weight: bold;
        }

        .info-value {
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

        /* NOTES */
        .notes-section {
            margin: 15px 0;
            padding: 10px;
            border: 1px solid #000;
            font-size: 10px;
        }

        .notes-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .notes-content {
            color: #c41e3a;
        }

        /* SIGNATURE */
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
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
        }

        .signature-company {
            font-size: 9px;
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
            <button class="btn-print" onclick="window.print()">Print Surat Jalan</button>
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
                    Jl. Diklat Pemda RT.001/RW.015 Sukabakti - Curug - Tangerang<br>
                    Phone: 0852-1271-4789<br>
                    Email: cv.berkahdoabunda@gmail.com
                </div>
            </div>
            <div class="document-title">SURAT JALAN</div>
        </div>

        <!-- INFO SECTION -->
        <div class="info-section">
            <div class="info-left">
                <div style="font-weight: bold; margin-bottom: 5px;">Kepada Yth.</div>
                <div class="info-row">
                    <span class="info-label">NAMA</span>
                    <span class="info-value">: {{ $deliveryNote->customer?->name ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">ALAMAT</span>
                    <span class="info-value">:
                        {{ $deliveryNote->delivery_address ?? $deliveryNote->customer?->address ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">NO. PO</span>
                    <span class="info-value">: {{ $deliveryNote->po_number ?? '-' }}</span>
                </div>
            </div>
            <div class="info-right">
                <div class="info-row">
                    <span class="info-label">FAKTUR NO.</span>
                    <span class="info-value">: {{ $deliveryNote->invoice_number ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">TANGGAL FAKTUR</span>
                    <span class="info-value">:
                        {{ $deliveryNote->invoice_date?->translatedFormat('d F Y') ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">PAYMEN FAKTUR</span>
                    <span class="info-value">: {{ $deliveryNote->payment_method ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">SJ NO.</span>
                    <span class="info-value">: {{ $deliveryNote->sj_number }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">NO.POLISI/PENGIRIM</span>
                    <span class="info-value">: {{ $deliveryNote->vehicle_number ?? '-' }}</span>
                </div>
            </div>
        </div>

        <!-- ITEMS TABLE -->
        <table>
            <thead>
                <tr>
                    <th style="width: 30px;">NO.</th>
                    <th>NAMA BARANG</th>
                    <th style="width: 50px;">QTY</th>
                    <th style="width: 100px;">UNIT PACKING</th>
                    <th>KETERANGAN</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deliveryNote->items as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item->product?->name ?? '-' }}</td>
                        <td class="text-center">{{ number_format($item->quantity, 0) }}</td>
                        <td class="text-center">{{ $item->product?->unit_packing ?? $item->product?->unit ?? $item->unit }}
                        </td>
                        <td>{{ $item->notes ?? '' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada item</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- NOTES -->
        <div class="notes-section">
            <div class="notes-title">NOTE :</div>
            <div class="notes-content">
                1) Surat Jalan ini merupakan bukti resmi penerimaan barang dan dilengkapi dengan Invoice sebagai bukti
                penjualan<br>
                2) Penyelesaian pembayaran invoice telah diselesaikan
            </div>
        </div>

        <!-- SIGNATURE -->
        <div style="text-align: right; font-size: 10px; margin-bottom: 10px;">
            <em>(Tanda tangan dan Cap Stempel Perusahaan)</em>
            <span style="margin-left: 100px;">Tangerang,
                {{ $deliveryNote->delivery_date?->translatedFormat('d F Y') ?? now()->translatedFormat('d F Y') }}</span>
        </div>
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-title">PENERIMA</div>
                <div class="signature-name">({{ $deliveryNote->recipient_name ?? '-' }})</div>
            </div>
            <div class="signature-box">
                <div class="signature-title">HORMAT KAMI</div>
                <div class="signature-name">MULYADI</div>
                <div class="signature-company">( CV. BERKAH DOA BUNDA )</div>
            </div>
        </div>
    </div>
</body>

</html>