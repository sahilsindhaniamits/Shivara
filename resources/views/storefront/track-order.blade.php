@extends('layouts.app')
@section('title', 'Track Order - Shivara')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 py-12">
    <div class="text-center mb-10">
        <h1 class="font-display text-3xl md:text-4xl font-bold text-espresso-700">Track Your Order</h1>
        <p class="text-espresso-400 mt-2">Enter your order number to check delivery status</p>
    </div>

    <!-- Search Form -->
    <form method="GET" action="{{ route('track.order') }}" class="space-y-3 mb-10">
        <div class="flex flex-col sm:flex-row gap-3">
            <input type="text" name="order_number" value="{{ request('order_number') }}" required placeholder="Order Number (e.g. SHV-XXXXXX-XX)" class="flex-1 px-5 py-4 bg-white border border-gold-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-gold-200 focus:border-gold-300 placeholder:text-espresso-300">
            <input type="text" name="phone" value="{{ request('phone') }}" required placeholder="Phone Number" class="sm:w-48 px-5 py-4 bg-white border border-gold-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-gold-200 focus:border-gold-300 placeholder:text-espresso-300">
            <button type="submit" class="px-8 py-4 text-white font-bold text-sm uppercase tracking-wider rounded-xl hover:opacity-90 transition" style="background-color:#2C2418">Track</button>
        </div>
        <p class="text-[10px] text-espresso-300 text-center">Enter your order number and the phone number used during checkout</p>
    </form>

    @if(isset($order))
    <!-- Order Found -->
    <div class="space-y-6">
        <!-- Order Status Card -->
        <div class="bg-white p-6 md:p-8 rounded-3xl border border-gold-100 shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 pb-6 border-b border-gray-100">
                <div>
                    <p class="text-xs text-espresso-400">Order Number</p>
                    <p class="text-lg font-bold text-espresso-700">{{ $order->order_number }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-espresso-400">Placed on</p>
                    <p class="text-sm font-semibold text-espresso-700">{{ $order->created_at->format('d M Y, h:i A') }}</p>
                </div>
            </div>

            <!-- Status Progress -->
            @php
                $statuses = ['pending', 'confirmed', 'processing', 'shipped', 'out_for_delivery', 'delivered'];
                $currentIndex = array_search($order->status, $statuses);
                if ($order->status === 'cancelled') $currentIndex = -1;
            @endphp

            @if($order->status !== 'cancelled')
            <div class="mb-8">
                <div class="flex items-center justify-between mb-2">
                    @foreach($statuses as $i => $s)
                    <div class="flex flex-col items-center flex-1">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold {{ $i <= $currentIndex ? 'text-white' : 'bg-gray-100 text-gray-400' }}" @if($i <= $currentIndex) style="background-color:#16a34a" @endif>
                            @if($i < $currentIndex)
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            @elseif($i == $currentIndex)
                            <div class="w-2.5 h-2.5 bg-white rounded-full"></div>
                            @else
                            {{ $i + 1 }}
                            @endif
                        </div>
                        <p class="text-[9px] font-semibold text-center mt-1.5 {{ $i <= $currentIndex ? 'text-green-700' : 'text-gray-400' }}">{{ ucfirst(str_replace('_',' ',$s)) }}</p>
                    </div>
                    @if($i < count($statuses) - 1)
                    <div class="flex-1 h-1 rounded {{ $i < $currentIndex ? 'bg-green-500' : 'bg-gray-200' }} mx-1 mt-[-20px]"></div>
                    @endif
                    @endforeach
                </div>
            </div>
            @else
            <div class="mb-6 p-4 bg-red-50 rounded-xl border border-red-200 text-center">
                <p class="text-sm font-bold text-red-700">This order has been cancelled.</p>
                @if($order->cancelled_at)<p class="text-xs text-red-500 mt-1">Cancelled on {{ $order->cancelled_at->format('d M Y') }}</p>@endif
            </div>
            @endif

            <!-- Tracking Info -->
            @if($order->tracking_number)
            <div class="p-4 rounded-xl border border-green-200" style="background-color: #f0fdf4;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-green-800 uppercase">Shipping via {{ $order->courier_name ?? 'Courier' }}</p>
                        <p class="text-sm font-semibold text-green-700 mt-0.5">Tracking: {{ $order->tracking_number }}</p>
                    </div>
                    @if($order->tracking_url)
                    <a href="{{ $order->tracking_url }}" target="_blank" class="px-4 py-2 text-xs font-bold text-white rounded-lg hover:opacity-90 transition" style="background-color:#16a34a">Track Live →</a>
                    @endif
                </div>
            </div>
            @endif

            <!-- Order Items -->
            <div class="mt-6 pt-6 border-t border-gray-100">
                <h3 class="text-sm font-bold text-espresso-700 mb-3">Items in this order</h3>
                <div class="space-y-3">
                    @foreach($order->items as $item)
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-cream-100 rounded-lg flex items-center justify-center text-xs">📦</div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-espresso-700">{{ $item->product_name }}</p>
                            @if($item->variant_name)<p class="text-[10px] text-espresso-400">{{ $item->variant_name }}</p>@endif
                        </div>
                        <p class="text-sm font-semibold">₹{{ number_format($item->total_price) }}</p>
                    </div>
                    @endforeach
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between">
                    <span class="text-sm font-bold text-espresso-700">Total</span>
                    <span class="text-sm font-bold text-espresso-700">₹{{ number_format($order->total_amount) }}</span>
                </div>
            </div>
        </div>
    </div>

    @elseif(request('order_number'))
    <!-- Order Not Found -->
    <div class="text-center py-12 bg-white rounded-2xl border border-gold-100">
        <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p class="text-lg font-semibold text-espresso-700">Order not found</p>
        <p class="text-sm text-espresso-400 mt-1">Please check the order number and try again.</p>
    </div>
    @endif
</div>
@endsection
