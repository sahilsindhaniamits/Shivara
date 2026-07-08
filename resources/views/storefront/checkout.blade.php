@extends('layouts.app')
@section('title', 'Checkout - Shivara')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-8">
    <h1 class="text-2xl md:text-3xl font-display font-bold text-gray-900 mb-8">Checkout</h1>

    <form method="POST" action="{{ route('checkout.store') }}">
        @csrf
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Shipping Address -->
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 mb-5 flex items-center gap-2">
                        <span class="w-7 h-7 bg-dark text-white text-xs font-bold rounded-full flex items-center justify-center">1</span>
                        Delivery Address
                    </h2>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name *</label>
                            <input type="text" name="full_name" value="{{ old('full_name', auth()->user()->name) }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:border-brand-300 focus:bg-white transition">
                            @error('full_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Phone *</label>
                            <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:border-brand-300 focus:bg-white transition">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Email *</label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:border-brand-300 focus:bg-white transition">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Address *</label>
                            <input type="text" name="address_line1" value="{{ old('address_line1') }}" required placeholder="House no, Building, Street" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:border-brand-300 focus:bg-white transition placeholder:text-gray-400">
                        </div>
                        <div class="sm:col-span-2">
                            <input type="text" name="address_line2" value="{{ old('address_line2') }}" placeholder="Area, Colony, Landmark (optional)" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:border-brand-300 focus:bg-white transition placeholder:text-gray-400">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">City *</label>
                            <input type="text" name="city" value="{{ old('city') }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:border-brand-300 focus:bg-white transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">State *</label>
                            <select name="state" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:border-brand-300 focus:bg-white transition">
                                <option value="">Select State</option>
                                @foreach($indianStates as $state)
                                <option value="{{ $state }}" {{ old('state') == $state ? 'selected' : '' }}>{{ $state }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Pincode *</label>
                            <input type="text" name="pincode" value="{{ old('pincode') }}" maxlength="6" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:border-brand-300 focus:bg-white transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Landmark</label>
                            <input type="text" name="landmark" value="{{ old('landmark') }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:border-brand-300 focus:bg-white transition">
                        </div>
                    </div>
                </div>

                <!-- Shipping Method -->
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm" x-data="{ shipping: 'standard' }">
                    <h2 class="text-lg font-bold text-gray-900 mb-5 flex items-center gap-2">
                        <span class="w-7 h-7 bg-dark text-white text-xs font-bold rounded-full flex items-center justify-center">2</span>
                        Shipping Method
                    </h2>
                    <div class="space-y-3">
                        @php
                            $cartSubtotal = $cartItems->sum(function($item) {
                                $price = $item->variant ? $item->variant->selling_price : $item->product->selling_price;
                                return $price * $item->quantity;
                            });
                            $freeShipping = $cartSubtotal >= config('shivara.free_shipping_threshold', 299);
                        @endphp
                        <label @click="shipping = 'standard'" :class="shipping === 'standard' ? 'border-brand-500 bg-brand-50/50' : 'border-gray-200 hover:border-gray-300'" class="flex items-center justify-between p-4 rounded-xl border-2 cursor-pointer transition">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="shipping_method" value="standard" checked class="text-brand-600 focus:ring-brand-500">
                                <div><p class="font-semibold text-sm text-gray-800">Standard Delivery</p><p class="text-xs text-gray-500">{{ config('shivara.standard_days') }}</p></div>
                            </div>
                            @if($freeShipping)
                            <span class="font-bold text-sm text-green-600">FREE</span>
                            @else
                            <span class="font-bold text-sm text-gray-700">₹{{ config('shivara.standard_rate') }}</span>
                            @endif
                        </label>
                        <label @click="shipping = 'express'" :class="shipping === 'express' ? 'border-brand-500 bg-brand-50/50' : 'border-gray-200 hover:border-gray-300'" class="flex items-center justify-between p-4 rounded-xl border-2 cursor-pointer transition">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="shipping_method" value="express" class="text-brand-600 focus:ring-brand-500">
                                <div><p class="font-semibold text-sm text-gray-800">Express Delivery</p><p class="text-xs text-gray-500">{{ config('shivara.express_days') }}</p></div>
                            </div>
                            <span class="font-bold text-sm text-gray-700">₹{{ config('shivara.express_rate') }}</span>
                        </label>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm" x-data="{ payment: 'razorpay' }">
                    <h2 class="text-lg font-bold text-gray-900 mb-5 flex items-center gap-2">
                        <span class="w-7 h-7 bg-dark text-white text-xs font-bold rounded-full flex items-center justify-center">3</span>
                        Payment Method
                    </h2>
                    <div class="space-y-3">
                        <label @click="payment = 'razorpay'" :class="payment === 'razorpay' ? 'border-brand-500 bg-brand-50/50' : 'border-gray-200 hover:border-gray-300'" class="flex items-center justify-between p-4 rounded-xl border-2 cursor-pointer transition">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="payment_method" value="razorpay" checked class="text-brand-600 focus:ring-brand-500">
                                <div><p class="font-semibold text-sm text-gray-800">Pay Online</p><p class="text-xs text-gray-500">UPI, Cards, Net Banking, Wallets</p></div>
                            </div>
                            <span class="text-[10px] font-bold uppercase bg-green-100 text-green-700 px-2 py-1 rounded-md">Recommended</span>
                        </label>
                        <label @click="payment = 'cod'" :class="payment === 'cod' ? 'border-brand-500 bg-brand-50/50' : 'border-gray-200 hover:border-gray-300'" class="flex items-center justify-between p-4 rounded-xl border-2 cursor-pointer transition">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="payment_method" value="cod" class="text-brand-600 focus:ring-brand-500">
                                <div><p class="font-semibold text-sm text-gray-800">Cash on Delivery</p><p class="text-xs text-gray-500">+₹{{ config('shivara.cod_charge') }} COD charges</p></div>
                            </div>
                        </label>
                    </div>
                </div>

                <button type="submit" class="w-full px-8 py-4 text-white text-lg font-bold rounded-xl hover:opacity-90 transition shadow-lg" style="background-color:#2C2418">
                    Place Order
                </button>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm sticky top-24">
                    <h3 class="font-bold text-gray-900 mb-4">Order Summary</h3>
                    <div class="space-y-3 max-h-64 overflow-y-auto scrollbar-hide mb-5">
                        @foreach($cartItems as $item)
                        <div class="flex gap-3">
                            <div class="w-12 h-12 bg-gray-50 rounded-lg shrink-0 overflow-hidden">
                                @if($item->product->primaryImage)
                                <img src="{{ $item->product->primaryImage->url }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-800 line-clamp-2">{{ $item->product->name }}</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">Qty: {{ $item->quantity }}</p>
                            </div>
                            @php $p = $item->variant ? $item->variant->selling_price : $item->product->selling_price; @endphp
                            <p class="text-sm font-bold text-gray-900 shrink-0">₹{{ number_format($p * $item->quantity) }}</p>
                        </div>
                        @endforeach
                    </div>
                    <hr class="border-gray-100 mb-4">
                    @php
                        $subtotal = $cartItems->sum(function($item) { return ($item->variant ? $item->variant->selling_price : $item->product->selling_price) * $item->quantity; });
                    @endphp
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between text-gray-600"><span>Subtotal</span><span>₹{{ number_format($subtotal) }}</span></div>
                        <div class="flex justify-between text-gray-600"><span>Shipping</span><span class="text-gray-400">Calculated next</span></div>
                        <hr class="border-gray-100">
                        <div class="flex justify-between text-lg font-bold text-gray-900 pt-1"><span>Estimated Total</span><span class="text-brand-600">₹{{ number_format($subtotal) }}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
