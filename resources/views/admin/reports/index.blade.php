@extends('layouts.admin')
@section('title', 'Reports & Analytics - Admin')
@section('page_title', 'Reports & Analytics')

@section('content')
<div class="space-y-6">

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                @if($stats['revenue_growth'] != 0)
                <span class="text-xs font-bold px-2 py-1 rounded-full {{ $stats['revenue_growth'] > 0 ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }}">
                    {{ $stats['revenue_growth'] > 0 ? '↑' : '↓' }} {{ abs($stats['revenue_growth']) }}%
                </span>
                @endif
            </div>
            <p class="text-2xl font-bold text-gray-900">₹{{ number_format($stats['total_revenue']) }}</p>
            <p class="text-xs text-gray-500 mt-1">Total Revenue</p>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <span class="text-xs font-bold px-2 py-1 rounded-full bg-blue-50 text-blue-600">{{ $stats['this_month_orders'] }} this month</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_orders']) }}</p>
            <p class="text-xs text-gray-500 mt-1">Total Orders</p>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900">₹{{ number_format($stats['avg_order_value']) }}</p>
            <p class="text-xs text-gray-500 mt-1">Avg Order Value</p>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <span class="text-xs font-bold px-2 py-1 rounded-full bg-amber-50 text-amber-600">+{{ $stats['new_customers_this_month'] }} new</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_customers']) }}</p>
            <p class="text-xs text-gray-500 mt-1">Total Customers</p>
        </div>
    </div>

    <!-- Revenue Chart -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Revenue Overview</h3>
                <p class="text-xs text-gray-500">Track your revenue performance</p>
            </div>
            <div class="flex items-center gap-3">
                <!-- Date Range Picker (shown when Custom is selected) -->
                <div id="custom-date-range" class="hidden items-center gap-2">
                    <input type="date" id="start-date" class="px-2 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-200">
                    <span class="text-xs text-gray-400">to</span>
                    <input type="date" id="end-date" class="px-2 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-200">
                    <button onclick="loadCustomChart()" class="px-3 py-1.5 text-xs bg-espresso-700 text-white rounded-lg hover:bg-espresso-600 transition font-medium">Go</button>
                </div>
                <!-- Period Buttons -->
                <div class="flex gap-1 bg-gray-100 rounded-lg p-1">
                    <button onclick="loadChart('daily')" id="btn-daily" class="px-3 py-1.5 text-xs rounded-md transition text-gray-500 hover:text-gray-700">Daily</button>
                    <button onclick="loadChart('weekly')" id="btn-weekly" class="px-3 py-1.5 text-xs rounded-md transition text-gray-500 hover:text-gray-700">Weekly</button>
                    <button onclick="loadChart('monthly')" id="btn-monthly" class="px-3 py-1.5 text-xs rounded-md transition bg-white shadow-sm text-gray-900 font-semibold">Monthly</button>
                    <button onclick="showCustomRange()" id="btn-custom" class="px-3 py-1.5 text-xs rounded-md transition text-gray-500 hover:text-gray-700">Custom</button>
                </div>
            </div>
        </div>
        <div class="relative h-64">
            <canvas id="revenueCanvas"></canvas>
        </div>
        <div class="flex items-center gap-6 mt-4 pt-4 border-t border-gray-100">
            <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full" style="background-color: #c06d22"></span><span class="text-xs text-gray-600">Revenue (₹)</span></div>
            <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full" style="background-color: #B08840"></span><span class="text-xs text-gray-600">Orders</span></div>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        <!-- Top Selling Products -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-1">Top Selling Products</h3>
            <p class="text-xs text-gray-500 mb-4">By units sold</p>
            @if($topProducts->count())
            <div class="space-y-3">
                @foreach($topProducts as $i => $item)
                <div class="flex items-center gap-3">
                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-bold {{ $i < 3 ? 'text-white' : 'bg-gray-100 text-gray-500' }}" @if($i < 3) style="background-color: {{ $i === 0 ? '#c06d22' : ($i === 1 ? '#B08840' : '#6b7280') }}" @endif>{{ $i + 1 }}</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $item->product_name }}</p>
                        <p class="text-xs text-gray-400">{{ number_format($item->total_sold) }} sold • ₹{{ number_format($item->total_revenue) }} revenue</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-gray-900">{{ number_format($item->total_sold) }}</p>
                        <p class="text-[10px] text-gray-400">units</p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <p class="text-sm text-gray-400">No sales data yet</p>
            </div>
            @endif
        </div>

        <!-- Orders by Status -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-1">Orders by Status</h3>
            <p class="text-xs text-gray-500 mb-4">Current distribution</p>

            @php
                $statusColors = [
                    'pending' => ['bg' => '#FEF3C7', 'text' => '#92400E', 'bar' => '#F59E0B'],
                    'confirmed' => ['bg' => '#DBEAFE', 'text' => '#1E40AF', 'bar' => '#3B82F6'],
                    'processing' => ['bg' => '#E0E7FF', 'text' => '#3730A3', 'bar' => '#6366F1'],
                    'shipped' => ['bg' => '#D1FAE5', 'text' => '#065F46', 'bar' => '#10B981'],
                    'delivered' => ['bg' => '#D1FAE5', 'text' => '#065F46', 'bar' => '#059669'],
                    'cancelled' => ['bg' => '#FEE2E2', 'text' => '#991B1B', 'bar' => '#EF4444'],
                    'returned' => ['bg' => '#F3E8FF', 'text' => '#6B21A8', 'bar' => '#A855F7'],
                ];
                $totalOrders = array_sum($ordersByStatus);
            @endphp

            @if($totalOrders > 0)
            <div class="space-y-3">
                @foreach($ordersByStatus as $status => $count)
                @php $color = $statusColors[$status] ?? ['bg' => '#F3F4F6', 'text' => '#374151', 'bar' => '#6B7280']; @endphp
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full" style="background-color: {{ $color['bg'] }}; color: {{ $color['text'] }}">{{ ucfirst($status) }}</span>
                        </div>
                        <span class="text-xs font-bold text-gray-700">{{ $count }} <span class="text-gray-400 font-normal">({{ $totalOrders > 0 ? round(($count / $totalOrders) * 100) : 0 }}%)</span></span>
                    </div>
                    <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full rounded-full transition-all" style="width: {{ $totalOrders > 0 ? ($count / $totalOrders) * 100 : 0 }}%; background-color: {{ $color['bar'] }}"></div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <p class="text-sm text-gray-400">No orders yet</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Customer Growth -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-1">Customer Growth</h3>
        <p class="text-xs text-gray-500 mb-4">New customer registrations (last 6 months)</p>
        @if($customerGrowth->count())
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3">
            @foreach($customerGrowth as $cg)
            @php
                $maxCount = $customerGrowth->max('count') ?: 1;
                $heightPercent = ($cg->count / $maxCount) * 100;
            @endphp
            <div class="text-center">
                <div class="h-32 flex items-end justify-center mb-2">
                    <div class="w-10 rounded-t-lg transition-all" style="height: {{ max(10, $heightPercent) }}%; background-color: #B08840;"></div>
                </div>
                <p class="text-lg font-bold text-gray-900">{{ $cg->count }}</p>
                <p class="text-[10px] text-gray-400 uppercase">{{ \Carbon\Carbon::parse($cg->month . '-01')->format('M Y') }}</p>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-8">
            <p class="text-sm text-gray-400">No customer data in the last 6 months</p>
        </div>
        @endif
    </div>

    <!-- This Month vs Last Month -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Month Comparison</h3>
        <div class="grid sm:grid-cols-3 gap-6">
            <div class="text-center p-4 bg-gray-50 rounded-xl">
                <p class="text-xs text-gray-500 mb-1">This Month Revenue</p>
                <p class="text-xl font-bold text-gray-900">₹{{ number_format($stats['this_month_revenue']) }}</p>
            </div>
            <div class="text-center p-4 bg-gray-50 rounded-xl">
                <p class="text-xs text-gray-500 mb-1">Last Month Revenue</p>
                <p class="text-xl font-bold text-gray-900">₹{{ number_format($stats['last_month_revenue']) }}</p>
            </div>
            <div class="text-center p-4 bg-gray-50 rounded-xl">
                <p class="text-xs text-gray-500 mb-1">Growth</p>
                <p class="text-xl font-bold {{ $stats['revenue_growth'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $stats['revenue_growth'] >= 0 ? '+' : '' }}{{ $stats['revenue_growth'] }}%
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
var revenueChart = null;
var initialData = @json($revenueData);

