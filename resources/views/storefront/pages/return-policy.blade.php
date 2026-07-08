@extends('layouts.app')
@section('title', 'Return & Refund Policy - Shivara Ayurveda')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-12 md:py-16">
    <div class="mb-10">
        <span class="text-[11px] font-bold uppercase tracking-[0.3em] text-gold-500">Customer Support</span>
        <h1 class="font-display text-3xl md:text-4xl font-bold text-espresso-700 mt-2">Return & Refund Policy</h1>
        <p class="text-sm text-espresso-400 mt-2">Your satisfaction is our priority</p>
    </div>
    <div class="prose prose-sm max-w-none text-espresso-600 space-y-6 leading-relaxed">
        <div class="bg-green-50 border border-green-200 rounded-2xl p-6 !mt-0">
            <p class="text-sm font-semibold text-green-800 flex items-center gap-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> We offer a 7-day hassle-free return policy on all products.</p>
        </div>

        <h2 class="font-display text-xl font-bold text-espresso-700 !mt-8">Eligibility for Returns</h2>
        <ul class="list-disc pl-5 space-y-2">
            <li>Products must be returned within <strong>7 days</strong> of delivery</li>
            <li>Items must be unused, unopened, and in original packaging</li>
            <li>Opened/used products can only be returned if defective or damaged</li>
            <li>Provide order number and reason for return</li>
        </ul>

        <h2 class="font-display text-xl font-bold text-espresso-700 !mt-8">Non-Returnable Items</h2>
        <ul class="list-disc pl-5 space-y-2">
            <li>Products that have been opened and used (unless defective)</li>
            <li>Items purchased during clearance sales</li>
            <li>Gift cards and promotional items</li>
        </ul>

        <h2 class="font-display text-xl font-bold text-espresso-700 !mt-8">Refund Process</h2>
        <ol class="list-decimal pl-5 space-y-2">
            <li>Contact us at <a href="mailto:{{ config('shivara.email', 'support@shivara.in') }}" class="text-gold-600 hover:underline">{{ config('shivara.email', 'support@shivara.in') }}</a> with your order number</li>
            <li>We'll arrange a pickup or provide return shipping instructions</li>
            <li>Once received and inspected, refund will be processed within 5-7 business days</li>
            <li>Refund will be credited to your original payment method</li>
        </ol>

        <h2 class="font-display text-xl font-bold text-espresso-700 !mt-8">Exchange</h2>
        <p>We offer free exchanges for damaged or incorrect items. Contact our support team and we'll ship the replacement at no extra cost.</p>

        <h2 class="font-display text-xl font-bold text-espresso-700 !mt-8">Contact</h2>
        <p>For return requests, email <a href="mailto:{{ config('shivara.email', 'support@shivara.in') }}" class="text-gold-600 hover:underline">{{ config('shivara.email', 'support@shivara.in') }}</a> or call <a href="tel:{{ config('shivara.phone', '+919876543210') }}" class="text-gold-600 hover:underline">{{ config('shivara.phone', '+91 9876543210') }}</a></p>
    </div>
</div>
@endsection
