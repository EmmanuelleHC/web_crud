<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .invoice-header { text-align: center; margin-bottom: 20px; }
        .invoice-details, .invoice-items { width: 100%; margin: 20px 0; }
        .invoice-items th, .invoice-items td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        .total { font-weight: bold; }
    </style>
</head>
<body>
    <div class="invoice-header">
        <h1>Invoice: {{ $invoice->invoice_number }}</h1>
        <p>Date: {{ $invoice->invoice_date }}</p>
        <p>Client Number: {{ $invoice->client_number }}</p>
        <p>Address: {{ $invoice->client_address }}</p>
    </div>

    <table class="invoice-details">
        <tr>
            <td><strong>Invoice Date:</strong> {{ $invoice->invoice_date }}</td>
            <td><strong>Client Number:</strong> {{ $invoice->client_number }}</td>
        </tr>
        <tr>
            <td><strong>Client Address:</strong> {{ $invoice->client_address }}</td>
            <td><strong>Grand Total:</strong> ${{ number_format($invoice->grand_total, 2) }}</td>
        </tr>
    </table>

    <h3>Items</h3>
    <table class="invoice-items">
        <thead>
            <tr>
                <th>Item ID</th>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->item_id }}</td>
                    <td>{{ $item->item_name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>${{ number_format($item->unit_price, 2) }}</td>
                    <td>${{ number_format($item->total_price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3 class="total">Grand Total: ${{ number_format($invoice->grand_total, 2) }}</h3>
</body>
</html>
