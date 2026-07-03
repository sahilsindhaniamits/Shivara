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

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'LIKE', "%{$request->search}%")
                    ->orWhereHas('user', fn($u) => $u->where('name', 'LIKE', "%{$request->search}%"));
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'address', 'timeline', 'coupon']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,out_for_delivery,delivered,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        // Update related timestamps
        match ($request->status) {
            'shipped' => $order->update(['shipped_at' => now()]),
            'delivered' => $order->update(['delivered_at' => now()]),
            'cancelled' => $order->update(['cancelled_at' => now()]),
            default => null,
        };

        // Add to timeline
        $order->timeline()->create([
            'status' => $request->status,
            'message' => "Order status changed to " . ucfirst(str_replace('_', ' ', $request->status)),
        ]);

        return back()->with('success', 'Order status updated.');
    }
}
