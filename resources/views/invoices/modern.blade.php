@php
    $currencySymbols = [
        'USD' => '$',
        'EUR' => '€',
        'GBP' => '£',
        'GEL' => '₾',
    ];

    $symbol = $currencySymbols[$invoice->account->currency ?? 'USD'] ?? '';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice->number ?? 'N/A' }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            color: #2c3e50;
            margin: 40px;
            background-color: #f9f9f9;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #34495e;
        }

        .section-title {
            font-weight: 600;
            margin-top: 30px;
            margin-bottom: 10px;
            font-size: 14px;
            color: #1a1a1a;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background: #fff;
            border-radius: 4px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        th {
            background-color: #f0f3f5;
            font-weight: 600;
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .right {
            text-align: right;
        }

        .total {
            font-weight: bold;
            background-color: #ecf0f1;
        }

        .info-block {
            background-color: #fff;
            padding: 15px;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .info-block div {
            margin-bottom: 4px;
        }

        .footer-note {
            margin-top: 40px;
            font-size: 10px;
            color: #777;
        }
    </style>
</head>
<body>

    <h1>Invoice</h1>

    <div><strong>Invoice Number:</strong> {{ $invoice->number ?? 'N/A' }}</div>
    <div><strong>Invoice Date:</strong> {{ \Carbon\Carbon::parse($invoice->issue_date)->format('d/m/Y') ?? 'N/A' }}</div>

    <div class="section-title">Your Details</div>
    <div class="info-block">
        <div>{{ $invoice->company->company_name }}</div>
        <div>{{ $invoice->company->company_address }}</div>
        <div>{{ $invoice->company->company_email }}</div>
        <div>{{ $invoice->company->company_phone }}</div>
    </div>

    <div class="section-title">Client Details</div>
    <div class="info-block">
        <div>{{ $invoice->customer->name }}</div>
        <div>{{ $invoice->customer->address }}</div>
        <div>{{ $invoice->customer->email }}</div>
        <div>{{ $invoice->customer->phone }}</div>
        <div>{{ $invoice->customer->country }}</div>
    </div>

    <div class="section-title">Services</div>
    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th class="right">Hours</th>
                <th class="right">Rate</th>
                <th class="right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td class="right">{{ $item->qty }}</td>
                    <td class="right">{{ $symbol }}{{ number_format($item->price, 2) }}</td>
                    <td class="right">{{ $symbol }}{{ number_format($item->amount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="right">VAT (0%)</td>
                <td class="right">{{ $symbol }}0.00</td>
            </tr>
            <tr class="total">
                <td colspan="3" class="right">Total</td>
                <td class="right">{{ $symbol }}{{ number_format($invoice->total_amount, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="section-title">Payment Terms</div>
    <div class="info-block">
        Payment due within 30 days of invoice date.
    </div>

    <div class="section-title">Bank Details</div>
    <table>
        <tr>
            <td>Intermediary bank:</td>
            <td>{{ $invoice->account->intermediary }}</td>
        </tr>
        <tr>
            <td>Account with institution:</td>
            <td>{{ $invoice->account->institution }}</td>
        </tr>
        <tr>
            <td>Beneficiary:</td>
            <td>{{ $invoice->account->beneficiary }}</td>
        </tr>
        <tr>
            <td>Account:</td>
            <td>{{ $invoice->account->account }}</td>
        </tr>
    </table>

    <div class="footer-note">
        Note: This invoice is VAT exempt.
    </div>

</body>
</html>
