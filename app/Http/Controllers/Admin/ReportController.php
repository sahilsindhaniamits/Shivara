<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'monthly');

        // Revenue data based on period
        $revenueData = $this->getRevenueData($period);

        // Top selling products (by quantity sold)
        $topProducts = OrderItem::select('product_id', 'product_name', DB::raw('SUM(quantity) as total_sold'), DB::raw('SUM(total_price) as total_revenue'))
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_sold')
            ->take(10)
            ->get();

        // Orders by status
        $ordersByStatus = Order::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        // Customer growth (last 6 months)
        $customerGrowth = User::where('role', 'customer')
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"), DB::raw('COUNT(*) as count'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Summary stats (exclude cancelled orders from revenue)
        $stats = [
            'total_revenue' => Order::where('payment_status', 'paid')->where('status', '!=', 'cancelled')->sum('total_amount'),
            'this_month_revenue' => Order::where('payment_status', 'paid')->where('status', '!=', 'cancelled')->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->sum('total_amount'),
            'last_month_revenue' => Order::where('payment_status', 'paid')->where('status', '!=', 'cancelled')->whereMonth('created_at', Carbon::now()->subMonth()->month)->whereYear('created_at', Carbon::now()->subMonth()->year)->sum('total_amount'),
            'total_orders' => Order::where('status', '!=', 'cancelled')->count(),
            'this_month_orders' => Order::where('status', '!=', 'cancelled')->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->count(),
            'avg_order_value' => Order::where('payment_status', 'paid')->where('status', '!=', 'cancelled')->avg('total_amount') ?? 0,
            'total_customers' => User::where('role', 'customer')->count(),
            'new_customers_this_month' => User::where('role', 'customer')->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->count(),
        ];

        // Revenue growth percentage
        $stats['revenue_growth'] = $stats['last_month_revenue'] > 0
            ? round((($stats['this_month_revenue'] - $stats['last_month_revenue']) / $stats['last_month_revenue']) * 100, 1)
            : 0;

        return view('admin.reports.index', compact('revenueData', 'topProducts', 'ordersByStatus', 'customerGrowth', 'stats', 'period'));
    }

    public function revenueChart(Request $request)
    {
        $period = $request->get('period', 'monthly');
        $data = $this->getRevenueData($period);
        return response()->json($data);
    }

    private function getRevenueData(string $period): array
    {
        $query = Order::where('payment_status', 'paid')->where('status', '!=', 'cancelled');

        switch ($period) {
            case 'daily':
                $data = $query->where('created_at', '>=', Carbon::now()->subDays(30))
                    ->select(DB::raw("DATE(created_at) as label"), DB::raw('SUM(total_amount) as revenue'), DB::raw('COUNT(*) as orders'))
                    ->groupBy('label')
                    ->orderBy('label')
                    ->get();
                break;

            case 'weekly':
                $data = $query->where('created_at', '>=', Carbon::now()->subWeeks(12))
                    ->select(DB::raw("CONCAT(YEAR(created_at), '-W', LPAD(WEEK(created_at), 2, '0')) as label"), DB::raw('SUM(total_amount) as revenue'), DB::raw('COUNT(*) as orders'))
                    ->groupBy('label')
                    ->orderBy('label')
                    ->get();
                break;

            default: // monthly
                $data = $query->where('created_at', '>=', Carbon::now()->subMonths(12))
                    ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as label"), DB::raw('SUM(total_amount) as revenue'), DB::raw('COUNT(*) as orders'))
                    ->groupBy('label')
                    ->orderBy('label')
                    ->get();
                break;
        }

        return [
            'labels' => $data->pluck('label')->toArray(),
            'revenue' => $data->pluck('revenue')->map(fn($v) => (float) $v)->toArray(),
            'orders' => $data->pluck('orders')->toArray(),
        ];
    }
}
