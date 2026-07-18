<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $order->order_number }} - Shivara</title>
</head>
<body style="margin:0;padding:0;background-color:#f5f0e8;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif;">

<!-- Wrapper -->
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f5f0e8;padding:20px 10px;">
<tr><td align="center">
<table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background-color:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(44,36,24,0.08);">

    <!-- Header with Logo -->
    <tr>
        <td style="background-color:#2C2418;padding:28px 32px;text-align:center;">
            <img src="{{ url('/public/shivaralogo1.png') }}" alt="Shivara" height="40" style="height:40px;width:auto;display:inline-block;filter:brightness(0) invert(1);">
            <p style="color:rgba(255,255,255,0.6);margin:8px 0 0;font-size:10px;letter-spacing:3px;text-transform:uppercase;font-weight:500;">Ayurvedic Purity, Elevated</p>
        </td>
    </tr>

    <!-- Status Banner -->
    <tr>
        <td style="padding:0;">
            @php
                $statusConfig = match($order->status) {
                    'confirmed' => ['icon' => '✓', 'color' => '#16a34a', 'bg' => '#f0fdf4', 'title' => 'Order Confirmed!', 'subtitle' => 'Your order has been confirmed and is being prepared.'],
                    'processing' => ['icon' => '⚙️', 'color' => '#2563eb', 'bg' => '#eff6ff', 'title' => 'Order Processing', 'subtitle' => 'We are carefully packing your products with love.'],
                    'shipped' => ['icon' => '🚚', 'color' => '#7c3aed', 'bg' => '#f5f3ff', 'title' => 'Order Shipped!', 'subtitle' => 'Your package is on its way to you.'],
                    'delivered' => ['icon' => '🎉', 'color' => '#16a34a', 'bg' => '#f0fdf4', 'title' => 'Order Delivered!', 'subtitle' => 'Your order has been delivered. Enjoy!'],
                    default => ['icon' => '📋', 'color' => '#B7925C', 'bg' => '#FBF7F0', 'title' => 'Order Update', 'subtitle' => $statusMessage],
                };
            @endphp
            <div style="background-color:{{ $statusConfig['bg'] }};padding:28px 32px;text-align:center;border-bottom:1px solid rgba(44,36,24,0.06);">
                <div style="font-size:36px;margin-bottom:12px;">{{ $statusConfig['icon'] }}</div>
                <h1 style="color:{{ $statusConfig['color'] }};font-size:22px;font-weight:700;margin:0 0 6px;font-family:Georgia,'Times New Roman',serif;">{{ $statusConfig['title'] }}</h1>
                <p style="color:#6b5442;font-size:14px;margin:0;line-height:1.5;">{{ $statusConfig['subtitle'] }}</p>
            </div>
        </td>
    </tr>

    <!-- Order Progress Bar -->
    @if(in_array($order->status, ['confirmed', 'processing', 'shipped', 'delivered']))
    <tr>
        <td style="padding:24px 32px 0;">
            @php
                $steps = ['confirmed' => 1, 'processing' => 2, 'shipped' => 3, 'delivered' => 4];
                $currentStep = $steps[$order->status] ?? 1;
            @endphp
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    @foreach(['Confirmed', 'Processing', 'Shipped', 'Delivered'] as $idx => $step)
                    <td style="text-align:center;width:25%;padding:0;">
                        <div style="width:24px;height:24px;border-radius:50%;margin:0 auto 4px;line-height:24px;font-size:10px;font-weight:700;{{ ($idx + 1) <= $currentStep ? 'background-color:#2C2418;color:#fff;' : 'background-color:#e5e7eb;color:#9ca3af;' }}">{{ ($idx + 1) <= $currentStep ? '✓' : ($idx + 1) }}</div>
                        <p style="margin:0;font-size:9px;color:{{ ($idx + 1) <= $currentStep ? '#2C2418' : '#9ca3af' }};font-weight:{{ ($idx + 1) <= $currentStep ? '600' : '400' }};">{{ $step }}</p>
                    </td>
                    @endforeach
                </tr>
                <tr>
                    <td colspan="4" style="padding:12px 0 0;">
                        <div style="height:3px;background-color:#e5e7eb;border-radius:3px;overflow:hidden;">
                            <div style="height:100%;width:{{ ($currentStep / 4) * 100 }}%;background-color:#2C2418;border-radius:3px;"></div>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    @endif

    <!-- Order Details Card -->
    <tr>
        <td style="padding:24px 32px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#FFFDF8;border:1px solid rgba(183,146,92,0.2);border-radius:12px;overflow:hidden;">
                <tr>
                    <td style="padding:16px 20px;border-bottom:1px solid rgba(183,146,92,0.1);">
                        <p style="margin:0;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;color:#B7925C;">Order Details</p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:16px 20px;">
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:13px;color:#2C2418;">
                            <tr>
                                <td style="padding:6px 0;color:#8c7560;">Order Number</td>
                                <td style="padding:6px 0;text-align:right;font-weight:700;">{{ $order->order_number }}</td>
                            </tr>
                            <tr>
                                <td style="padding:6px 0;color:#8c7560;">Order Date</td>
                                <td style="padding:6px 0;text-align:right;">{{ $order->created_at->format('d M Y, h:i A') }}</td>
                            </tr>
                            <tr>
                                <td style="padding:6px 0;color:#8c7560;">Payment Method</td>
                                <td style="padding:6px 0;text-align:right;">{{ $order->payment_method === 'cod' ? 'Cash on Delivery' : 'Prepaid (Razorpay)' }}</td>
                            </tr>
                            <tr>
                                <td style="padding:6px 0;color:#8c7560;">Payment Status</td>
                                <td style="padding:6px 0;text-align:right;">
                                    <span style="display:inline-block;padding:2px 10px;border-radius:20px;font-size:11px;font-weight:600;{{ $order->payment_status === 'paid' ? 'background-color:#dcfce7;color:#166534;' : 'background-color:#fef3c7;color:#92400e;' }}">{{ ucfirst($order->payment_status) }}</span>
                                </td>
                            </tr>
                            @if($order->tracking_number)
                            <tr>
                                <td style="padding:6px 0;color:#8c7560;">Tracking Number</td>
                                <td style="padding:6px 0;text-align:right;font-weight:700;">{{ $order->tracking_number }}</td>
                            </tr>
                            @endif
                            @if($order->courier_name)
                            <tr>
                                <td style="padding:6px 0;color:#8c7560;">Courier</td>
                                <td style="padding:6px 0;text-align:right;">{{ $order->courier_name }}</td>
                            </tr>
                            @endif
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- Items Ordered -->
    <tr>
        <td style="padding:0 32px 24px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#FFFDF8;border:1px solid rgba(183,146,92,0.2);border-radius:12px;overflow:hidden;">
                <tr>
                    <td style="padding:16px 20px;border-bottom:1px solid rgba(183,146,92,0.1);">
                        <p style="margin:0;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;color:#B7925C;">Items Ordered</p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:12px 20px;">
                        @foreach($order->items as $item)
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-bottom:1px solid rgba(183,146,92,0.08);margin-bottom:8px;padding-bottom:8px;">
                            <tr>
                                <td style="padding:8px 0;vertical-align:top;width:60%;">
                                    <p style="margin:0;font-size:13px;font-weight:600;color:#2C2418;">{{ $item->product_name }}</p>
                                    @if($item->variant_name)
                                    <p style="margin:3px 0 0;font-size:11px;color:#B7925C;">{{ $item->variant_name }}</p>
                                    @endif
                                    <p style="margin:3px 0 0;font-size:11px;color:#8c7560;">Qty: {{ $item->quantity }}</p>
                                </td>
                                <td style="padding:8px 0;vertical-align:top;text-align:right;">
                                    <p style="margin:0;font-size:14px;font-weight:700;color:#2C2418;">₹{{ number_format($item->price * $item->quantity) }}</p>
                                    @if($item->quantity > 1)
                                    <p style="margin:2px 0 0;font-size:10px;color:#8c7560;">₹{{ number_format($item->price) }} each</p>
                                    @endif
                                </td>
                            </tr>
                        </table>
                        @endforeach
                    </td>
                </tr>
                <!-- Price Breakdown -->
                <tr>
                    <td style="padding:0 20px 16px;">
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-top:2px solid rgba(183,146,92,0.15);padding-top:12px;">
                            <tr>
                                <td style="padding:4px 0;font-size:12px;color:#8c7560;">Subtotal</td>
                                <td style="padding:4px 0;font-size:12px;color:#2C2418;text-align:right;">₹{{ number_format($order->subtotal) }}</td>
                            </tr>
                            @if($order->shipping_charge > 0)
                            <tr>
                                <td style="padding:4px 0;font-size:12px;color:#8c7560;">Shipping</td>
                                <td style="padding:4px 0;font-size:12px;color:#2C2418;text-align:right;">₹{{ number_format($order->shipping_charge) }}</td>
                            </tr>
                            @else
                            <tr>
                                <td style="padding:4px 0;font-size:12px;color:#8c7560;">Shipping</td>
                                <td style="padding:4px 0;font-size:12px;color:#16a34a;text-align:right;font-weight:600;">FREE</td>
                            </tr>
                            @endif
                            @if($order->discount > 0)
                            <tr>
                                <td style="padding:4px 0;font-size:12px;color:#16a34a;">Discount{{ $order->coupon_code ? ' ('.$order->coupon_code.')' : '' }}</td>
                                <td style="padding:4px 0;font-size:12px;color:#16a34a;text-align:right;">-₹{{ number_format($order->discount) }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td style="padding:10px 0 0;font-size:15px;font-weight:700;color:#2C2418;border-top:1px solid rgba(183,146,92,0.15);">Total</td>
                                <td style="padding:10px 0 0;font-size:15px;font-weight:700;color:#2C2418;text-align:right;border-top:1px solid rgba(183,146,92,0.15);">₹{{ number_format($order->total_amount) }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- Delivery Address -->
    @if($order->address)
    <tr>
        <td style="padding:0 32px 24px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#FFFDF8;border:1px solid rgba(183,146,92,0.2);border-radius:12px;overflow:hidden;">
                <tr>
                    <td style="padding:16px 20px;border-bottom:1px solid rgba(183,146,92,0.1);">
                        <p style="margin:0;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;color:#B7925C;">Delivery Address</p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:16px 20px;">
                        <p style="margin:0;font-size:13px;font-weight:600;color:#2C2418;">{{ $order->address->full_name }}</p>
                        <p style="margin:4px 0 0;font-size:12px;color:#6b5442;line-height:1.6;">
                            {{ $order->address->address_line1 }}{{ $order->address->address_line2 ? ', '.$order->address->address_line2 : '' }}<br>
                            {{ $order->address->city }}, {{ $order->address->state }} - {{ $order->address->pincode }}
                        </p>
                        <p style="margin:6px 0 0;font-size:12px;color:#8c7560;">📞 {{ $order->address->phone }}</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    @endif

    <!-- Track Order Button -->
    <tr>
        <td style="padding:0 32px 28px;text-align:center;">
            @if($order->tracking_url)
            <a href="{{ $order->tracking_url }}" target="_blank" style="display:inline-block;padding:14px 36px;background-color:#2C2418;color:#ffffff;text-decoration:none;border-radius:30px;font-size:13px;font-weight:700;letter-spacing:0.5px;">Track Your Shipment →</a>
            <p style="margin:10px 0 0;font-size:11px;color:#8c7560;">or track on our website ↓</p>
            @endif
            <a href="{{ url('/track-order?order_number=' . $order->order_number . '&phone=' . ($order->address?->phone ?? '')) }}" style="display:inline-block;margin-top:8px;padding:12px 32px;background-color:{{ $order->tracking_url ? '#ffffff' : '#2C2418' }};color:{{ $order->tracking_url ? '#2C2418' : '#ffffff' }};text-decoration:none;border-radius:30px;font-size:13px;font-weight:600;{{ $order->tracking_url ? 'border:2px solid #2C2418;' : '' }}">Track Order on Shivara</a>
        </td>
    </tr>

    <!-- Help Section -->
    <tr>
        <td style="padding:0 32px 24px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f8f5f0;border-radius:12px;padding:20px;">
                <tr>
                    <td style="padding:20px;text-align:center;">
                        <p style="margin:0 0 8px;font-size:13px;font-weight:600;color:#2C2418;">Need Help?</p>
                        <p style="margin:0;font-size:12px;color:#6b5442;line-height:1.6;">
                            Reply to this email or contact us at<br>
                            <a href="mailto:shop@theshivara.com" style="color:#B7925C;text-decoration:none;font-weight:600;">shop@theshivara.com</a> &nbsp;|&nbsp;
                            <a href="https://wa.me/919828385808" style="color:#B7925C;text-decoration:none;font-weight:600;">WhatsApp</a> &nbsp;|&nbsp;
                            <a href="tel:+919828385808" style="color:#B7925C;text-decoration:none;font-weight:600;">{{ config('shivara.phone') }}</a>
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- Footer -->
    <tr>
        <td style="background-color:#2C2418;padding:28px 32px;text-align:center;">
            <img src="{{ url('/public/shivaralogo1.png') }}" alt="Shivara" height="28" style="height:28px;width:auto;display:inline-block;filter:brightness(0) invert(1);margin-bottom:12px;">
            <p style="margin:0 0 8px;font-size:11px;color:rgba(255,255,255,0.7);">100% Natural • GMP Certified • Lab Tested</p>
            <p style="margin:0 0 12px;font-size:11px;color:rgba(255,255,255,0.5);">
                {{ config('shivara.address.line1') }}, {{ config('shivara.address.city') }}, {{ config('shivara.address.state') }} - {{ config('shivara.address.pincode') }}
            </p>
            <div style="margin:12px 0 0;">
                <a href="{{ config('shivara.social.instagram') }}" style="display:inline-block;margin:0 6px;color:rgba(255,255,255,0.6);text-decoration:none;font-size:11px;">Instagram</a>
                <span style="color:rgba(255,255,255,0.3);">•</span>
                <a href="{{ config('shivara.social.facebook') }}" style="display:inline-block;margin:0 6px;color:rgba(255,255,255,0.6);text-decoration:none;font-size:11px;">Facebook</a>
                <span style="color:rgba(255,255,255,0.3);">•</span>
                <a href="{{ url('/') }}" style="display:inline-block;margin:0 6px;color:rgba(255,255,255,0.6);text-decoration:none;font-size:11px;">Shop Now</a>
            </div>
            <p style="margin:16px 0 0;font-size:10px;color:rgba(255,255,255,0.3);">&copy; {{ date('Y') }} Shivara. All rights reserved.</p>
        </td>
    </tr>

</table>
</td></tr>
</table>

</body>
</html>
