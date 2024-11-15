<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .invoice-container {
            max-width: 800px;
            margin: auto;
            border: 1px solid #ddd;
            padding: 20px;
        }
        .header {
            text-align: right;
        }
        .header h1 {
            margin: 0;
        }
        .info-table, .items-table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 8px;
        }
        .items-table th, .items-table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .items-table th {
            text-align: left;
            font-weight: bold;
        }
        .items-table td.numeric, .items-table th.numeric {
            text-align: right; 
        }
        .totals {
            margin-top: 20px;
            text-align: right;
        }
        .totals p {
            margin: 5px 0;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="header">
            <h1>INVOICE</h1>
            <p>#{{ $invoice->invoice_number }}</p>
        </div>

        <table class="info-table">
            <tr>
                <td><strong>Billed To:</strong></td>
            </tr>
            <tr>
                <td colspan="2">{{ $invoice->client->client_name }}</td>
            </tr>
            <tr>
                <td colspan="2">{{ $invoice->client->client_address }}</td>
            </tr>
            <tr>
                <td><strong>Pay To:</strong> 
     
            </tr>
            <tr>
                <td>John Doe</td>
            </tr>
            <tr>
                <td>123 Main St, Sample City</td>
            </tr>
            <tr>
                <td>+1 234-567-8901</td>
            </tr>
            <tr>
                <td>Bank: Sample Bank</td>
            </tr>
            <tr>
                <td>Account Name: John Doe</td>
            </tr>
            <tr>
                <td>BSB: 123-456</td>
            </tr>
            <tr>
                <td>Account Number: 7890 1234 5678</td>
            </tr>
        </table>

        <h3>Description</h3>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th class="numeric">Price</th>
                    <th class="numeric">Quantity</th>
                    <th class="numeric">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->item_name }}</td>
                    <td class="numeric">${{ number_format($item->unit_price, 2) }}</td>
                    <td class="numeric">{{ $item->quantity }}</td>
                    <td class="numeric">${{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @php
            $subtotal = $invoice->items->sum(function($item) {
                return $item->unit_price * $item->quantity;
            });
            $gst = $subtotal * 0.09;
            $total_with_gst = $subtotal + $gst;
        @endphp

        <div class="totals">
            <p>Subtotal: ${{ number_format($subtotal, 2) }}</p>
            <p>GST (9%): ${{ number_format($gst, 2) }}</p>
            <h3>Total with GST: ${{ number_format($total_with_gst, 2) }}</h3>
        </div>
    </div>
</body>
</html>
