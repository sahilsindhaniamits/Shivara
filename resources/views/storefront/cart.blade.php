@extends('layouts.app')
@section('title', 'Cart - Shivara')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    @if($cartItems->isEmpty())
    <div class="max-w-4xl mx-auto py-20 text-center">
        <div class="w-24 h-24 bg-accent rounded-full flex items-center justify-center mx-auto mb-6">
            <i data-lucide="shopping-bag" class="w-10 h-10 text-primary/50"></i>
        </div>
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Your cart is empty</h1>
        <p class="text-gray-500 mb-8">Looks like you haven't added any products yet.</p>
        <a href="{{ route('products.index') }}" class="px-8 py-3 bg-primary text-white font-medium rounded-xl hover:bg-primary-dark transition inline-block">Continue Shopping</a>
    </div>
    @else
    <h1 class="text-2xl font-bold text-gray-900 mb-2">Shopping Cart</h1>
    <p class="text-gray-500 mb-8">{{ $cartItems->sum('quantity') }} item(s) in your cart</p>

    <div class="grid lg:grid-cols-3 gap-8">
        <!-- Cart Items -->
        <div class="lg:col-span-2 space-y-4">
            @foreach($cartItems as $item)
            @php
                $price = $item->variant ? $item->variant->selling_price : $item->product->selling_price;
                $mrp = $item->variant ? $item->variant->mrp : $item->product->mrp;
            @endphp
            <div class="bg-white p-4 md:p-6 rounded-2xl border border-border flex gap-4">
                <div class="w-20 h-20 md:w-24 md:h-24 bg-accent/50 rounded-xl overflow-hidden shrink-0">
                    @if($item->product->primaryImage)
                    <img src="{{ $item->product->primaryImage->url }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full flex items-center justify-center"><span class="text-3xl">🌿</span></div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex justify-between">
                        <div>
                            <h3 class="text-sm md:text-base font-medium text-gray-800 line-clamp-2">{{ $item->product->name }}</h3>
                            @if($item->variant)
                            <p class="text-xs text-gray-500 mt-0.5">{{ $item->variant->name }}</p>
                            @endif
                        </div>
                        <form method="POST" action="{{ route('cart.remove') }}">
                            @csrf @method('DELETE')
                            <input type="hidden" name="item_id" value="{{ $item->id }}">
                            <button type="submit" class="text-gray-400 hover:text-red-500 transition p-1">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </div>
                    <div class="flex items-end justify-between mt-3">
                        <form method="POST" action="{{ route('cart.update') }}" class="flex items-center border border-border rounded-lg">
                            @csrf @method('PATCH')
                            <input type="hidden" name="item_id" value="{{ $item->id }}">
                            <button type="submit" name="quantity" value="{{ max(1, $item->quantity - 1) }}" class="px-3 py-1.5 text-gray-500 hover:text-primary">
                                <i data-lucide="minus" class="w-3 h-3"></i>
                            </button>
                            <span class="px-3 py-1.5 text-sm font-semibold">{{ $item->quantity }}</span>
                            <button type="submit" name="quantity" value="{{ $item->quantity + 1 }}" class="px-3 py-1.5 text-gray-500 hover:text-primary">
                                <i data-lucide="plus" class="w-3 h-3"></i>
                            </button>
                        </form>
                        <div class="text-right">
                            <p class="text-lg font-bold text-primary">₹{{ number_format($price * $item->quantity) }}</p>
                            @if($mrp > $price)
                            <p class="text-xs text-gray-400 line-through">₹{{ number_format($mrp * $item->quantity) }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <div class="flex justify-between items-center pt-4">
                <a href="{{ route('products.index') }}" class="text-sm text-primary font-medium hover:underline">← Continue Shopping</a>
                <form method="POST" action="{{ route('cart.clear') }}">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-sm text-red-500 font-medium hover:underline">Clear Cart</button>
                </form>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-2xl border border-border sticky top-40">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Order Summary</h2>

                <!-- Coupon -->
                <form method="POST" action="{{ route('cart.coupon') }}" class="mb-6">
                    @csrf
                    <div class="flex gap-2">
                        <input type="text" name="code" placeholder="Coupon code" value="{{ session('coupon.code', '') }}" class="flex-1 px-4 py-2.5 border border-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/20">
                        <button type="submit" class="px-4 py-2.5 border border-secondary text-secondary text-sm rounded-lg hover:bg-secondary hover:text-white transition">Apply</button>
                    </div>
                </form>

                @php
                    $subtotal = $cartItems->sum(function($item) {
                        $p = $item->variant ? $item->variant->selling_price : $item->product->selling_price;
                        return $p * $item->quantity;
                    });
                    $totalMRP = $cartItems->sum(function($item) {
                        $m = $item->variant ? $item->variant->mrp : $item->product->mrp;
                        return $m * $item->quantity;
                    });
                    $discount = $totalMRP - $subtotal;
                    $shipping = $subtotal >= config('shivara.free_shipping_threshold') ? 0 : config('shivara.standard_rate');
                    $total = $subtotal + $shipping;
                @endphp

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between text-gray-600"><span>Total MRP</span><span>₹{{ number_format($totalMRP) }}</span></div>
                    @if($discount > 0)
                    <div class="flex justify-between text-green-600"><span>Discount on MRP</span><span>-₹{{ number_format($discount) }}</span></div>
                    @endif
                    <div class="flex justify-between text-gray-600"><span>Shipping</span><span>{!! $shipping === 0 ? '<span class="text-green-600 font-medium">FREE</span>' : '₹'.number_format($shipping) !!}</span></div>
                    <hr class="border-border">
                    <div class="flex justify-between text-lg font-bold text-gray-900"><span>Total</span><span class="text-primary">₹{{ number_format($total) }}</span></div>
                </div>

                @if($discount > 0)
                <div class="mt-4 p-3 bg-green-50 rounded-xl text-center">
                    <p class="text-sm text-green-700 font-medium">🎉 You're saving ₹{{ number_format($discount) }} on this order!</p>
                </div>
                @endif

                <a href="{{ route('checkout.index') }}" class="block mt-6 w-full px-8 py-3 bg-primary text-white font-medium rounded-xl hover:bg-primary-dark transition text-center">
                    Proceed to Checkout →
                </a>

                <div class="mt-4 text-center">
                    <p class="text-xs text-gray-400">Secure payments powered by Razorpay</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
