<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $invoice->invoice_number }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap');
        
        body {
            font-family: 'Outfit', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            font-size: 10pt;
        }

        .container {
            width: 148mm; /* A5 Width */
            padding: 10mm;
            margin: 0 auto;
            background: white;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 { margin: 0; font-size: 18pt; font-weight: 700; color: #222; }
        .header p { margin: 2px 0; font-size: 9pt; color: #666; }

        .meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .meta-col { width: 48%; }
        .meta-label { font-weight: 600; font-size: 8pt; color: #888; text-transform: uppercase; }
        .meta-value { font-size: 10pt; font-weight: 600; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { text-align: left; border-bottom: 2px solid #333; padding: 5px 0; font-size: 9pt; }
        td { padding: 8px 0; border-bottom: 1px solid #eee; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }

        .totals {
            width: 50%;
            margin-left: auto;
        }
        .total-row { display: flex; justify-content: space-between; padding: 4px 0; }
        .total-row.final { border-top: 2px solid #333; margin-top: 5px; padding-top: 5px; font-weight: 700; font-size: 12pt; }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 8pt;
            color: #777;
            border-top: 1px dashed #ddd;
            padding-top: 10px;
        }

        @media print {
            @page { size: A5; margin: 0; }
            body { margin: 0; -webkit-print-color-adjust: exact; }
            .container { width: 100%; height: 100%; padding: 10mm; box-sizing: border-box; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="container">
        <!-- Header -->
        <div class="header">
            <table style="width: 100%; border: none; margin-bottom: 0;">
                <tr>
                    <td style="width: 60px; border: none; padding-right: 15px; vertical-align: middle;">
                        @if($setting->logo_path)
                            <img src="{{ asset('storage/' . $setting->logo_path) }}" alt="Logo" style="width: 60px; height: auto;">
                        @else
                            <div style="width: 60px; height: 60px; background: #eee; border-radius: 50%;"></div>
                        @endif
                    </td>
                    <td style="border: none; text-align: left; vertical-align: middle;">
                        <h1 style="margin: 0; font-size: 16pt; font-weight: 700; color: #000;">{{ $setting->shop_name }}</h1>
                        <p style="margin: 2px 0 0; font-size: 9pt; color: #444;">{{ $setting->shop_address }}</p>
                        <p style="margin: 0; font-size: 9pt; color: #444;">{{ $setting->shop_phone }} {{ $setting->shop_email ? ' | ' . $setting->shop_email : '' }}</p>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Meta -->
        <div class="meta">
            <div class="meta-col">
                <div class="meta-label">Bill To</div>
                <div class="meta-value">{{ $invoice->customer->name }}</div>
                <div style="font-size: 9pt;">{{ $invoice->customer->phone }} | {{ $invoice->customer->address }}</div>
            </div>
            <div class="meta-col text-right">
                <div class="meta-label">Invoice No</div>
                <div class="meta-value">{{ $invoice->invoice_number }}</div>
                <div class="meta-label" style="margin-top: 5px;">Date</div>
                <div>{{ $invoice->date->format('d M Y') }}</div>
            </div>
        </div>

        <!-- Items -->
        <table>
            <thead>
                <tr>
                    <th style="width: 50%">Item</th>
                    <th class="text-center">Qty</th>
                    <th class="text-right">Price</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->product_type }}</strong>
                            @if(!empty($item->specifications['note']))
                                <br><small style="color: #666">{{ $item->specifications['note'] }}</small>
                            @endif
                        </td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">{{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($item->total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals">
            <div class="total-row">
                <span>Subtotal</span>
                <span>Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
            </div>
            @if($invoice->discount_value > 0)
            <div class="total-row" style="color: #d32f2f;">
                <span>Discount</span>
                <span>- Rp {{ number_format($invoice->discount_type == 'nominal' ? $invoice->discount_value : ($invoice->subtotal * $invoice->discount_value / 100), 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="total-row final">
                <span>Total</span>
                <span>Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</span>
            </div>
            <div class="total-row">
                <span>Paid (DP)</span>
                <span>Rp {{ number_format($invoice->dp_amount, 0, ',', '.') }}</span>
            </div>
            <div class="total-row" style="font-weight: 600; color: {{ $invoice->remaining_amount > 0 ? '#d32f2f' : '#2e7d32' }}">
                <span>Remaining</span>
                <span>Rp {{ number_format($invoice->remaining_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            {{ $setting->footer_note }}
            <br>
            <div style="margin-top: 5px;">Thank you for your business!</div>
        </div>
    </div>

</body>
</html>
