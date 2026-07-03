@extends('layouts.admin')
@section('page_title', 'Dashboard')
@section('title', 'Dashboard - Shivara Admin')

@section('content')
<div class="space-y-6">
    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-100 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-slate-900">₹{{ number_format($stats['total_revenue']) }}</p>
            <p class="text-xs text-slate-500 mt-1">Total Revenue</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-slate-900">{{ $stats['total_orders'] }}</p>
            <p class="text-xs text-slate-500 mt-1">Total Orders</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-slate-900">{{ $stats['total_products'] }}</p>
            <p class="text-xs text-slate-500 mt-1">Products</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-slate-900">{{ number_format($stats['total_customers']) }}</p>
            <p class="text-xs text-slate-500 mt-1">Customers</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Recent Orders -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-slate-900">Recent Orders</h3>
                <a href="{{ route('admin.orders.index') }}" class="text-xs text-brand-600 font-semibold hover:underline">View All →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead><tr class="border-b border-slate-50 bg-slate-50/50">
                        <th class="text-left text-[11px] font-semibold text-slate-500 uppercase tracking-wider px-5 py-3">Order</th>
                        <th class="text-left text-[11px] font-semibold text-slate-500 uppercase tracking-wider px-5 py-3">Customer</th>
                        <th class="text-left text-[11px] font-semibold text-slate-500 uppercase tracking-wider px-5 py-3">Amount</th>
                        <th class="text-left text-[11px] font-semibold text-slate-500 uppercase tracking-wider px-5 py-3">Status</th>
                    </tr></thead>
                    <tbody>
                    @forelse($recentOrders as $order)
                    <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition">
                        <td class="px-5 py-3.5"><a href="{{ route('admin.orders.show', $order) }}" class="text-sm font-semibold text-brand-600 hover:underline">{{ $order->order_number }}</a></td>
                        <td class="px-5 py-3.5 text-sm text-slate-600">{{ $order->user->name ?? '-' }}</td>
                        <td class="px-5 py-3.5 text-sm font-semibold text-slate-900">₹{{ number_format($order->total_amount) }}</td>
                        <td class="px-5 py-3.5">
                            @php $c = ['pending'=>'amber','confirmed'=>'blue','processing'=>'indigo','shipped'=>'violet','delivered'=>'emerald','cancelled'=>'red']; @endphp
                            <span class="text-[10px] font-bold uppercase px-2 py-1 rounded-md bg-{{ $c[$order->status] ?? 'slate' }}-50 text-{{ $c[$order->status] ?? 'slate' }}-700">{{ ucfirst($order->status) }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-5 py-10 text-center text-slate-400 text-sm">No orders yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Alerts -->
        <div class="space-y-4">
            <div class="bg-white rounded-2xl border border-slate-100 p-5">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <h3 class="font-bold text-slate-900 text-sm">Low Stock ({{ $stats['low_stock'] }})</h3>
                </div>
                @forelse($lowStockProducts as $product)
                <div class="flex items-center justify-between py-2.5 border-b border-slate-50 last:border-0">
                    <span class="text-sm text-slate-700 truncate pr-2">{{ $product->name }}</span>
                    <span class="text-xs font-bold px-2 py-0.5 rounded-md {{ $product->stock <= 0 ? 'bg-red-50 text-red-700' : 'bg-amber-50 text-amber-700' }}">{{ $product->stock }}</span>
                </div>
                @empty
                <p class="text-sm text-slate-400 text-center py-4">All good! ✓</p>
                @endforelse
            </div>
            <div class="bg-white rounded-2xl border border-slate-100 p-5">
                <h3 class="font-bold text-slate-900 text-sm mb-3">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.products.create') }}" class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm text-slate-600 hover:bg-slate-50 transition"><svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>Add Product</a>
                    <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm text-slate-600 hover:bg-slate-50 transition"><svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>Pending Orders ({{ $stats['pending_orders'] }})</a>
                    <a href="{{ route('admin.coupons.create') }}" class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm text-slate-600 hover:bg-slate-50 transition"><svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>New Coupon</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
