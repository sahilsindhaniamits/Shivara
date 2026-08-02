@extends('layouts.app')
@section('title', 'Shipping Policy - Shivara Wellness | Free Delivery Above ₹999')
@section('meta_description', 'Shivara Wellness shipping policy. Free shipping on orders above ₹999. Standard delivery in 5-7 business days. We ship across India and internationally.')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-12 md:py-16">
    <div class="mb-10">
        <span class="text-[11px] font-bold uppercase tracking-[0.3em] text-gold-500">Delivery Information</span>
        <h1 class="font-display text-3xl md:text-4xl font-bold text-espresso-700 mt-2">Shipping Policy</h1>
        <p class="text-sm text-espresso-400 mt-2">At Shivara Wellness, we strive to get your Ayurvedic wellness products to you safely and within a reasonable time.</p>
    </div>

    <!-- Quick Info Cards -->
    <div class="grid sm:grid-cols-3 gap-4 mb-10">
        <div class="bg-white p-5 rounded-2xl border border-gold-100 text-center">
            <p class="text-2xl font-display font-bold text-gold-600">₹999+</p>
            <p class="text-xs text-espresso-500 font-semibold uppercase mt-1">Free Shipping</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gold-100 text-center">
            <p class="text-2xl font-display font-bold text-gold-600">5–7 Days</p>
            <p class="text-xs text-espresso-500 font-semibold uppercase mt-1">Standard Delivery</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gold-100 text-center">
            <p class="text-2xl font-display font-bold text-gold-600">Pan India</p>
            <p class="text-xs text-espresso-500 font-semibold uppercase mt-1">+ International</p>
        </div>
    </div>

    <div class="prose prose-sm max-w-none text-espresso-600 space-y-8 leading-relaxed">

        <!-- Order Processing -->
        <section>
            <h2 class="font-display text-xl font-bold text-espresso-700 mb-3">Order Processing</h2>
            <ul class="list-disc pl-5 space-y-2">
                <li>Orders are processed within <strong>2–5 business days</strong> of order confirmation and payment realization.</li>
                <li>Orders placed on Sundays or public holidays will begin processing the next working day.</li>
                <li>You will receive an order confirmation and, once shipped, a tracking link/number via email, SMS, or WhatsApp.</li>
            </ul>
        </section>

        <!-- Shipping Coverage -->
        <section>
            <h2 class="font-display text-xl font-bold text-espresso-700 mb-3">Shipping Coverage</h2>
            <ul class="list-disc pl-5 space-y-2">
                <li>We currently ship across India and also accept <strong>international orders</strong> (Direct-to-Consumer).</li>
                <li>For international orders, customers are responsible for any customs duties, import taxes, or levies charged by the destination country. These are not included in our product or shipping price and are payable by the customer upon or before delivery, as per local customs regulations.</li>
                <li>Serviceability for a specific address/pin code/country depends on our courier partners' coverage at the time of order.</li>
            </ul>
        </section>

        <!-- Shipping Charges -->
        <section>
            <h2 class="font-display text-xl font-bold text-espresso-700 mb-3">Shipping Charges</h2>
            <ul class="list-disc pl-5 space-y-2">
                <li><strong>Free standard shipping</strong> is available on eligible domestic orders above ₹999.</li>
                <li>Orders below the free-shipping threshold may attract a standard shipping charge, as shown at checkout.</li>
                <li><strong>Express Delivery</strong> is available for an additional ₹149, where serviceable (domestic orders only).</li>
                <li>International shipping charges are calculated and displayed at checkout based on destination and order weight.</li>
            </ul>
        </section>

        <!-- Delivery Timelines -->
        <section>
            <h2 class="font-display text-xl font-bold text-espresso-700 mb-3">Delivery Timelines</h2>
            <ul class="list-disc pl-5 space-y-2">
                <li>Delivery timelines shown at checkout or via tracking updates are <strong>estimates</strong> and may vary due to courier delays, customs clearance (for international orders), weather, regional restrictions, or other circumstances beyond our control.</li>
                <li>We are not liable for delays caused by third-party courier/logistics providers, once the shipment has left our facility.</li>
            </ul>
        </section>

        <!-- Order Tracking -->
        <section>
            <h2 class="font-display text-xl font-bold text-espresso-700 mb-3">Order Tracking</h2>
            <ul class="list-disc pl-5 space-y-2">
                <li>Track your order anytime via the <a href="{{ route('track.order') }}" class="text-gold-600 font-semibold hover:underline">Track Order page</a>.</li>
                <li>For any delay or tracking issue, contact us at <a href="mailto:Info@theshivara.com" class="text-gold-600 hover:underline">Info@theshivara.com</a> or WhatsApp <a href="https://wa.me/919828385808" class="text-gold-600 hover:underline">+91-9828385808</a>.</li>
            </ul>
        </section>

        <!-- Cash on Delivery -->
        <section>
            <h2 class="font-display text-xl font-bold text-espresso-700 mb-3">Cash on Delivery (COD)</h2>
            <ul class="list-disc pl-5 space-y-2">
                <li>COD is available on eligible domestic orders and pin codes. International orders must be prepaid.</li>
                <li>A confirmation call/message may be required before dispatch for high-value COD orders.</li>
            </ul>
        </section>

        <!-- Undelivered Shipments -->
        <section>
            <h2 class="font-display text-xl font-bold text-espresso-700 mb-3">Undelivered / Refused Shipments</h2>
            <p>If a shipment is returned to us due to an incorrect address, unavailability of the recipient, or refusal to accept delivery, we will contact you to arrange re-shipment. Additional shipping charges may apply.</p>
        </section>

        <!-- Force Majeure -->
        <section>
            <h2 class="font-display text-xl font-bold text-espresso-700 mb-3">Force Majeure</h2>
            <p>Shivara Wellness is not liable for shipping delays caused by natural calamities, courier strikes, customs holds, government restrictions, or other events beyond our reasonable control.</p>
        </section>

        <!-- Contact -->
        <section class="bg-cream-100 rounded-2xl p-6 !mt-10">
            <p class="text-sm font-semibold text-espresso-700">For any shipping questions:</p>
            <p class="text-sm text-espresso-500 mt-1">Email: <a href="mailto:Info@theshivara.com" class="text-gold-600 hover:underline">Info@theshivara.com</a> | Phone/WhatsApp: <a href="https://wa.me/919828385808" class="text-gold-600 hover:underline">+91-9828385808</a></p>
        </section>
    </div>
</div>
@endsection
