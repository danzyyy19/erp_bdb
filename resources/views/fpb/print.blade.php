<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print FPB - {{ $fpb->fpb_number }}</title>
    <style>
        @page {
            size: 24cm 13cm landscape;
            margin: 8mm 15mm 3mm 15mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
            line-height: 1.3;
            padding: 0;
            width: 17cm;
            max-width: 17cm;
            margin: 0 auto;
        }

        /* KOP SURAT */
        .kop-surat {
            display: flex;
            align-items: center;
            border-bottom: 2px solid #000;
            padding-bottom: 3px;
            margin-bottom: 4px;
        }

        .logo {
            width: 80px;
            height: 80px;
            margin-right: 12px;
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .company-info {
            flex: 1;
            text-align: center;
        }

        .company-name {
            font-size: 14pt;
            font-weight: bold;
        }

        .company-tagline {
            font-size: 9pt;
        }

        .fpb-number {
            text-align: right;
            font-size: 12pt;
            font-weight: bold;
            min-width: 120px;
        }

        /* DOCUMENT TITLE */
        .document-title {
            text-align: center;
            margin: 4px 0;
            padding: 2px;
            border: 1px solid #000;
            font-size: 12pt;
            font-weight: bold;
        }

        /* INFO GRID */
        .info-container {
            display: flex;
            gap: 6px;
            margin-bottom: 5px;
        }

        .info-box {
            flex: 1;
            border: 1px solid #000;
            padding: 3px 5px;
            font-size: 11pt;
        }

        .info-box-title {
            font-weight: bold;
            font-size: 11pt;
            border-bottom: 1px solid #000;
            margin-bottom: 2px;
            padding-bottom: 1px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            border: none;
            padding: 1px 0;
            vertical-align: top;
            font-size: 11pt;
        }

        .info-table .label {
            width: 70px;
            font-weight: bold;
            white-space: nowrap;
        }

        .info-table .colon {
            width: 10px;
            text-align: center;
        }

        /* SECTION TITLE */
        .section-title {
            font-size: 11pt;
            font-weight: bold;
            padding: 3px 6px;
            margin-bottom: 3px;
            margin-top: 5px;
            border: 1px solid #000;
        }

        /* TABLES */
        .section {
            margin-bottom: 5px;
        }

        table.main-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11pt;
        }

        table.main-table th,
        table.main-table td {
            border: 1px solid #000;
            padding: 3px 4px;
            text-align: left;
        }

        table.main-table th {
            font-weight: bold;
            font-size: 11pt;
        }

        table.main-table td.center,
        table.main-table th.center {
            text-align: center;
        }

        table.main-table td.right {
            text-align: right;
        }

        /* SIGNATURE BOXES */
        .signatures-row {
            display: flex;
            gap: 8px;
            margin-top: 6px;
        }

        .signature-box {
            flex: 1;
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
            font-size: 10pt;
            min-height: 55px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .signature-box .title {
            font-weight: bold;
            font-size: 10pt;
            margin-bottom: 2px;
        }

        .signature-box .space {
            flex: 1;
            min-height: 30px;
        }

        .signature-box .line {
            border-top: 1px solid #000;
            padding-top: 2px;
            font-size: 10pt;
        }

        /* FOOTER */
        .footer {
            text-align: center;
            font-size: 8pt;
            margin-top: 3px;
            border-top: 1px solid #000;
            padding-top: 2px;
        }

        /* NO PRINT */
        .no-print {
            margin-bottom: 10px;
            padding: 8px;
            background: #f5f5f5;
            border-radius: 5px;
        }

        .no-print button {
            padding: 6px 14px;
            font-size: 11px;
            cursor: pointer;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-right: 6px;
        }

        @media print {
            body {
                padding: 0;
                width: 100%;
                max-width: 100%;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body>
    <div class="no-print">
        <button onclick="window.print()">🖨️ Print FPB</button>
        <button onclick="window.close()">✕ Tutup</button>
        <span style="font-size: 10px; color: #666; margin-left: 8px;">Format: 24 x 13 cm (Jenis Kertas Letter)</span>
    </div>

    <!-- KOP SURAT -->
    <div class="kop-surat">
        <div class="logo">
            <img src="{{ asset('images/logo-bdb.png') }}" alt="Logo">
        </div>
        <div class="company-info">
            <div class="company-name">CV BERKAH DOA BUNDA</div>
            <div class="company-tagline">Email: cv.berkahdoabunda@gmail.com</div>
            <div class="company-tagline">Telp: 0852-1271-4789 / 0822-1379-6385</div>
            <div class="company-tagline">Office: Ruko Kristal Aryana Curug | Gudang: Jl. Diklat Pemda No.06-08 Curug
                Tangerang</div>
        </div>
        <div class="fpb-number">
            <strong>{{ $fpb->fpb_number }}</strong><br>
            {{ $fpb->request_date->format('d/m/Y') }}
        </div>
    </div>

    <!-- DOCUMENT TITLE -->
    <div class="document-title">FORM PERMINTAAN BARANG (FPB)</div>

    <!-- INFO BOXES -->
    <div class="info-container">
        <div class="info-box">
            <div class="info-box-title">SPK Produksi</div>
            <table class="info-table">
                <tr>
                    <td class="label">No. SPK</td>
                    <td class="colon">:</td>
                    <td>{{ $fpb->spk->spk_number ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Tgl Perm.</td>
                    <td class="colon">:</td>
                    <td>{{ $fpb->request_date->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td class="label">Status</td>
                    <td class="colon">:</td>
                    <td>{{ $fpb->status_label }}</td>
                </tr>
            </table>
        </div>
        <div class="info-box">
            <div class="info-box-title">Catatan</div>
            <div>{{ $fpb->notes ?: '-' }}</div>
        </div>
    </div>

    <!-- MATERIAL YANG DIMINTA -->
    <div class="section-title">Material Yang Diminta</div>
    <div class="section">
        <table class="main-table">
            <thead>
                <tr>
                    <th style="width: 25px;" class="center">No</th>
                    <th style="width: 100px;" class="center">Kode</th>
                    <th>Nama Material</th>
                    <th style="width: 60px;" class="center">Qty Diminta</th>
                    <th style="width: 45px;" class="center">Satuan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($fpb->items as $index => $item)
                    <tr>
                        <td class="center">{{ $index + 1 }}</td>
                        <td class="center">{{ $item->product->code ?? '-' }}</td>
                        <td>{{ $item->product->name ?? '-' }}</td>
                        <td class="right">{{ number_format($item->quantity_requested, 2) }}</td>
                        <td class="center">{{ $item->unit }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- SIGNATURES - HORIZONTAL AT BOTTOM -->
    <div class="signatures-row">
        <div class="signature-box">
            <div class="title">Diminta Oleh</div>
            <div class="space"></div>
            <div class="line">{{ $fpb->creator->name ?? '........................' }}</div>
        </div>
        <div class="signature-box">
            <div class="title">Disetujui Oleh</div>
            <div class="space"></div>
            <div class="line">{{ $fpb->approver->name ?? '........................' }}</div>
        </div>
        <div class="signature-box">
            <div class="title">Diserahkan Oleh</div>
            <div class="space"></div>
            <div class="line">........................</div>
        </div>
    </div>

    <div class="footer">
        CV Berkah Doa Bunda • Dicetak: {{ now()->format('d/m/Y H:i') }}
    </div>
</body>

</html>