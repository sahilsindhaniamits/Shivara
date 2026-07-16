<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"></head>
<body style="margin:0;padding:0;font-family:'Helvetica Neue',Arial,sans-serif;background-color:#f8f5f0;">
<div style="max-width:600px;margin:0 auto;background:#fff;">
    <!-- Header -->
    <div style="background-color:#2C2418;padding:24px;text-align:center;">
        <h1 style="color:#D4B078;margin:0;font-size:24px;font-family:Georgia,serif;">Shivara</h1>
        <p style="color:#fff;margin:4px 0 0;font-size:11px;letter-spacing:2px;text-transform:uppercase;">Ayurvedic Purity, Elevated</p>
    </div>

    <!-- Content -->
    <div style="padding:32px 24px;">
        <h2 style="color:#2C2418;font-size:20px;margin:0 0 8px;">Order {{ ucfirst($order->status) }}</h2>
        <p style="color:#6b5442;font-size:14px;line-height:1.6;margin:0 0 20px;">{{ $statusMessage }}</p>

        <!-- Order Details -->
        <div style="background:#f8f5f0;border-radius:12px;padding:20px;margin:20px 0;">
            <table style="width:100%;font-size:13px;color:#2C2418;">
                <tr><td style="padding:4px 0;"><strong>Order Number:</strong></td><td style="text-align:right;">{{ $order->order_number }}</td></tr>
                <tr><td style="padding:4px 0;"><strong>Total Amount:</strong></td><td style="text-align:right;">₹{{ number_format($order->total_amount, 2) }}</td></tr>
                <tr><td style="padding:4px 0;"><strong>Payment:</strong></td><td style="text-align:right;">{{ ucfirst($order->payment_method) }}</td></tr>
                @if($order->tracking_number)
                <tr><td style="padding:4px 0;"><strong>Tracking:</strong></td><td style="text-align:right;">{{ $order->tracking_number }}</td></tr>
                @endif
            </table>
        </div>

        <!-- Items -->
        <h3 style="color:#2C2418;font-size:14px;margin:20px 0 10px;">Items Ordered:</h3>
        @foreach($order->items as $item)
        <div style="display:flex;align-items:center;padding:8px 0;border-bottom:1px solid #f0e8db;">
            <div style="flex:1;">
                <p style="margin:0;font-size:13px;font-weight:600;color:#2C2418;">{{ $item->product_name }}</p>
                @if($item->variant_name)<p style="margin:2px 0 0;font-size:11px;color:#8c7560;">{{ $item->variant_name }}</p>@endif
            </div>
            <div style="text-align:right;">
                <p style="margin:0;font-size:13px;color:#2C2418;">{{ $item->quantity }} × ₹{{ number_format($item->price) }}</p>
            </div>
        </div>
        @endforeach

        <!-- CTA -->
        @if($order->status === 'delivered')
        <div style="text-align:center;margin:28px 0 0;">
            <a href="{{ url('/products') }}" style="display:inline-block;padding:12px 32px;background-color:#2C2418;color:#fff;text-decoration:none;border-radius:30px;font-size:13px;font-weight:600;">Shop Again</a>
        </div>
        @endif
    </div>

    <!-- Footer -->
    <div style="background:#f8f5f0;padding:20px 24px;text-align:center;border-top:1px solid #e8dcc8;">
        <p style="margin:0;font-size:11px;color:#8c7560;">Shivara — Ayurvedic Purity, Elevated</p>
        <p style="margin:4px 0 0;font-size:11px;color:#a89070;">{{ config('shivara.email') }} | {{ config('shivara.phone') }}</p>
    </div>
</div>
</body>
</html>
