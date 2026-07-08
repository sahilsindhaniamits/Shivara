@extends('layouts.admin')
@section('title', $customer->name . ' - Customer')
@section('page_title', 'Customer Details')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-full flex items-center justify-center text-xl font-bold text-white" style="background-color:#B7925C">{{ substr($customer->name, 0, 1) }}</div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $customer->name }}</h1>
                <p class="text-sm text-gray-500">{{ $customer->email }} &bull; Joined {{ $customer->created_at->format('d M Y') }}</p>
            </div>
        </div>
        <a href="{{ route('admin.customers.index') }}" class="px-4 py-2 border border-gray-200 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-50 transition">← Back</a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-2xl font-bold text-gray-900">₹{{ number_format($totalSpent) }}</p>
            <p class="text-xs text-gray-500 mt-1">Total Spent</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-2xl font-bold text-gray-900">{{ $customer->orders->count() }}</p>
            <p class="text-xs text-gray-500 mt-1">Orders</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-2xl font-bold text-gray-900">{{ $customer->phone ?? '-' }}</p>
            <p class="text-xs text-gray-500 mt-1">Phone</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-2xl font-bold text-gray-900">
                <span class="text-sm font-bold px-3 py-1 rounded-full {{ $customer->is_active ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">{{ $customer->is_active ? 'Active' : 'Blocked' }}</span>
            </p>
            <p class="text-xs text-gray-500 mt-1">Status</p>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-900">Recent Orders</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead><tr class="border-b border-gray-50 bg-gray-50/50">
                    <th class="text-left text-[11px] font-semibold text-gray-500 uppercase px-6 py-3">Order</th>
                    <th class="text-left text-[11px] font-semibold text-gray-500 uppercase px-6 py-3">Amount</th>
                    <th class="text-left text-[11px] font-semibold text-gray-500 uppercase px-6 py-3">Status</th>
                    <th class="text-left text-[11px] font-semibold text-gray-500 uppercase px-6 py-3">Date</th>
                </tr></thead>
                <tbody>
                @forelse($customer->orders as $order)
                <tr class="border-b border-gray-50 hover:bg-gray-50/50">
                    <td class="px-6 py-3"><a href="{{ route('admin.orders.show', $order) }}" class="text-sm font-bold hover:underline" style="color:#B7925C">{{ $order->order_number }}</a></td>
                    <td class="px-6 py-3 text-sm font-semibold">₹{{ number_format($order->total_amount) }}</td>
                    <td class="px-6 py-3"><span class="text-[10px] font-bold uppercase px-2 py-1 rounded-md bg-gray-100 text-gray-600">{{ ucfirst($order->status) }}</span></td>
                    <td class="px-6 py-3 text-xs text-gray-500">{{ $order->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-6 py-8 text-center text-gray-400 text-sm">No orders yet</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Addresses -->
    @if($customer->addresses && $customer->addresses->count())
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <h3 class="font-bold text-gray-900 mb-4">Saved Addresses</h3>
        <div class="grid md:grid-cols-2 gap-4">
            @foreach($customer->addresses as $addr)
            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                <p class="text-sm font-semibold text-gray-800">{{ $addr->full_name }}</p>
                <p class="text-xs text-gray-600 mt-1">{{ $addr->address_line1 }}</p>
                <p class="text-xs text-gray-600">{{ $addr->city }}, {{ $addr->state }} - {{ $addr->pincode }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $addr->phone }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
