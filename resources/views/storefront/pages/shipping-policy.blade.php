@extends('layouts.app')
@section('title', 'Shipping Policy - Shivara Ayurveda')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-12 md:py-16">
    <div class="mb-10">
        <span class="text-[11px] font-bold uppercase tracking-[0.3em] text-gold-500">Delivery</span>
        <h1 class="font-display text-3xl md:text-4xl font-bold text-espresso-700 mt-2">Shipping Policy</h1>
        <p class="text-sm text-espresso-400 mt-2">Fast & reliable delivery across India</p>
    </div>
    <div class="prose prose-sm max-w-none text-espresso-600 space-y-6 leading-relaxed">
        <div class="grid sm:grid-cols-2 gap-4 !mt-0">
            <div class="bg-white p-5 rounded-2xl border border-gold-100 text-center">
                <p class="text-2xl font-display font-bold text-gold-600">₹{{ config('shivara.free_shipping_threshold', 299) }}+</p>
                <p class="text-xs text-espresso-500 font-semibold uppercase mt-1">Free Shipping</p>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-gold-100 text-center">
                <p class="text-2xl font-display font-bold text-gold-600">5-7 Days</p>
                <p class="text-xs text-espresso-500 font-semibold uppercase mt-1">Standard Delivery</p>
            </div>
        </div>

        <h2 class="font-display text-xl font-bold text-espresso-700 !mt-8">Shipping Rates</h2>
        <div class="bg-white rounded-xl border border-gold-100 overflow-hidden">
            <table class="w-full text-sm">
                <thead><tr class="bg-cream-100"><th class="px-5 py-3 text-left text-xs font-bold text-espresso-600 uppercase">Method</th><th class="px-5 py-3 text-left text-xs font-bold text-espresso-600 uppercase">Delivery Time</th><th class="px-5 py-3 text-left text-xs font-bold text-espresso-600 uppercase">Cost</th></tr></thead>
                <tbody>
                    <tr class="border-t border-gold-50"><td class="px-5 py-3">Standard Shipping</td><td class="px-5 py-3">5-7 business days</td><td class="px-5 py-3 font-semibold">₹{{ config('shivara.standard_rate', 79) }} (Free above ₹{{ config('shivara.free_shipping_threshold', 299) }})</td></tr>
                    <tr class="border-t border-gold-50"><td class="px-5 py-3">Express Shipping</td><td class="px-5 py-3">2-3 business days</td><td class="px-5 py-3 font-semibold">₹{{ config('shivara.express_rate', 149) }}</td></tr>
                </tbody>
            </table>
        </div>

        <h2 class="font-display text-xl font-bold text-espresso-700 !mt-8">Delivery Coverage</h2>
        <p>We deliver to all pin codes across India. Orders are shipped via trusted courier partners including Delhivery, DTDC, and India Post.</p>

        <h2 class="font-display text-xl font-bold text-espresso-700 !mt-8">Order Tracking</h2>
        <p>Once your order is shipped, you'll receive a tracking number via email and SMS. Track your order anytime from your account dashboard.</p>

        <h2 class="font-display text-xl font-bold text-espresso-700 !mt-8">Cash on Delivery</h2>
        <p>COD is available on all orders with a nominal charge of ₹{{ config('shivara.cod_charge', 49) }}. Pay at the time of delivery using cash or UPI.</p>
    </div>
</div>
@endsection
