<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Cache dashboard stats for 5 minutes (admin doesn't need real-time)
        $stats = Cache::remember('admin_dashboard_stats', 300, function () {
            return [
                'total_revenue' => Order::where('payment_status', 'paid')
                    ->where('status', '!=', 'cancelled')
                    ->sum('total_amount'),
                'total_orders' => Order::where('status', '!=', 'cancelled')->count(),
                'total_products' => Product::count(),
                'total_customers' => User::where('role', 'customer')->count(),
                'pending_orders' => Order::where('status', 'pending')->count(),
                'low_stock' => Product::whereNotNull('stock')
                    ->whereNotNull('low_stock_alert')
                    ->whereColumn('stock', '<=', 'low_stock_alert')
                    ->count(),
            ];
        });

        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $lowStockProducts = Product::whereNotNull('stock')
            ->whereNotNull('low_stock_alert')
            ->whereColumn('stock', '<=', 'low_stock_alert')
            ->where('is_active', true)
            ->with('primaryImage')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'lowStockProducts'));
    }
}
