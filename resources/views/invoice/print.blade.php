<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $invoice->invoice_number }} - Invoice</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm;
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

        .invoice-title {
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
            width: 130px;
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

        /* TERBILANG */
        .terbilang-section {
            display: flex;
            gap: 15px;
            margin: 15px 0;
        }

        .terbilang-box {
            flex: 1;
        }

        .terbilang-label {
            font-weight: bold;
            font-size: 10px;
        }

        .terbilang-value {
            font-style: italic;
            font-weight: bold;
            color: #c41e3a;
            font-size: 10px;
        }

        /* SUMMARY */
        .summary-section {
            width: 300px;
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

        /* PAYMENT INFO */
        .payment-section {
            margin-top: 20px;
            font-size: 10px;
        }

        .payment-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .payment-row {
            display: flex;
            margin-bottom: 2px;
        }

        .payment-label {
            width: 80px;
        }

        .payment-note {
            margin-top: 8px;
            font-size: 9px;
        }

        /* SIGNATURE */
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .signature-box {
            text-align: center;
            width: 40%;
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
            <button class="btn-print" onclick="window.print()">Print Invoice</button>
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
            <div class="invoice-title">INVOICE</div>
        </div>

        <!-- INFO SECTION -->
        <div class="info-section">
            <div class="info-left">
                <div style="font-weight: bold; margin-bottom: 5px;">KEPADA YTH.</div>
                <div class="info-row">
                    <span class="info-label">NAMA</span>
                    <span class="info-value">: {{ $invoice->customer?->name ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">ALAMAT</span>
                    <span class="info-value">: {{ $invoice->customer?->address ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">TELP/HP</span>
                    <span class="info-value">: {{ $invoice->customer?->phone ?? '-' }}</span>
                </div>
            </div>
            <div class="info-right">
                <div class="info-right-row">
                    <span class="info-right-label">FAKTUR NO.</span>
                    <span class="info-right-value">:
                        {{ $invoice->deliveryNote?->invoice_number ?? $invoice->invoice_number }}</span>
                </div>
                <div class="info-right-row">
                    <span class="info-right-label">FAKTUR TERBIT</span>
                    <span class="info-right-value">:
                        {{ $invoice->deliveryNote?->invoice_date?->translatedFormat('d F Y') ?? $invoice->invoice_date->translatedFormat('d F Y') }}</span>
                </div>
                <div class="info-right-row">
                    <span class="info-right-label">PAYMENT FAKTUR</span>
                    <span class="info-right-value">: {{ $invoice->deliveryNote?->payment_method ?? '-' }}</span>
                </div>
                <div class="info-right-row">
                    <span class="info-right-label">JATUH TEMPO</span>
                    <span class="info-right-value">: {{ $invoice->due_date?->translatedFormat('d F Y') ?? '-' }}</span>
                </div>
                <div class="info-right-row">
                    <span class="info-right-label">SJ NO</span>
                    <span class="info-right-value">: {{ $invoice->deliveryNote?->sj_number ?? '-' }}</span>
                </div>
                <div class="info-right-row">
                    <span class="info-right-label">TANGGAL PENGIRIMAN</span>
                    <span class="info-right-value">:
                        {{ $invoice->deliveryNote?->delivery_date?->translatedFormat('d F Y') ?? '-' }}</span>
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
                    <th style="width: 100px;">HARGA</th>
                    <th style="width: 100px;">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $key => $item)
                    <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td>{{ $item->product->name }}</td>
                        <td class="text-center">{{ number_format((float) $item->quantity, 0) }}</td>
                        <td class="text-center">{{ $item->product->unit_packing ?? $item->product->unit }}</td>
                        <td class="text-right">{{ number_format((float) $item->unit_price, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format((float) $item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- TERBILANG & SUMMARY -->
        <div class="terbilang-section">
            <div class="terbilang-box">
                <div class="terbilang-label">Terbilang :</div>
                <div class="terbilang-value">#{{ ucwords(\App\Helpers\Terbilang::make((float) $invoice->total)) }}
                    RUPIAH#</div>
            </div>
            <div class="summary-section">
                <div class="summary-row">
                    <span>TOTAL HARGA (DPP)</span>
                    <span>Rp {{ number_format((float) $invoice->subtotal, 0, ',', '.') }}</span>
                </div>
                @if((float) $invoice->tax_amount > 0)
                    <div class="summary-row">
                        <span>PPN ({{ number_format((float) $invoice->tax_percent, 0) }}%)</span>
                        <span>Rp {{ number_format((float) $invoice->tax_amount, 0, ',', '.') }}</span>
                    </div>
                @endif
                @if((float) $invoice->discount > 0)
                    <div class="summary-row" style="color: #22c55e;">
                        <span>DIKURANGI DISCOUNT</span>
                        <span>- Rp {{ number_format((float) $invoice->discount, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="summary-row" style="font-weight: bold; border-top: 1px solid #000; padding-top: 5px;">
                    <span>GRAND TOTAL</span>
                    <span>Rp {{ number_format((float) $invoice->total, 0, ',', '.') }}</span>
                </div>
                @if((float) $invoice->paid_amount > 0)
                    <div class="summary-row" style="color: #22c55e;">
                        <span>SUDAH DIBAYAR (UANG MUKA)</span>
                        <span>- Rp {{ number_format((float) $invoice->paid_amount, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="summary-row total"
                    style="color: {{ $invoice->remaining_amount > 0 ? '#c41e3a' : '#22c55e' }};">
                    <span>SISA YANG HARUS DIBAYAR</span>
                    <span>Rp {{ number_format((float) $invoice->remaining_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- PAYMENT INFO -->
        <div class="payment-section">
            <div class="payment-title">PEMBAYARAN TRANSFER KE :</div>
            <div class="payment-row">
                <span class="payment-label">BANK</span>
                <span>: BCA - CABANG JATAKE</span>
            </div>
            <div class="payment-row">
                <span class="payment-label">A/N</span>
                <span>: MULYADI</span>
            </div>
            <div class="payment-row">
                <span class="payment-label">A/C NO.</span>
                <span>: 762-0841-948</span>
            </div>
            <div class="payment-note">
                PEMBAYARAN DENGAN CHEQUE/GIRO DIANGGAP LUNAS SETELAH DIKLIRING.
            </div>
        </div>

        <!-- SIGNATURE -->
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-title">PENERIMA</div>
                <div class="signature-name">({{ $invoice->customer?->name ?? '...................' }})</div>
            </div>
            <div class="signature-box">
                <div style="text-align: right; font-size: 10px; margin-bottom: 5px;">
                    Tangerang, {{ $invoice->invoice_date->translatedFormat('d F Y') }}
                </div>
                <div class="signature-title">HORMAT KAMI</div>
                <div class="signature-name">(CV. BERKAH DOA BUNDA)</div>
            </div>
        </div>
    </div>
</body>

</html>