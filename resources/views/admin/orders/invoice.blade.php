<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $order->order_number }} - Shivara</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', system-ui, sans-serif; font-size: 13px; color: #1a1a1a; background: #f8f8f8; }
        .invoice { max-width: 800px; margin: 20px auto; background: #fff; padding: 50px; box-shadow: 0 2px 20px rgba(0,0,0,0.08); }
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 40px; border-bottom: 3px solid #B7925C; padding-bottom: 30px; }
        .logo h1 { font-size: 28px; font-weight: 800; color: #2C2418; letter-spacing: 3px; }
        .logo p { font-size: 11px; color: #8c7560; margin-top: 4px; }
        .invoice-title { text-align: right; }
        .invoice-title h2 { font-size: 32px; font-weight: 300; color: #B7925C; text-transform: uppercase; letter-spacing: 5px; }
        .invoice-title p { font-size: 12px; color: #666; margin-top: 5px; }
        .meta { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 35px; }
        .meta-box h4 { font-size: 10px; text-transform: uppercase; letter-spacing: 1.5px; color: #B7925C; font-weight: 700; margin-bottom: 8px; }
        .meta-box p { font-size: 13px; color: #333; line-height: 1.6; }
        .meta-box .highlight { font-weight: 600; color: #1a1a1a; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        thead th { background: #2C2418; color: #fff; padding: 12px 15px; text-align: left; font-size: 10px; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; }
        thead th:last-child { text-align: right; }
        tbody td { padding: 14px 15px; border-bottom: 1px solid #f0ebe6; font-size: 13px; }
        tbody td:last-child { text-align: right; font-weight: 600; }
        tbody tr:hover { background: #fdf8f0; }
        .totals { display: flex; justify-content: flex-end; margin-bottom: 35px; }
        .totals-table { width: 300px; }
        .totals-table tr td { padding: 8px 0; font-size: 13px; }
        .totals-table tr td:last-child { text-align: right; font-weight: 600; }
        .totals-table .grand-total td { border-top: 2px solid #2C2418; padding-top: 12px; font-size: 16px; font-weight: 700; color: #2C2418; }
        .totals-table .discount td { color: #16a34a; }
        .footer { border-top: 1px solid #e8e0d5; padding-top: 25px; display: grid; grid-template-columns: 1fr 1fr; gap: 30px; }
        .footer h4 { font-size: 10px; text-transform: uppercase; letter-spacing: 1.5px; color: #B7925C; font-weight: 700; margin-bottom: 6px; }
        .footer p { font-size: 11px; color: #666; line-height: 1.5; }
        .stamp { text-align: center; margin-top: 40px; padding-top: 30px; border-top: 1px dashed #d4c5b0; }
        .stamp p { font-size: 11px; color: #999; }
        .stamp .brand { font-size: 14px; font-weight: 700; color: #B7925C; margin-top: 5px; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
        .badge-paid { background: #dcfce7; color: #166534; }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-cod { background: #e0e7ff; color: #3730a3; }
        @media print {
            body { background: #fff; }
            .invoice { box-shadow: none; margin: 0; padding: 30px; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <!-- Print Button -->
    <div class="no-print" style="text-align:center; padding: 15px; background: #2C2418;">
        <button onclick="window.print()" style="background:#B7925C; color:#fff; border:none; padding:10px 30px; border-radius:8px; font-size:13px; font-weight:700; cursor:pointer; margin-right:10px;">Print Invoice</button>
        <button onclick="window.close()" style="background:transparent; color:#fff; border:1px solid #fff; padding:10px 30px; border-radius:8px; font-size:13px; cursor:pointer;">Close</button>
    </div>

    <div class="invoice">
        <!-- Header -->
        <div class="header">
            <div class="logo">
                <h1>SHIVARA</h1>
                <p>Premium Ayurvedic Products</p>
            </div>
            <div class="invoice-title">
                <h2>Invoice</h2>
                <p><strong>{{ $order->order_number }}</strong></p>
                <p>Date: {{ $order->created_at->format('d M Y') }}</p>
            </div>
        </div>

        <!-- Meta Info -->
        <div class="meta">
            <div class="meta-box">
                <h4>Bill To</h4>
                @if($order->address)
                <p class="highlight">{{ $order->address->full_name }}</p>
                <p>{{ $order->address->address_line1 }}</p>
                @if($order->address->address_line2)<p>{{ $order->address->address_line2 }}</p>@endif
                <p>{{ $order->address->city }}, {{ $order->address->state }} - {{ $order->address->pincode }}</p>
                <p>Phone: {{ $order->address->phone }}</p>
                @endif
            </div>
            <div class="meta-box" style="text-align: right;">
                <h4>Order Details</h4>
                <p>Order: <span class="highlight">{{ $order->order_number }}</span></p>
                <p>Date: {{ $order->created_at->format('d/m/Y') }}</p>
                <p>Payment: <span class="badge {{ $order->payment_status === 'paid' ? 'badge-paid' : 'badge-pending' }}">{{ ucfirst($order->payment_status) }}</span></p>
                <p>Method: <span class="badge badge-cod">{{ strtoupper($order->payment_method) }}</span></p>
                <p>Shipping: {{ ucfirst($order->shipping_method ?? 'Standard') }}</p>
            </div>
        </div>

        <!-- Items Table -->
        <table>
            <thead>
                <tr>
                    <th style="width:10%">#</th>
                    <th style="width:45%">Product</th>
                    <th style="width:15%">Qty</th>
                    <th style="width:15%">Price</th>
                    <th style="width:15%">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>
                        <strong>{{ $item->product_name }}</strong>
                        @if($item->variant_name)<br><span style="color:#8c7560; font-size:11px;">{{ $item->variant_name }}</span>@endif
                    </td>
                    <td>{{ $item->quantity }}</td>
                    <td>₹{{ number_format($item->price, 2) }}</td>
                    <td>₹{{ number_format($item->total_price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals">
            <table class="totals-table">
                <tr>
                    <td>Subtotal</td>
                    <td>₹{{ number_format($order->subtotal, 2) }}</td>
                </tr>
                @if($order->discount > 0)
                <tr class="discount">
                    <td>Discount @if($order->coupon_code)({{ $order->coupon_code }})@endif</td>
                    <td>-₹{{ number_format($order->discount, 2) }}</td>
                </tr>
                @endif
                <tr>
                    <td>Shipping ({{ ucfirst($order->shipping_method ?? 'Standard') }})</td>
                    <td>{{ $order->shipping_charge > 0 ? '₹' . number_format($order->shipping_charge, 2) : 'FREE' }}</td>
                </tr>
                @if($order->tax_amount > 0)
                <tr>
                    <td>Tax</td>
                    <td>₹{{ number_format($order->tax_amount, 2) }}</td>
                </tr>
                @endif
                <tr class="grand-total">
                    <td>Total Amount</td>
                    <td>₹{{ number_format($order->total_amount, 2) }}</td>
                </tr>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div>
                <h4>Payment Information</h4>
                <p>Method: {{ strtoupper($order->payment_method) }}</p>
                <p>Status: {{ ucfirst($order->payment_status) }}</p>
                @if($order->razorpay_payment_id)<p>Transaction ID: {{ $order->razorpay_payment_id }}</p>@endif
                @if($order->paid_at)<p>Paid on: {{ $order->paid_at->format('d M Y, h:i A') }}</p>@endif
            </div>
            <div style="text-align: right;">
                <h4>Company Details</h4>
                <p>Shivara Ayurveda</p>
                <p>{{ config('shivara.email', 'support@shivara.in') }}</p>
                <p>{{ config('shivara.phone', '+91 9876543210') }}</p>
                <p>GSTIN: {{ config('shivara.gstin', 'N/A') }}</p>
            </div>
        </div>

        <!-- Stamp -->
        <div class="stamp">
            <p>This is a computer-generated invoice and does not require a signature.</p>
            <p class="brand">Thank you for shopping with Shivara!</p>
        </div>
    </div>
</body>
</html>
