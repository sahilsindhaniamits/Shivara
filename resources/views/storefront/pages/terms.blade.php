@extends('layouts.app')
@section('title', 'Terms and Conditions - Shivara Ayurveda')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-12 md:py-16">
    <div class="mb-10">
        <span class="text-[11px] font-bold uppercase tracking-[0.3em] text-gold-500">Legal</span>
        <h1 class="font-display text-3xl md:text-4xl font-bold text-espresso-700 mt-2">Terms and Conditions</h1>
    </div>
    <div class="prose prose-sm max-w-none text-espresso-600 space-y-6 leading-relaxed">
        <p>By accessing and using the Shivara Ayurveda website (shivara.itspherehub.in), you agree to be bound by these Terms and Conditions.</p>

        <h2 class="font-display text-xl font-bold text-espresso-700 !mt-8">1. Use of Website</h2>
        <p>You may use this website for lawful purposes only. You must not use this site in any way that causes damage to the website or impairs its availability.</p>

        <h2 class="font-display text-xl font-bold text-espresso-700 !mt-8">2. Products & Pricing</h2>
        <ul class="list-disc pl-5 space-y-2">
            <li>All prices are in Indian Rupees (INR) and include applicable taxes</li>
            <li>We reserve the right to modify prices without prior notice</li>
            <li>Product images are for illustration; actual products may vary slightly</li>
            <li>Products are subject to availability</li>
        </ul>

        <h2 class="font-display text-xl font-bold text-espresso-700 !mt-8">3. Orders & Payment</h2>
        <p>An order is confirmed only after successful payment processing. We accept UPI, credit/debit cards, net banking, wallets, and Cash on Delivery.</p>

        <h2 class="font-display text-xl font-bold text-espresso-700 !mt-8">4. Intellectual Property</h2>
        <p>All content on this website including text, images, logos, and designs are the intellectual property of Shivara Ayurveda and may not be reproduced without permission.</p>

        <h2 class="font-display text-xl font-bold text-espresso-700 !mt-8">5. Limitation of Liability</h2>
        <p>Our products are Ayurvedic supplements and not intended to diagnose, treat, cure, or prevent any disease. Results may vary. Consult your healthcare provider before use.</p>

        <h2 class="font-display text-xl font-bold text-espresso-700 !mt-8">6. Governing Law</h2>
        <p>These terms shall be governed by and construed in accordance with the laws of India. Any disputes shall be subject to the exclusive jurisdiction of courts in Rajasthan.</p>

        <h2 class="font-display text-xl font-bold text-espresso-700 !mt-8">7. Contact</h2>
        <p>For questions regarding these terms, contact us at <a href="mailto:{{ config('shivara.email', 'support@shivara.in') }}" class="text-gold-600 hover:underline">{{ config('shivara.email', 'support@shivara.in') }}</a></p>
    </div>
</div>
@endsection