document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() { renderChart(initialData); }, 500);
});

function loadChart(period) {
    // Hide custom date range
    document.getElementById('custom-date-range').classList.add('hidden');
    document.getElementById('custom-date-range').classList.remove('flex');

    // Update button styles
    ['daily','weekly','monthly','custom'].forEach(function(p) {
        var btn = document.getElementById('btn-' + p);
        if (p === period) {
            btn.className = 'px-3 py-1.5 text-xs rounded-md transition bg-white shadow-sm text-gray-900 font-semibold';
        } else {
            btn.className = 'px-3 py-1.5 text-xs rounded-md transition text-gray-500 hover:text-gray-700';
        }
    });
    fetch('/admin/reports/revenue-chart?period=' + period, { headers: { 'Accept': 'application/json' }, credentials: 'same-origin'})
    .then(function(r) { return r.json(); })
    .then(function(data) { renderChart(data); });
}

function showCustomRange() {
    // Show date range inputs
    document.getElementById('custom-date-range').classList.remove('hidden');
    document.getElementById('custom-date-range').classList.add('flex');

    // Update button styles
    ['daily','weekly','monthly','custom'].forEach(function(p) {
        var btn = document.getElementById('btn-' + p);
        if (p === 'custom') {
            btn.className = 'px-3 py-1.5 text-xs rounded-md transition bg-white shadow-sm text-gray-900 font-semibold';
        } else {
            btn.className = 'px-3 py-1.5 text-xs rounded-md transition text-gray-500 hover:text-gray-700';
        }
    });

    // Set default dates (last 30 days)
    var today = new Date();
    var thirtyDaysAgo = new Date(today);
    thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);
    document.getElementById('end-date').value = today.toISOString().split('T')[0];
    document.getElementById('start-date').value = thirtyDaysAgo.toISOString().split('T')[0];
}

