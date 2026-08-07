@extends('layouts.admin')
@section('page_title', 'Dashboard')
@section('title', 'Dashboard - Shivara Admin')

@section('content')
<div class="space-y-6">

    <!-- Welcome + Today Stats -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name }}!</h1>
            <p class="text-sm text-gray-500">Here's what's happening with your store today.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.products.create') }}" class="px-4 py-2 text-white text-xs font-bold rounded-lg hover:opacity-90 transition" style="background-color:#c06d22">+ Add Product</a>
            <a href="{{ route('admin.reports.index') }}" class="px-4 py-2 text-xs font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition">View Reports</a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="{{ route('admin.reports.index') }}" class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition block">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background-color: rgba(16,185,129,0.1)">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <svg class="w-12 h-8 text-emerald-200" viewBox="0 0 48 32"><path d="M0 28 Q12 20 24 24 T48 16" fill="none" stroke="currentColor" stroke-width="2"/></svg>
            </div>
            <p class="text-2xl font-bold text-gray-900">₹{{ number_format($stats['total_revenue']) }}</p>
            <p class="text-xs text-gray-500 mt-1">Total Revenue</p>
        </a>

        <a href="{{ route('admin.orders.index') }}" class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition block">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background-color: rgba(59,130,246,0.1)">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
                @if($stats['pending_orders'] > 0)
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700">{{ $stats['pending_orders'] }} pending</span>
                @endif
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_orders']) }}</p>
            <p class="text-xs text-gray-500 mt-1">Total Orders</p>
        </a>

        <a href="{{ route('admin.products.index') }}" class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition block">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background-color: rgba(139,92,246,0.1)">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                @if($stats['low_stock'] > 0)
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-700">{{ $stats['low_stock'] }} low</span>
                @endif
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_products']) }}</p>
            <p class="text-xs text-gray-500 mt-1">Products</p>
        </a>

        <a href="{{ route('admin.customers.index') }}" class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition block">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background-color: rgba(245,158,11,0.1)">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_customers']) }}</p>
            <p class="text-xs text-gray-500 mt-1">Customers</p>
        </a>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">

        <!-- Recent Orders -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-gray-900 text-sm">Recent Orders</h3>
                    <p class="text-[11px] text-gray-400">Latest 10 orders</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold hover:underline" style="color:#c06d22">View All →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead><tr class="border-b border-gray-50 bg-gray-50/50">
                        <th class="text-left text-[10px] font-semibold text-gray-500 uppercase tracking-wider px-5 py-2.5">Order</th>
                        <th class="text-left text-[10px] font-semibold text-gray-500 uppercase tracking-wider px-5 py-2.5">Customer</th>
                        <th class="text-left text-[10px] font-semibold text-gray-500 uppercase tracking-wider px-5 py-2.5">Amount</th>
                        <th class="text-left text-[10px] font-semibold text-gray-500 uppercase tracking-wider px-5 py-2.5">Status</th>
                        <th class="text-left text-[10px] font-semibold text-gray-500 uppercase tracking-wider px-5 py-2.5">Date</th>
                    </tr></thead>
                    <tbody>
                    @forelse($recentOrders as $order)
                    <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition">
                        <td class="px-5 py-3"><a href="{{ route('admin.orders.show', $order) }}" class="text-xs font-bold hover:underline" style="color:#c06d22">{{ $order->order_number }}</a></td>
                        <td class="px-5 py-3 text-xs text-gray-600">{{ $order->user->name ?? 'Guest' }}</td>
                        <td class="px-5 py-3 text-xs font-bold text-gray-900">₹{{ number_format($order->total_amount) }}</td>
                        <td class="px-5 py-3">
                            @php
                                $statusStyles = [
                                    'pending' => 'background-color:#FEF3C7;color:#92400E',
                                    'confirmed' => 'background-color:#DBEAFE;color:#1E40AF',
                                    'processing' => 'background-color:#E0E7FF;color:#3730A3',
                                    'shipped' => 'background-color:#EDE9FE;color:#5B21B6',
                                    'delivered' => 'background-color:#D1FAE5;color:#065F46',
                                    'cancelled' => 'background-color:#FEE2E2;color:#991B1B',
                                ];
                            @endphp
                            <span class="text-[9px] font-bold uppercase px-2 py-0.5 rounded-md" style="{{ $statusStyles[$order->status] ?? 'background-color:#F3F4F6;color:#374151' }}">{{ ucfirst($order->status) }}</span>
                        </td>
                        <td class="px-5 py-3 text-[11px] text-gray-400">{{ $order->created_at->format('d M, h:i A') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-5 py-10 text-center text-xs text-gray-400">No orders yet</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-5">

            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <h3 class="font-bold text-gray-900 text-sm mb-4">Quick Actions</h3>
                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('admin.products.create') }}" class="flex flex-col items-center gap-1.5 p-3 rounded-xl hover:bg-orange-50 transition text-center group">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center bg-orange-100 group-hover:bg-orange-200 transition">
                            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <span class="text-[10px] font-semibold text-gray-600">Add Product</span>
                    </a>
                    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="flex flex-col items-center gap-1.5 p-3 rounded-xl hover:bg-amber-50 transition text-center group">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center bg-amber-100 group-hover:bg-amber-200 transition">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-[10px] font-semibold text-gray-600">Pending ({{ $stats['pending_orders'] }})</span>
                    </a>
                    <a href="{{ route('admin.coupons.create') }}" class="flex flex-col items-center gap-1.5 p-3 rounded-xl hover:bg-purple-50 transition text-center group">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center bg-purple-100 group-hover:bg-purple-200 transition">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                        </div>
                        <span class="text-[10px] font-semibold text-gray-600">New Coupon</span>
                    </a>
                    <a href="{{ route('admin.reports.index') }}" class="flex flex-col items-center gap-1.5 p-3 rounded-xl hover:bg-blue-50 transition text-center group">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center bg-blue-100 group-hover:bg-blue-200 transition">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <span class="text-[10px] font-semibold text-gray-600">Reports</span>
                    </a>
                </div>
            </div>

            <!-- Low Stock Alerts -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-gray-900 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        Low Stock
                    </h3>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-red-50 text-red-700">{{ $stats['low_stock'] }}</span>
                </div>
                @forelse($lowStockProducts as $product)
                <div class="flex items-center justify-between py-2.5 border-b border-gray-50 last:border-0">
                    <div class="flex items-center gap-2 min-w-0">
                        <div class="w-7 h-7 bg-gray-100 rounded-md overflow-hidden shrink-0">
                            @if($product->primaryImage)
                            <img src="{{ str_starts_with($product->primaryImage->url, '/storage/') ? '/public' . $product->primaryImage->url : $product->primaryImage->url }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <span class="text-xs text-gray-700 truncate">{{ $product->name }}</span>
                    </div>
                    <span class="text-xs font-bold px-2 py-0.5 rounded-md shrink-0 {{ $product->stock <= 0 ? 'bg-red-50 text-red-700' : 'bg-amber-50 text-amber-700' }}">{{ $product->stock }}</span>
                </div>
                @empty
                <div class="text-center py-4">
                    <svg class="w-8 h-8 text-green-200 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <p class="text-xs text-gray-400">All stock levels good!</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
