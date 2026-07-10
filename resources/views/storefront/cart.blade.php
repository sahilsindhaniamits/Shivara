@extends('layouts.app')
@section('title', 'Shopping Cart - Shivara')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    @if($cartItems->isEmpty())
    <!-- Empty Cart -->
    <div class="max-w-md mx-auto py-20 text-center">
        <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
        </div>
        <h1 class="text-2xl font-display font-bold text-gray-900 mb-2">Your cart is empty</h1>
        <p class="text-gray-500 mb-8">Looks like you haven't added any products yet.</p>
        <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-8 py-3.5 bg-dark text-white font-semibold rounded-full hover:bg-dark-light transition">
            Start Shopping
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
    </div>
    @else
    <!-- Cart Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-display font-bold text-gray-900">Shopping Cart</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $cartItems->sum('quantity') }} item(s)</p>
        </div>
        <form method="POST" action="{{ route('cart.clear') }}">
            @csrf @method('DELETE')
            <button type="submit" class="text-sm text-red-500 hover:text-red-600 font-medium transition">Clear All</button>
        </form>
    </div>

    <div class="grid lg:grid-cols-3 gap-8">
        <!-- Cart Items -->
        <div class="lg:col-span-2 space-y-4">
            @foreach($cartItems as $item)
            @php
                $price = $item->variant ? $item->variant->selling_price : $item->product->selling_price;
                $mrp = $item->variant ? $item->variant->mrp : $item->product->mrp;
            @endphp
            <div class="bg-white p-4 md:p-5 rounded-2xl border border-gray-100 flex gap-4 shadow-sm">
                <!-- Image -->
                <a href="{{ route('products.show', $item->product->slug) }}" class="w-20 h-20 md:w-24 md:h-24 bg-gray-50 rounded-xl overflow-hidden shrink-0">
                    @if($item->product->primaryImage)
                    <img src="{{ str_starts_with($item->product->primaryImage->url, '/storage/') ? '/public' . $item->product->primaryImage->url : $item->product->primaryImage->url }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    @endif
                </a>

                <!-- Details -->
                <div class="flex-1 min-w-0">
                    <div class="flex justify-between gap-2">
                        <div>
                            <a href="{{ route('products.show', $item->product->slug) }}" class="text-sm md:text-base font-semibold text-gray-900 line-clamp-2 hover:text-brand-700 transition">{{ $item->product->name }}</a>
                            @if($item->variant)<p class="text-xs text-gray-500 mt-0.5">{{ $item->variant->name }}</p>@endif
                        </div>
                        <form method="POST" action="{{ route('cart.remove') }}">
                            @csrf @method('DELETE')
                            <input type="hidden" name="item_id" value="{{ $item->id }}">
                            <button type="submit" class="p-1 text-gray-300 hover:text-red-500 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </form>
                    </div>

                    <div class="flex items-end justify-between mt-3">
                        <!-- Quantity -->
                        <div class="flex items-center border border-gray-200 rounded-lg">
                            <form method="POST" action="{{ route('cart.update') }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="item_id" value="{{ $item->id }}">
                                <input type="hidden" name="quantity" value="{{ max(1, $item->quantity - 1) }}">
                                <button type="submit" class="px-3 py-2 text-gray-500 hover:text-gray-900 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                                </button>
                            </form>
                            <span class="px-3 py-2 text-sm font-bold text-gray-900 min-w-[32px] text-center">{{ $item->quantity }}</span>
                            <form method="POST" action="{{ route('cart.update') }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="item_id" value="{{ $item->id }}">
                                <input type="hidden" name="quantity" value="{{ $item->quantity + 1 }}">
                                <button type="submit" class="px-3 py-2 text-gray-500 hover:text-gray-900 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                </button>
                            </form>
                        </div>

                        <!-- Price -->
                        <div class="text-right">
                            <p class="text-base font-bold text-gray-900">₹{{ number_format($price * $item->quantity) }}</p>
                            @if($mrp > $price)
                            <p class="text-xs text-gray-400 line-through">₹{{ number_format($mrp * $item->quantity) }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-brand-700 hover:text-brand-800 mt-4 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/></svg>
                Continue Shopping
            </a>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm sticky top-24">
                <h2 class="text-lg font-bold text-gray-900 mb-5">Order Summary</h2>

                <!-- Coupon -->
                <form method="POST" action="{{ route('cart.coupon') }}" class="mb-6">
                    @csrf
                    <div class="flex gap-2">
                        <input type="text" name="code" placeholder="Coupon code" value="{{ session('coupon.code', '') }}" class="flex-1 px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 placeholder:text-gray-400">
                        <button type="submit" class="px-4 py-2.5 bg-dark text-white text-xs font-bold uppercase rounded-lg hover:bg-dark-light transition">Apply</button>
                    </div>
                </form>

                @php
                    $subtotal = $cartItems->sum(function($item) { return ($item->variant ? $item->variant->selling_price : $item->product->selling_price) * $item->quantity; });
                    $totalMRP = $cartItems->sum(function($item) { return ($item->variant ? $item->variant->mrp : $item->product->mrp) * $item->quantity; });
                    $discount = $totalMRP - $subtotal;
                    $shipping = $subtotal >= config('shivara.free_shipping_threshold') ? 0 : config('shivara.standard_rate');
                    $total = $subtotal + $shipping;
                @endphp

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between text-gray-600"><span>Subtotal (MRP)</span><span>₹{{ number_format($totalMRP) }}</span></div>
                    @if($discount > 0)
                    <div class="flex justify-between text-green-600"><span>Discount</span><span>-₹{{ number_format($discount) }}</span></div>
                    @endif
                    <div class="flex justify-between text-gray-600"><span>Shipping</span><span>{!! $shipping === 0 ? '<span class="text-green-600 font-semibold">FREE</span>' : '₹'.number_format($shipping) !!}</span></div>
                    <hr class="border-gray-100">
                    <div class="flex justify-between text-lg font-bold text-gray-900 pt-1"><span>Total</span><span>₹{{ number_format($total) }}</span></div>
                </div>

                @if($discount > 0)
                <div class="mt-4 p-3 bg-green-50 rounded-xl text-center">
                    <p class="text-sm text-green-700 font-medium">You're saving ₹{{ number_format($discount) }}!</p>
                </div>
                @endif

                <a href="{{ route('checkout.index') }}" class="block mt-6 w-full px-8 py-3.5 bg-brand-600 text-white font-semibold rounded-xl hover:bg-brand-700 transition text-center shadow-lg shadow-brand-600/20">
                    Proceed to Checkout
                </a>

                <p class="text-[11px] text-gray-400 text-center mt-3">Secure payments by Razorpay</p>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
