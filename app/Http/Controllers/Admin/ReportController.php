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
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $data = $this->getRevenueData($period, $startDate, $endDate);
        return response()->json($data);
    }

    private function getRevenueData(string $period, ?string $startDate = null, ?string $endDate = null): array
    {
        $query = Order::where('payment_status', 'paid')->where('status', '!=', 'cancelled');

        switch ($period) {
            case 'daily':
                $from = Carbon::now()->subDays(13)->startOfDay();
                $to = Carbon::now()->endOfDay();
                $raw = $query->whereBetween('created_at', [$from, $to])
                    ->select(DB::raw("DATE(created_at) as label"), DB::raw('SUM(total_amount) as revenue'), DB::raw('COUNT(*) as orders'))
                    ->groupBy('label')->get()->keyBy('label');
                return $this->fillDateRange($from, $to, 'day', $raw);

            case 'weekly':
                $from = Carbon::now()->subWeeks(11)->startOfWeek();
                $to = Carbon::now()->endOfWeek();
                $raw = $query->whereBetween('created_at', [$from, $to])
                    ->select(DB::raw("CONCAT(YEAR(created_at), '-W', LPAD(WEEK(created_at, 3), 2, '0')) as label"), DB::raw('SUM(total_amount) as revenue'), DB::raw('COUNT(*) as orders'))
                    ->groupBy('label')->get()->keyBy('label');
                return $this->fillDateRange($from, $to, 'week', $raw);

            case 'custom':
                $from = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
                $to = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();
                $diffDays = $from->diffInDays($to);

                if ($diffDays <= 62) {
                    $raw = $query->whereBetween('created_at', [$from, $to])
                        ->select(DB::raw("DATE(created_at) as label"), DB::raw('SUM(total_amount) as revenue'), DB::raw('COUNT(*) as orders'))
                        ->groupBy('label')->get()->keyBy('label');
                    return $this->fillDateRange($from, $to, 'day', $raw);
                }
                $raw = $query->whereBetween('created_at', [$from, $to])
                    ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as label"), DB::raw('SUM(total_amount) as revenue'), DB::raw('COUNT(*) as orders'))
                    ->groupBy('label')->get()->keyBy('label');
                return $this->fillDateRange($from, $to, 'month', $raw);

            default: // monthly
                $from = Carbon::now()->subMonths(11)->startOfMonth();
                $to = Carbon::now()->endOfMonth();
                $raw = $query->whereBetween('created_at', [$from, $to])
                    ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as label"), DB::raw('SUM(total_amount) as revenue'), DB::raw('COUNT(*) as orders'))
                    ->groupBy('label')->get()->keyBy('label');
                return $this->fillDateRange($from, $to, 'month', $raw);
        }
    }

    /**
     * Fill in all intervals between from and to with zero values where no data exists.
     * This ensures the chart shows a complete timeline, not just days with sales.
     */
    private function fillDateRange(Carbon $from, Carbon $to, string $unit, $raw): array
    {
        $labels = [];
        $revenue = [];
        $orders = [];

        $cursor = $from->copy();

        while ($cursor->lte($to)) {
            if ($unit === 'day') {
                $key = $cursor->format('Y-m-d');
                $cursor->addDay();
            } elseif ($unit === 'week') {
                $key = $cursor->format('Y') . '-W' . str_pad($cursor->weekOfYear, 2, '0', STR_PAD_LEFT);
                $cursor->addWeek();
            } else { // month
                $key = $cursor->format('Y-m');
                $cursor->addMonth();
            }

            $row = $raw->get($key);
            $labels[] = $key;
            $revenue[] = $row ? (float) $row->revenue : 0;
            $orders[] = $row ? (int) $row->orders : 0;
        }

        return [
            'labels' => $labels,
            'revenue' => $revenue,
            'orders' => $orders,
        ];
    }
}
