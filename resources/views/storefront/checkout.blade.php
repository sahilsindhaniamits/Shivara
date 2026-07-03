@extends('layouts.app')
@section('title', 'Checkout - Shivara')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-8">Checkout</h1>

    <form method="POST" action="{{ route('checkout.store') }}">
        @csrf
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Address -->
                <div class="bg-white p-6 rounded-2xl border border-border">
                    <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <i data-lucide="map-pin" class="w-5 h-5 text-primary"></i> Delivery Address
                    </h2>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name *</label>
                            <input type="text" name="full_name" value="{{ old('full_name', auth()->user()->name) }}" required class="w-full px-4 py-3 rounded-xl border border-border text-sm focus:outline-none focus:ring-2 focus:ring-primary/20">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Phone *</label>
                            <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required class="w-full px-4 py-3 rounded-xl border border-border text-sm focus:outline-none focus:ring-2 focus:ring-primary/20">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Email *</label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required class="w-full px-4 py-3 rounded-xl border border-border text-sm focus:outline-none focus:ring-2 focus:ring-primary/20">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Address Line 1 *</label>
                            <input type="text" name="address_line1" value="{{ old('address_line1') }}" required placeholder="House no, Building, Street" class="w-full px-4 py-3 rounded-xl border border-border text-sm focus:outline-none focus:ring-2 focus:ring-primary/20">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Address Line 2</label>
                            <input type="text" name="address_line2" value="{{ old('address_line2') }}" placeholder="Area, Colony (optional)" class="w-full px-4 py-3 rounded-xl border border-border text-sm focus:outline-none focus:ring-2 focus:ring-primary/20">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">City *</label>
                            <input type="text" name="city" value="{{ old('city') }}" required class="w-full px-4 py-3 rounded-xl border border-border text-sm focus:outline-none focus:ring-2 focus:ring-primary/20">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">State *</label>
                            <select name="state" required class="w-full px-4 py-3 rounded-xl border border-border text-sm focus:outline-none focus:ring-2 focus:ring-primary/20">
                                <option value="">Select State</option>
                                @foreach($indianStates as $state)
                                <option value="{{ $state }}" {{ old('state') == $state ? 'selected' : '' }}>{{ $state }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Pincode *</label>
                            <input type="text" name="pincode" value="{{ old('pincode') }}" maxlength="6" required class="w-full px-4 py-3 rounded-xl border border-border text-sm focus:outline-none focus:ring-2 focus:ring-primary/20">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Landmark</label>
                            <input type="text" name="landmark" value="{{ old('landmark') }}" class="w-full px-4 py-3 rounded-xl border border-border text-sm focus:outline-none focus:ring-2 focus:ring-primary/20">
                        </div>
                    </div>
                </div>

                <!-- Shipping Method -->
                <div class="bg-white p-6 rounded-2xl border border-border">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i data-lucide="truck" class="w-5 h-5 text-primary"></i> Shipping Method
                    </h2>
                    <div class="space-y-3">
                        <label class="flex items-center justify-between p-4 rounded-xl border-2 border-primary bg-primary/5 cursor-pointer">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="shipping_method" value="standard" checked class="text-primary">
                                <div><p class="font-medium text-gray-800">Standard Delivery</p><p class="text-sm text-gray-500">{{ config('shivara.standard_days') }}</p></div>
                            </div>
                            <span class="font-semibold text-gray-700">₹{{ config('shivara.standard_rate') }}</span>
                        </label>
                        <label class="flex items-center justify-between p-4 rounded-xl border-2 border-border cursor-pointer hover:border-primary/30">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="shipping_method" value="express" class="text-primary">
                                <div><p class="font-medium text-gray-800">Express Delivery</p><p class="text-sm text-gray-500">{{ config('shivara.express_days') }}</p></div>
                            </div>
                            <span class="font-semibold text-gray-700">₹{{ config('shivara.express_rate') }}</span>
                        </label>
                    </div>
                </div>

                <!-- Payment -->
                <div class="bg-white p-6 rounded-2xl border border-border">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i data-lucide="credit-card" class="w-5 h-5 text-primary"></i> Payment Method
                    </h2>
                    <div class="space-y-3">
                        <label class="flex items-center justify-between p-4 rounded-xl border-2 border-primary bg-primary/5 cursor-pointer">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="payment_method" value="razorpay" checked class="text-primary">
                                <div><p class="font-medium text-gray-800">Pay Online (Razorpay)</p><p class="text-sm text-gray-500">UPI, Credit/Debit Card, Net Banking, Wallets</p></div>
                            </div>
                            <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full font-medium">Recommended</span>
                        </label>
                        <label class="flex items-center justify-between p-4 rounded-xl border-2 border-border cursor-pointer hover:border-primary/30">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="payment_method" value="cod" class="text-primary">
                                <div><p class="font-medium text-gray-800">Cash on Delivery</p><p class="text-sm text-gray-500">+₹{{ config('shivara.cod_charge') }} COD charge</p></div>
                            </div>
                        </label>
                    </div>
                </div>

                <button type="submit" class="w-full px-8 py-4 bg-primary text-white text-lg font-medium rounded-xl hover:bg-primary-dark transition">
                    Place Order
                </button>
            </div>

            <!-- Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-2xl border border-border sticky top-40">
                    <h3 class="font-bold text-gray-900 mb-4">Order Summary</h3>
                    <div class="space-y-3 max-h-60 overflow-y-auto mb-4">
                        @foreach($cartItems as $item)
                        <div class="flex gap-3">
                            <div class="w-12 h-12 bg-accent rounded-lg flex items-center justify-center shrink-0"><span class="text-lg">🌿</span></div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-gray-700 line-clamp-1">{{ $item->product->name }}</p>
                                <p class="text-xs text-gray-400">Qty: {{ $item->quantity }}</p>
                            </div>
                            @php $p = $item->variant ? $item->variant->selling_price : $item->product->selling_price; @endphp
                            <p class="text-sm font-semibold text-gray-800">₹{{ number_format($p * $item->quantity) }}</p>
                        </div>
                        @endforeach
                    </div>
                    <hr class="border-border mb-4">
                    @php
                        $subtotal = $cartItems->sum(function($item) {
                            $p = $item->variant ? $item->variant->selling_price : $item->product->selling_price;
                            return $p * $item->quantity;
                        });
                    @endphp
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between text-gray-600"><span>Subtotal</span><span>₹{{ number_format($subtotal) }}</span></div>
                        <div class="flex justify-between text-gray-600"><span>Shipping</span><span>Calculated at checkout</span></div>
                        <hr class="border-border">
                        <div class="flex justify-between text-lg font-bold text-gray-900"><span>Estimated Total</span><span class="text-primary">₹{{ number_format($subtotal) }}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
