@extends('layouts.app')
@section('title', 'Privacy Policy - Shivara Ayurveda')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-12 md:py-16">
    <div class="mb-10">
        <span class="text-[11px] font-bold uppercase tracking-[0.3em] text-gold-500">Legal</span>
        <h1 class="font-display text-3xl md:text-4xl font-bold text-espresso-700 mt-2">Privacy Policy</h1>
    </div>
    <div class="prose prose-sm max-w-none text-espresso-600 space-y-6 leading-relaxed">
        <p>At Shivara Ayurveda ("we", "us", "our"), we are committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website or make a purchase.</p>

        <h2 class="font-display text-xl font-bold text-espresso-700 !mt-8">1. Information We Collect</h2>
        <p><strong>Personal Information:</strong> Name, email address, phone number, shipping address, billing address, and payment information when you place an order.</p>
        <p><strong>Automatically Collected:</strong> IP address, browser type, operating system, referring URLs, and browsing behavior on our site using cookies and analytics tools.</p>

        <h2 class="font-display text-xl font-bold text-espresso-700 !mt-8">2. How We Use Your Information</h2>
        <ul class="list-disc pl-5 space-y-1">
            <li>Process and fulfill your orders</li>
            <li>Send order confirmations and shipping updates</li>
            <li>Respond to customer service requests</li>
            <li>Send promotional communications (with your consent)</li>
            <li>Improve our website and product offerings</li>
            <li>Prevent fraudulent transactions</li>
        </ul>

        <h2 class="font-display text-xl font-bold text-espresso-700 !mt-8">3. Information Sharing</h2>
        <p>We do not sell, trade, or rent your personal information. We may share data with:</p>
        <ul class="list-disc pl-5 space-y-1">
            <li>Payment processors (Razorpay) for secure transactions</li>
            <li>Shipping partners (Shiprocket, Delhivery) for order delivery</li>
            <li>Analytics providers to improve our services</li>
        </ul>

        <h2 class="font-display text-xl font-bold text-espresso-700 !mt-8">4. Data Security</h2>
        <p>We implement industry-standard security measures including SSL encryption, secure payment gateways, and access controls to protect your personal information.</p>

        <h2 class="font-display text-xl font-bold text-espresso-700 !mt-8">5. Your Rights</h2>
        <p>You have the right to access, correct, or delete your personal data. Contact us at <a href="mailto:{{ config('shivara.email', 'support@shivara.in') }}" class="text-gold-600 hover:underline">{{ config('shivara.email', 'support@shivara.in') }}</a> for any privacy-related requests.</p>

        <h2 class="font-display text-xl font-bold text-espresso-700 !mt-8">6. Contact Us</h2>
        <p>If you have questions about this Privacy Policy, please contact us at <a href="mailto:{{ config('shivara.email', 'support@shivara.in') }}" class="text-gold-600 hover:underline">{{ config('shivara.email', 'support@shivara.in') }}</a></p>
    </div>
</div>
@endsection
