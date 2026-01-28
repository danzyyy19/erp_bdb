<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $purchase->purchase_number }} - Purchase Order</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm;
            /* More margin from edge */
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.2;
            /* Balanced tight spacing */
            background: white;
            color: #000;
        }

        .container {
            width: 100%;
            max-width: 210mm;
            margin: 0 auto;
            border: 2px solid #000;
            padding: 15px;
            /* Internal spacing from border */
            /* height: 98vh; removed default height to avoid overflow issues */
            /* Removed min-height so border fits content */
            position: relative;
        }

        /* HEADER */
        .header {
            display: flex;
            align-items: center;
            /* Center logo with text */
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
            gap: 20px;
            /* Gap between logo and text */
        }

        /* ... logo ... */
        .logo {
            width: 80px;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .logo img {
            width: 100%;
            height: auto;
        }

        .company-info {
            flex: 1;
        }

        .company-name {
            font-size: 20px;
            /* Larger */
            font-weight: 900;
            /* Extra Bold */
            color: #c00;
            font-family: "Times New Roman", Times, serif;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .company-sub {
            font-style: italic;
            color: #c00;
            font-size: 11px;
            margin-bottom: 3px;
            font-weight: bold;
        }

        .company-address {
            font-size: 10px;
            color: #000;
            line-height: 1.2;
        }

        /* ... company text ... */

        /* PO TITLE */
        .po-title-section {
            text-align: center;
            margin: 20px 0 30px 0;
        }

        .po-title {
            font-size: 18px;
            font-weight: 900;
            /* Extra Bold */
            text-transform: uppercase;
            text-decoration: underline;
            text-decoration-style: double;
            border-bottom: 4px double #000;
            /* Thicker double line */
            display: inline-block;
            padding-bottom: 3px;
            font-family: "Times New Roman", Times, serif;
            /* Match style */
        }

        /* INFO GRID */
        .info-grid {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            font-size: 11px;
            /* Slightly larger for readability */
        }

        .info-left,
        .info-right {
            width: 48%;
        }

        .info-row {
            display: grid;
            grid-template-columns: 100px 15px 1fr;
            /* Fixed widths: Label | : | Value */
            margin-bottom: 4px;
            align-items: flex-start;
        }

        .info-row.one-line {
            grid-template-columns: 1fr;
        }

        .info-row.full {
            grid-template-columns: 1fr;
        }

        .label {
            font-weight: bold;
            white-space: nowrap;
        }

        .colon {
            text-align: center;
            font-weight: bold;
        }

        .value {
            /* flex: 1 managed by grid */
            font-weight: normal;
        }

        .value-address {
            white-space: pre-line;
            line-height: 1.2;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid #000;
            margin-bottom: 15px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            font-size: 10px;
        }

        th {
            background-color: #f0f0f0;
            /* Light gray header optional */
            background-color: transparent;
            font-weight: bold;
            text-align: center;
        }

        .col-no {
            width: 30px;
            text-align: center;
        }

        .col-item {}

        .col-qty {
            width: 60px;
            text-align: center;
        }

        .col-unit {
            width: 60px;
            text-align: center;
        }

        .col-price {
            width: 180px;
            text-align: center;
        }

        .col-total {
            width: 100px;
            text-align: right;
        }

        /* FOOTER SUMMARY & TERBILANG */
        .footer-section {
            display: flex;
            justify-content: space-between;
            border: 2px solid #000;
            padding: 0;
            margin-bottom: 20px;
        }

        .terbilang-box {
            flex: 1;
            padding: 5px;
            border-right: 2px solid #000;
            font-style: italic;
            font-weight: bold;
            font-size: 10px;
            display: flex;
            align-items: center;
        }

        .summary-box {
            width: 300px;
        }

        .summary-row {
            display: flex;
            border-bottom: 1px solid #000;
        }

        .summary-row:last-child {
            border-bottom: none;
        }

        .summary-label {
            flex: 1;
            padding: 3px 5px;
            font-weight: bold;
            border-right: 1px solid #000;
        }

        .summary-val {
            width: 100px;
            padding: 3px 5px;
            text-align: right;
        }

        /* SIGNATURE */
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            padding: 0 40px;
        }

        .sig-block {
            text-align: center;
            width: 150px;
        }

        .sig-title {
            font-weight: bold;
            margin-bottom: 50px;
        }

        .sig-name {
            font-weight: bold;
            /* text-decoration: underline; */
        }

        .print-buttons {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 999;
        }

        @media print {
            .print-buttons {
                display: none;
            }

            @page {
                margin: 0;
            }

            body {
                margin: 10mm;
            }
        }
    </style>
</head>

<body>

    <!-- PRINT BUTTONS -->
    <div class="print-buttons">
        <button onclick="window.print()"
            style="padding: 10px 20px; background: #c00; color: white; border: none; cursor: pointer;">Print</button>
    </div>

    <div class="container">
        <!-- HEADER -->
        <div class="header">
            <div class="logo">
                <!-- Replace with actual logo path -->
                <img src="{{ asset('images/logo-bdb.png') }}" alt="LOGO">
            </div>
            <div class="company-info">
                <div class="company-name">CV. BERKAH DOA BUNDA</div>
                <div class="company-sub">Epoxy Coating Specialist, Manufactur and Trading Chemical</div>
                <div class="company-address">
                    Jl. Diklat Pemda RT.001/RW.015 Sukabakti - Curug - Tangerang<br>
                    Phone: 0852-1271-4789 Email: cv.berkahdoabunda@gmail.com
                </div>
            </div>
        </div>

        <!-- TITLE -->
        <div class="po-title-section">
            <span class="po-title">PURCHASE ORDER</span>
        </div>

        <!-- INFO -->
        <div class="info-grid">
            <div class="info-left">
                <div class="info-row full">
                    <!-- Special case for date if needed, or keep standard -->
                    <div style="font-weight: bold;">TANGERANG, {{ $purchase->purchase_date->translatedFormat('d F Y') }}
                    </div>
                </div>

                <div style="margin-top: 5px;"></div>

                <div class="info-row">
                    <div class="label">KEPADA</div>
                    <div class="colon">:</div>
                    <div class="value">{{ $purchase->supplier->name }}</div>
                </div>
                <div class="info-row">
                    <div class="label"></div>
                    <div class="colon"></div>
                    <div class="value">{{ $purchase->supplier->contact_name ?? '' }}</div>
                </div>

                <div style="margin-top: 5px;"></div>

                <div class="info-row">
                    <div class="label">EMAIL</div>
                    <div class="colon">:</div>
                    <div class="value">{{ $purchase->supplier->email ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="label">FAX</div>
                    <div class="colon">:</div>
                    <div class="value">{{ $purchase->supplier->fax ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="label">PAYMENT</div>
                    <div class="colon">:</div>
                    <div class="value">{{ $purchase->payment_terms ?? '-' }}</div>
                </div>
            </div>

            <div class="info-right">
                <div class="info-row">
                    <div class="label">NO. PO</div>
                    <div class="colon">:</div>
                    <div class="value">{{ $purchase->purchase_number }}</div>
                </div>
                <div class="info-row">
                    <div class="label">TO</div>
                    <div class="colon">:</div>
                    <div class="value">CV. BERKAH DOA BUNDA</div>
                </div>
                <div class="info-row">
                    <div class="label"></div>
                    <div class="colon"></div>
                    <div class="value">BpK Mulyadi [0852-1271-4789]</div>
                </div>
                <div class="info-row">
                    <div class="label">NPWP</div>
                    <div class="colon">:</div>
                    <div class="value">93.735.766.3-452.000</div>
                </div>
                <div class="info-row">
                    <div class="label">EMAIL</div>
                    <div class="colon">:</div>
                    <div class="value">cv.berkahdoabunda@gmail.com</div>
                </div>
                <div class="info-row">
                    <div class="label">DELIVERY</div>
                    <div class="colon">:</div>
                    <div class="value-address">Jl. Diklat Pemda Kp. Badodon RT001/RW.015 NO.06/08 Curug</div>
                </div>
            </div>
        </div>

        <!-- TABLE -->
        <table>
            <thead>
                <tr>
                    <th rowspan="2" class="col-no">NO</th>
                    <th rowspan="2" class="col-item">NAMA BARANG</th>
                    <th rowspan="2" class="col-qty">QTY</th>
                    <th rowspan="2" class="col-unit">SATUAN</th>
                    <th colspan="3" class="col-price">UNIT PRICE</th>
                    <th rowspan="2" class="col-total">TOTAL</th>
                </tr>
                <tr>
                    <th style="font-size: 9px; width: 40px;">$</th>
                    <th style="font-size: 9px; width: 60px;">KURS</th>
                    <th style="font-size: 9px; width: 80px;">IDR/Kg</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchase->items as $index => $item)
                    <tr>
                        <td class="col-no text-center">{{ $index + 1 }}</td>
                        <td class="col-item">{{ $item->product->name }}</td>
                        <td class="col-qty text-center">{{ number_format($item->quantity + 0, 0, ',', '.') }}</td>
                        <td class="col-unit text-center">{{ $item->unit ?? $item->product->unit ?? '-' }}</td>
                        <td class="text-center">{{ $item->unit_price_usd ? number_format($item->unit_price_usd, 2) : '-' }}
                        </td>
                        <td class="text-center">
                            {{ $item->conversion_rate ? 'Rp ' . number_format($item->conversion_rate, 0, ',', '.') : '-' }}
                        </td>
                        <td class="text-right">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                        <td class="col-total text-right">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- FOOTER: TERBILANG & SUMMARY -->
        <div class="footer-section">
            <div class="terbilang-box">
                TERBILANG : # {{ ucwords($terbilang) }} Rupiah #
            </div>
            <div class="summary-box">
                <div class="summary-row">
                    <div class="summary-label">SUB TOTAL</div>
                    <div class="summary-val">Rp {{ number_format($purchase->subtotal, 0, ',', '.') }}</div>
                </div>
                <div class="summary-row">
                    <div class="summary-label">DISCOUNT</div>
                    <div class="summary-val">Rp {{ number_format($purchase->discount, 0, ',', '.') }}</div>
                </div>
                {{-- Down Payment row not in DB, skipping or showing 0 --}}

                <div class="summary-row">
                    <div class="summary-label">DPP</div>
                    <div class="summary-val">Rp
                        {{ number_format($purchase->subtotal - $purchase->discount, 0, ',', '.') }}
                    </div>
                </div>
                <div class="summary-row">
                    <div class="summary-label">PPN {{ $purchase->tax_percentage + 0 }}%</div>
                    <div class="summary-val">Rp {{ number_format($purchase->tax, 0, ',', '.') }}</div>
                </div>
                <div class="summary-row" style="border-top: 1px solid #000;">
                    <div class="summary-label" style="font-weight: bold;">TOTAL AMOUNT</div>
                    <div class="summary-val" style="font-weight: bold;">Rp
                        {{ number_format($purchase->total_amount, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- SIGNATURE -->
        <div class="signature-section">
            <div class="sig-block">
                <div class="sig-title">Penerima PO</div>
                <br><br><br>
                <div class="sig-name">(
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    )</div>
            </div>
            <div class="sig-block">
                <div class="sig-title">Dibuat Oleh,</div>
                <br><br><br>
                <div class="sig-name">{{ strtoupper($purchase->creator->name ?? 'ADMIN') }}</div>
            </div>
        </div>

    </div>

</body>

</html>