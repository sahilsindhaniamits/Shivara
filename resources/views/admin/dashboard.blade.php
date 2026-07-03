@extends('layouts.admin')
@section('title', 'Dashboard - Shivara Admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-sm text-gray-500">Welcome back! Here's what's happening today.</p>
        </div>
        <div class="text-sm text-gray-500">{{ now()->format('l, F j, Y') }}</div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-6 rounded-2xl border border-gray-200 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-green-50 text-green-600">
                    <i data-lucide="indian-rupee" class="w-5 h-5"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900">₹{{ number_format($stats['total_revenue']) }}</p>
            <p class="text-sm text-gray-500 mt-1">Total Revenue</p>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-200 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-blue-50 text-blue-600">
                    <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_orders'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Total Orders</p>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-200 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-purple-50 text-purple-600">
                    <i data-lucide="package" class="w-5 h-5"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_products'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Products</p>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-200 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-orange-50 text-orange-600">
                    <i data-lucide="users" class="w-5 h-5"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_customers']) }}</p>
            <p class="text-sm text-gray-500 mt-1">Customers</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Recent Orders -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-bold text-gray-900">Recent Orders</h2>
                <a href="{{ route('admin.orders.index') }}" class="text-sm text-primary font-medium hover:underline flex items-center gap-1">View All <i data-lucide="arrow-right" class="w-3 h-3"></i></a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="text-left text-xs font-medium text-gray-500 uppercase px-6 py-3">Order</th>
                            <th class="text-left text-xs font-medium text-gray-500 uppercase px-6 py-3">Customer</th>
                            <th class="text-left text-xs font-medium text-gray-500 uppercase px-6 py-3">Amount</th>
                            <th class="text-left text-xs font-medium text-gray-500 uppercase px-6 py-3">Status</th>
                            <th class="text-left text-xs font-medium text-gray-500 uppercase px-6 py-3">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-6 py-4"><a href="{{ route('admin.orders.show', $order) }}" class="text-sm font-medium text-primary">{{ $order->order_number }}</a></td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $order->user->name ?? 'Guest' }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">₹{{ number_format($order->total_amount) }}</td>
                            <td class="px-6 py-4">
                                @php $statusColors = ['pending'=>'yellow','confirmed'=>'blue','processing'=>'indigo','shipped'=>'purple','delivered'=>'green','cancelled'=>'red']; @endphp
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-{{ $statusColors[$order->status] ?? 'gray' }}-100 text-{{ $statusColors[$order->status] ?? 'gray' }}-700">{{ ucfirst($order->status) }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $order->created_at->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">No orders yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <div class="flex items-center gap-2 mb-4">
                <i data-lucide="alert-triangle" class="w-5 h-5 text-orange-500"></i>
                <h3 class="font-bold text-gray-900">Low Stock Alert</h3>
            </div>
            @forelse($lowStockProducts as $product)
            <div class="flex items-center justify-between p-3 bg-orange-50 rounded-xl mb-2">
                <span class="text-sm font-medium text-gray-700 truncate">{{ $product->name }}</span>
                <span class="px-2 py-0.5 bg-red-100 text-red-700 text-xs font-medium rounded-full">{{ $product->stock }} left</span>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-4">All products well-stocked!</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