function loadCustomChart() {
    var startDate = document.getElementById('start-date').value;
    var endDate = document.getElementById('end-date').value;
    if (!startDate || !endDate) { alert('Please select both dates'); return; }
    if (startDate > endDate) { alert('Start date must be before end date'); return; }

    fetch('/admin/reports/revenue-chart?period=custom&start_date=' + startDate + '&end_date=' + endDate, { headers: { 'Accept': 'application/json' }, credentials: 'same-origin'})
    .then(function(r) { return r.json(); })
    .then(function(data) { renderChart(data); });
}

function renderChart(data) {
    if (typeof Chart === 'undefined') { setTimeout(function() { renderChart(data); }, 300); return; }
    if (revenueChart) revenueChart.destroy();
    var ctx = document.getElementById('revenueCanvas');
    if (!ctx) return;
    revenueChart = new Chart(ctx.getContext('2d'), {
        type: 'bar',
        data: {
            labels: data.labels.map(function(l) {
                if (l.includes('-W')) return 'W' + l.split('-W')[1];
                if (l.length === 7) { var d = new Date(l + '-01'); return d.toLocaleDateString('en-IN', {month:'short', year:'2-digit'}); }
                if (l.length === 10) { var d = new Date(l); return d.toLocaleDateString('en-IN', {day:'numeric', month:'short'}); }
                return l;
            }),
            datasets: [
                { label: 'Revenue', data: data.revenue, backgroundColor: 'rgba(192,109,34,0.7)', borderColor: '#c06d22', borderWidth: 1, borderRadius: 6, yAxisID: 'y' },
                { label: 'Orders', data: data.orders, type: 'line', borderColor: '#B08840', backgroundColor: 'rgba(176,136,64,0.1)', borderWidth: 2, pointRadius: 4, pointBackgroundColor: '#B08840', fill: true, tension: 0.3, yAxisID: 'y1' }
            ]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: { legend: { display: false } },
            scales: {
                y: { type: 'linear', position: 'left', grid: { color: '#f3f4f6' }, ticks: { callback: function(v) { return '₹' + (v >= 1000 ? (v/1000).toFixed(0) + 'k' : v); } } },
                y1: { type: 'linear', position: 'right', grid: { display: false }, ticks: { stepSize: 1 } },
                x: { grid: { display: false } }
            }
        }
    });
}
</script>
@endsection
