@extends('layouts.app')
@section('title', 'My Account - Shivara')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    <div class="flex flex-col md:flex-row gap-8">
        @include('storefront.account._sidebar')

        <div class="flex-1 min-w-0">
            <div class="mb-8">
                <h1 class="text-2xl font-display font-bold text-gray-900">Welcome, {{ $user->name }}!</h1>
                <p class="text-sm text-gray-500 mt-1">Here's an overview of your account.</p>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-white p-5 rounded-2xl border border-gray-100">
                    <p class="text-2xl font-bold text-gray-900">{{ $user->orders->count() }}</p>
                    <p class="text-xs text-gray-500 mt-1">Total Orders</p>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-gray-100">
                    <p class="text-2xl font-bold text-gray-900">{{ $addressCount }}</p>
                    <p class="text-xs text-gray-500 mt-1">Saved Addresses</p>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-gray-100">
                    <p class="text-2xl font-bold text-gray-900">{{ $wishlistCount }}</p>
                    <p class="text-xs text-gray-500 mt-1">Wishlist Items</p>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="bg-white rounded-2xl border border-gray-100">
                <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="font-bold text-gray-900">Recent Orders</h2>
                    <a href="{{ route('account.orders') }}" class="text-sm text-brand-600 font-medium hover:underline">View All</a>
                </div>
                @forelse($recentOrders as $order)
                <a href="{{ route('account.orders.show', $order->order_number) }}" class="block p-5 border-b border-gray-50 last:border-0 hover:bg-gray-50 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ $order->order_number }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $order->created_at->format('M d, Y') }} • {{ $order->items->count() }} item(s)</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-gray-900">₹{{ number_format($order->total_amount) }}</p>
                            @php $colors = ['pending'=>'yellow','confirmed'=>'blue','processing'=>'indigo','shipped'=>'purple','delivered'=>'green','cancelled'=>'red']; @endphp
                            <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded-full bg-{{ $colors[$order->status] ?? 'gray' }}-100 text-{{ $colors[$order->status] ?? 'gray' }}-700">{{ ucfirst($order->status) }}</span>
                        </div>
                    </div>
                </a>
                @empty
                <div class="p-10 text-center">
                    <p class="text-gray-400">No orders yet.</p>
                    <a href="{{ route('products.index') }}" class="text-sm text-brand-600 font-medium mt-2 inline-block">Start Shopping →</a>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
