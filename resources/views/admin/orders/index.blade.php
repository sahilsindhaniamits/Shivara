@extends('layouts.admin')
@section('title', 'Orders - Admin')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Orders</h1>
        <p class="text-sm text-gray-500">Manage customer orders</p>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-2xl border border-gray-200">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-col sm:flex-row gap-4">
            <div class="relative flex-1">
                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by order number or customer..." class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>
            <select name="status" class="px-3 py-2.5 border border-gray-200 rounded-xl text-sm">
                <option value="">All Status</option>
                @foreach(['pending','confirmed','processing','shipped','delivered','cancelled'] as $status)
                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2.5 bg-gray-100 text-gray-700 text-sm rounded-xl hover:bg-gray-200 transition">Filter</button>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50">
                        <th class="text-left text-xs font-medium text-gray-500 uppercase px-6 py-3">Order</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase px-6 py-3">Customer</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase px-6 py-3">Amount</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase px-6 py-3">Payment</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase px-6 py-3">Status</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase px-6 py-3">Date</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="px-6 py-4"><a href="{{ route('admin.orders.show', $order) }}" class="text-sm font-medium text-primary">{{ $order->order_number }}</a></td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $order->user->name ?? 'Guest' }}</td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">₹{{ number_format($order->total_amount) }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $order->payment_status == 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">{{ ucfirst($order->payment_status) }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @php $colors = ['pending'=>'yellow','confirmed'=>'blue','processing'=>'indigo','shipped'=>'purple','delivered'=>'green','cancelled'=>'red']; @endphp
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-{{ $colors[$order->status] ?? 'gray' }}-100 text-{{ $colors[$order->status] ?? 'gray' }}-700">{{ ucfirst($order->status) }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-gray-400 hover:text-primary transition"><i data-lucide="eye" class="w-4 h-4"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-6 py-12 text-center text-gray-400">No orders found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">{{ $orders->links() }}</div>
    </div>
</div>
@endsection
