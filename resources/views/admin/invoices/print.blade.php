<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; padding: 40px; }
        .invoice-box { max-width: 800px; margin: auto; border: 1px solid #eee; padding: 30px; }
        .header { display: flex; justify-content: space-between; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #333; }
        .company-info h1 { font-size: 28px; color: #333; margin-bottom: 5px; }
        .company-info p { color: #666; font-size: 14px; }
        .invoice-status { text-align: right; }
        .status-badge { display: inline-block; padding: 8px 16px; border-radius: 4px; font-weight: bold; font-size: 14px; }
        .status-paid { background: #d4edda; color: #155724; }
        .status-unpaid { background: #fff3cd; color: #856404; }
        .invoice-details { display: flex; justify-content: space-between; margin-bottom: 30px; }
        .bill-to h3, .invoice-info h3 { font-size: 12px; color: #666; margin-bottom: 10px; }
        .bill-to p, .invoice-info p { margin: 5px 0; color: #333; }
        .invoice-info { text-align: right; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th { background: #f8f9fa; padding: 12px; text-align: left; font-size: 12px; color: #666; border-bottom: 2px solid #dee2e6; }
        td { padding: 12px; border-bottom: 1px solid #dee2e6; }
        .text-right { text-align: right; }
        .total-row { background: #f8f9fa; font-weight: bold; font-size: 18px; }
        .footer { margin-top: 40px; padding-top: 20px; border-top: 1px solid #dee2e6; text-align: center; color: #666; font-size: 12px; }
        @media print {
            body { padding: 0; }
            .invoice-box { border: none; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="header">
            <div class="company-info">
                <h1>{{ $company['name'] }}</h1>
                <p>{{ $company['address'] }}</p>
                <p>Phone: {{ $company['phone'] }} | Email: {{ $company['email'] }}</p>
            </div>
            <div class="invoice-status">
                <span class="status-badge {{ $invoice->status === 'paid' ? 'status-paid' : 'status-unpaid' }}">
                    {{ strtoupper($invoice->status) }}
                </span>
            </div>
        </div>

        <div class="invoice-details">
            <div class="bill-to">
                <h3>BILL TO:</h3>
                <p><strong>{{ $invoice->customer->name }}</strong></p>
                <p>{{ $invoice->customer->phone }}</p>
                <p>{{ $invoice->customer->email }}</p>
                <p>{{ $invoice->customer->address }}</p>
            </div>
            <div class="invoice-info">
                <h3>INVOICE DETAILS:</h3>
                <p><strong>Invoice #:</strong> {{ $invoice->invoice_number }}</p>
                <p><strong>Date:</strong> {{ $invoice->created_at->format('d M Y') }}</p>
                @if($invoice->due_date)
                <p><strong>Due Date:</strong> {{ $invoice->due_date->format('d M Y') }}</p>
                @endif
                @if($invoice->paid_date)
                <p><strong>Paid Date:</strong> {{ $invoice->paid_date->format('d M Y') }}</p>
                @endif
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>{{ $invoice->package->name ?? 'Service' }}</strong><br>
                        <small>{{ ucfirst($invoice->invoice_type) }} - {{ $invoice->description ?? 'Monthly subscription' }}</small>
                    </td>
                    <td class="text-right">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                </tr>
                @if($invoice->tax_amount > 0)
                <tr>
                    <td class="text-right"><strong>Tax</strong></td>
                    <td class="text-right">Rp {{ number_format($invoice->tax_amount, 0, ',', '.') }}</td>
                </tr>
                @endif
                <tr class="total-row">
                    <td class="text-right">TOTAL</td>
                    <td class="text-right">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <p>Thank you for your business!</p>
            <p>This is a computer-generated invoice and does not require a signature.</p>
        </div>
    </div>

    <script>
        window.onload = function() { window.print(); }
    </script>
</body>
</html>
