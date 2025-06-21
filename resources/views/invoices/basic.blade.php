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
            font-family: Arial, sans-serif;
            font-size: 11pt;
            margin: 40px;
            color: #333;
        }
        h1 {
            font-size: 20pt;
        }
        .section-title {
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        td, th {
            padding: 8px;
            border: 1px solid #ccc;
        }
        .right {
            text-align: right;
        }
        .total {
            font-weight: bold;
        }
    </style>
</head>
<body>

    <h1>Invoice</h1>

    <p><strong>Invoice Number:</strong> {{ $invoice->number ?? 'N/A' }}</p>
    <p><strong>Invoice Date:</strong> {{ \Carbon\Carbon::parse($invoice->issue_date)->format('d/m/Y') ?? 'N/A' }}</p>

    <div class="section-title">Your Details:</div>
    <p>
        <div>{{ $invoice->company->company_name }}</div>
        <div>{{ $invoice->company->company_address }}</div>
        <div>{{ $invoice->company->company_email }}</div>
        <div>{{ $invoice->company->company_phone }}</div>
    </p>

    <div class="section-title">Client Details:</div>
    <p>
        <div>{{ $invoice->customer->name }}</div>
        <div>{{ $invoice->customer->address }}</div>
        <div>{{ $invoice->customer->email }}</div>
        <div>{{ $invoice->customer->phone }}</div>
        <div>{{ $invoice->customer->country }}</div>
    </p>

    <div class="section-title">Description of Services:</div>
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
                    <td class="">{{ $item->description }}</td>
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
            <tr>
                <td colspan="3" class="right total">Total</td>
                <td class="right total">{{ $symbol }}{{ number_format($invoice->total_amount, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="section-title">Payment Terms:</div>
    <p>Payment due within 30 days of invoice date.</p>

    <div class="section-title">Bank Details:</div>
    <table class="">
        <tr>
            <td>Intermediary bank:</td>
            <td>{{ $invoice->account->intermediary }}</td>
        </tr>
        <tr>
            <td>Account with institution:</td>
            <td>{{ $invoice->account->institution }}<br></td>
        </tr>
        <tr>
            <td>Beneficiary:</td>
            <td>{{ $invoice->account->beneficiary }}<br></td>
        </tr>
        <tr>
            <td>Account:</td>
            <td>{{ $invoice->account->account }}</td>
        </tr>
    </table>

    <p style="margin-top: 30px; font-size: 9pt;">
        Note: This invoice is VAT exempt.
    </p>

</body>
</html>
