<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
        }
        .header {
            padding: 20px 0;
            border-bottom: 1px solid #eee;
        }
        .details {
            margin-top: 20px;
        }
        .amount {
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="header">
            <h1>Invoice</h1>
            <p>Invoice Number: {{ $invoice_number }}</p>
            <p>Date: {{ $date }}</p>
        </div>
        
        <div class="details">
            <h3>Bill To:</h3>
            <p>{{ $customer_name }}<br>
            {{ $customer_email }}</p>
        </div>

        <div class="amount">
            <p>Amount: MXN ${{ $amount }}</p>
            <p>Transaction ID: {{ $transaction_id }}</p>
        </div>
    </div>
</body>
</html> 