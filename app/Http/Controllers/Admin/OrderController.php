<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

use App\Models\Address;
use App\Models\OrderItem;

class OrderController extends Controller
{
    public function create()
    {
        return view('admin.orders.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string',
            'phone' => 'required|string',
            'address_line1' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'pincode' => 'required|string',
            'items' => 'required|array|min:1',
        ]);

        // Create address
        $address = Address::create([
            'user_id' => auth()->id(),
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'address_line1' => $request->address_line1,
            'city' => $request->city,
            'state' => $request->state,
            'pincode' => $request->pincode,
        ]);

        // Calculate subtotal
        $subtotal = 0;
        foreach ($request->items as $item) {
            if (!empty($item['product_id']) && !empty($item['price'])) {
                $subtotal += floatval($item['price']) * intval($item['quantity'] ?? 1);
            }
        }

        $shipping = floatval($request->shipping_charge ?? 0);
        $discount = floatval($request->discount ?? 0);
        $total = $subtotal - $discount + $shipping;

        $isPrepaid = $request->payment_method === 'prepaid';

        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'user_id' => auth()->id(),
            'address_id' => $address->id,
            'status' => 'confirmed',
            'payment_status' => $isPrepaid ? 'paid' : 'pending',
            'payment_method' => $isPrepaid ? 'razorpay' : $request->payment_method,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'shipping_charge' => $shipping,
            'total_amount' => $total,
            'shipping_method' => $request->shipping_method ?? 'standard',
            'courier_name' => $request->courier_name,
            'tracking_number' => $request->tracking_number,
            'tracking_url' => $request->tracking_url,
            'awb_number' => $request->tracking_number,
            'admin_notes' => $request->admin_notes,
            'paid_at' => $isPrepaid ? now() : null,
        ]);

        // Create order items
        foreach ($request->items as $item) {
            if (!empty($item['product_id']) && !empty($item['price'])) {
                $product = \App\Models\Product::find($item['product_id']);
                if ($product) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'quantity' => intval($item['quantity'] ?? 1),
                        'price' => floatval($item['price']),
                        'total_price' => floatval($item['price']) * intval($item['quantity'] ?? 1),
                    ]);
                }
            }
        }

        $order->timeline()->create([
            'status' => 'confirmed',
            'message' => 'Order created manually by admin',
        ]);

        return redirect()->route('admin.orders.show', $order)->with('success', 'Manual order created!');
    }

    public function index(Request $request)
    {
        $query = Order::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment')) {
            if ($request->payment === 'cod') {
                $query->where('payment_method', 'cod');
            } else {
                $query->where('payment_status', $request->payment);
            }
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'LIKE', "%{$request->search}%")
                    ->orWhereHas('user', fn($u) => $u->where('name', 'LIKE', "%{$request->search}%")->orWhere('email', 'LIKE', "%{$request->search}%"));
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20)->appends($request->query());

        return view('admin.orders.index', compact('orders'));
    }

    public function bulkStatus(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:orders,id',
            'status' => 'required|in:confirmed,processing,shipped,delivered,cancelled',
        ]);

        $orders = Order::whereIn('id', $request->ids)->get();

        foreach ($orders as $order) {
            $order->update(['status' => $request->status]);
            match ($request->status) {
                'shipped' => $order->update(['shipped_at' => now()]),
                'delivered' => $order->update(['delivered_at' => now()]),
                'cancelled' => $order->update(['cancelled_at' => now()]),
                default => null,
            };
            $order->timeline()->create([
                'status' => $request->status,
                'message' => "Order status changed to " . ucfirst(str_replace('_', ' ', $request->status)) . " (bulk update)",
            ]);
        }

        return response()->json(['success' => true, 'message' => count($request->ids) . ' orders updated.']);
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product.primaryImage', 'items.product.images', 'address', 'timeline', 'coupon']);
        return view('admin.orders.show', compact('order'));
    }

    public function invoice(Order $order)
    {
        $order->load(['user', 'items', 'address']);
        return view('admin.orders.invoice', compact('order'));
    }

    public function updateTracking(Request $request, Order $order)
    {
        $order->update([
            'courier_name' => $request->courier_name,
            'tracking_number' => $request->tracking_number,
            'tracking_url' => $request->tracking_url,
            'awb_number' => $request->tracking_number,
        ]);

        if ($request->tracking_number) {
            $order->timeline()->create([
                'status' => $order->status,
                'message' => 'Tracking updated: ' . ($request->courier_name ?? '') . ' - ' . $request->tracking_number,
            ]);
        }

        return back()->with('success', 'Tracking information updated.');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:confirmed,processing,shipped,delivered,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        // Dynamic payment status based on order status
        match ($request->status) {
            'delivered' => $order->update([
                'delivered_at' => now(),
                'payment_status' => 'paid',
                'paid_at' => now(),
            ]),
            'cancelled' => $order->update([
                'cancelled_at' => now(),
                'payment_status' => $order->payment_status === 'paid' ? 'refunded' : $order->payment_status,
            ]),
            'shipped' => $order->update(['shipped_at' => now()]),
            default => null,
        };

        // Add to timeline
        $order->timeline()->create([
            'status' => $request->status,
            'message' => "Order status changed to " . ucfirst(str_replace('_', ' ', $request->status)),
        ]);

        // Send email notification to customer (NOT for cancelled)
        $customerEmail = $order->address?->email ?? ($order->user?->email ?? null);
        if ($customerEmail && in_array($request->status, ['processing', 'shipped', 'delivered'])) {
            try {
                \Illuminate\Support\Facades\Mail::to($customerEmail)->send(new \App\Mail\OrderStatusMail($order->fresh(['items', 'address'])));
            } catch (\Exception $e) {
                \Log::warning('Order email failed: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Order status updated.');
    }

    /**
     * Ship order via Velocity Shipping (auto-assigns courier + AWB)
     */
    public function shipViaVelocity(Order $order)
    {
        $velocity = new \App\Services\VelocityShipping();
        $result = $velocity->createShipment($order->load(['items', 'address']));

        if ($result['success']) {
            $order->update([
                'status' => 'shipped',
                'shipped_at' => now(),
                'tracking_number' => $result['awb_code'],
                'awb_number' => $result['awb_code'],
                'courier_name' => $result['courier_name'],
                'tracking_url' => 'https://shipfastt.in/track/' . $result['awb_code'],
            ]);

            $order->timeline()->create([
                'status' => 'shipped',
                'message' => 'Shipped via ' . $result['courier_name'] . ' — AWB: ' . $result['awb_code'],
            ]);

            // Send shipped email to customer
            $customerEmail = $order->address?->email ?? ($order->user?->email ?? null);
            if ($customerEmail) {
                try {
                    \Illuminate\Support\Facades\Mail::to($customerEmail)->send(new \App\Mail\OrderStatusMail($order->fresh(['items', 'address'])));
                } catch (\Exception $e) {
                    \Log::warning('Shipped email failed: ' . $e->getMessage());
                }
            }

            return back()->with('success', 'Order shipped via ' . $result['courier_name'] . '! AWB: ' . $result['awb_code']);
        }

        return back()->with('error', 'Velocity Shipping failed: ' . ($result['error'] ?? 'Unknown error. Check logs.'));
    }
}
