<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
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
            'status' => 'required|in:pending,confirmed,processing,shipped,out_for_delivery,delivered,cancelled',
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
            'status' => 'required|in:pending,confirmed,processing,shipped,out_for_delivery,delivered,cancelled',
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
                'payment_status' => 'refunded',
            ]),
            'shipped' => $order->update(['shipped_at' => now()]),
            // If moved BACK from delivered to any other status, revert payment for COD
            default => $order->payment_method === 'cod' && $order->payment_status === 'paid'
                ? $order->update(['payment_status' => 'pending', 'paid_at' => null])
                : null,
        };

        // Add to timeline
        $order->timeline()->create([
            'status' => $request->status,
            'message' => "Order status changed to " . ucfirst(str_replace('_', ' ', $request->status)),
        ]);

        return back()->with('success', 'Order status updated.');
    }
}
