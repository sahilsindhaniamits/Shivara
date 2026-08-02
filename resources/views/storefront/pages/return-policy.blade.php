@extends('layouts.app')
@section('title', 'Return & Refund Policy - Shivara Wellness')
@section('meta_description', 'Shivara Wellness return and refund policy. Easy returns for sealed products within 7 days. Free replacement for damaged or defective items.')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-12 md:py-16">
    <div class="mb-10">
        <span class="text-[11px] font-bold uppercase tracking-[0.3em] text-gold-500">Customer Support</span>
        <h1 class="font-display text-3xl md:text-4xl font-bold text-espresso-700 mt-2">Return & Refund Policy</h1>
        <p class="text-sm text-espresso-400 mt-2">Last Updated: July 18, 2026</p>
        <p class="text-sm text-espresso-500 mt-3 leading-relaxed">Please read this policy carefully before ordering. As Shivara Wellness deals in Ayurvedic health and wellness products meant for consumption/application, our return policy is designed keeping product safety and hygiene regulations in mind.</p>
    </div>

    <div class="prose prose-sm max-w-none text-espresso-600 space-y-8 leading-relaxed">

        <!-- Return Policy -->
        <section>
            <h2 class="font-display text-xl font-bold text-espresso-700 mb-3">Our Return Policy — Sealed Products Only</h2>
            <ul class="list-disc pl-5 space-y-2">
                <li>Returns are accepted only for products in their <strong>original, sealed, unopened, and unused condition</strong>, within <strong>7 days of delivery</strong>.</li>
                <li>Once a product's packaging/seal has been opened or the product has been used even partially, it cannot be returned or refunded. This is standard practice for health, wellness, and consumable products, in line with health and safety regulations.</li>
                <li>To be eligible for a return, the product must have its original packaging, seal, and invoice/order confirmation intact.</li>
            </ul>
        </section>

        <!-- Damaged/Defective -->
        <section>
            <h2 class="font-display text-xl font-bold text-espresso-700 mb-3">Damaged, Defective, or Wrong Product Received</h2>
            <p class="mb-3">If you receive a damaged, defective, expired, or incorrect product, this is <strong>not subject</strong> to the "sealed only" condition above:</p>
            <ol class="list-decimal pl-5 space-y-2">
                <li>Contact us within <strong>48 hours of delivery</strong> at <a href="mailto:Info@theshivara.com" class="text-gold-600 hover:underline">Info@theshivara.com</a> or WhatsApp <a href="https://wa.me/919828385808" class="text-gold-600 hover:underline">+91-9828385808</a> with your order number and photos/video of the product and packaging.</li>
                <li>Once verified, we will arrange a <strong>pickup of the product from your address at our own cost</strong>.</li>
                <li>After the returned product is received and inspected, we will offer a <strong>free replacement or a full refund</strong>, including original shipping charges paid, at your preference.</li>
            </ol>
        </section>

        <!-- Non-Returnable -->
        <section>
            <h2 class="font-display text-xl font-bold text-espresso-700 mb-3">Non-Returnable Situations</h2>
            <p class="mb-3">Returns/refunds will not be accepted where:</p>
            <ul class="list-disc pl-5 space-y-2">
                <li>The product's seal/packaging has been opened or the product has been used, unless it falls under the damaged/defective/wrong item category.</li>
                <li>The return request is made after 7 days of delivery.</li>
                <li>The product was purchased under a clearly marked clearance/final sale offer.</li>
            </ul>
        </section>

        <!-- How to Request -->
        <section>
            <h2 class="font-display text-xl font-bold text-espresso-700 mb-3">How to Request a Return or Refund</h2>
            <ol class="list-decimal pl-5 space-y-2">
                <li>Email <a href="mailto:Info@theshivara.com" class="text-gold-600 hover:underline">Info@theshivara.com</a> or WhatsApp <a href="https://wa.me/919828385808" class="text-gold-600 hover:underline">+91-9828385808</a> within 7 days of delivery, with your order ID, reason, and photos (if applicable).</li>
                <li>Our team will review the request. For eligible sealed-product returns or damaged/defective items, we will confirm the return process and pickup arrangement.</li>
                <li>Refunds are initiated only after the product is received back and inspected by our team (except where we decide to waive the return for damaged/defective claims based on photo/video evidence alone).</li>
            </ol>
        </section>

        <!-- Refund Method -->
        <section>
            <h2 class="font-display text-xl font-bold text-espresso-700 mb-3">Refund Method & Timeline</h2>
            <ul class="list-disc pl-5 space-y-2">
                <li>Approved refunds are processed manually within <strong>5–7 business days</strong> of the returned product passing inspection.</li>
                <li>Refunds are made to the original payment method used at checkout. For COD orders, the refund is processed via bank transfer/UPI, for which we will request your bank details.</li>
                <li>Please allow additional time (as per your bank/payment provider) for the refunded amount to reflect in your account.</li>
            </ul>
        </section>

        <!-- International Orders -->
        <section>
            <h2 class="font-display text-xl font-bold text-espresso-700 mb-3">International Orders</h2>
            <p>Returns for international orders are handled on a <strong>case-by-case basis</strong> due to logistics and customs constraints. Please contact us at <a href="mailto:Info@theshivara.com" class="text-gold-600 hover:underline">Info@theshivara.com</a> before initiating any return if you are an international customer.</p>
        </section>

        <!-- Cancellations -->
        <section>
            <h2 class="font-display text-xl font-bold text-espresso-700 mb-3">Cancellations</h2>
            <ul class="list-disc pl-5 space-y-2">
                <li>Orders can be cancelled <strong>free of charge before they are shipped</strong>. Contact us immediately at <a href="mailto:Info@theshivara.com" class="text-gold-600 hover:underline">Info@theshivara.com</a> or +91-9828385808 with your order ID.</li>
                <li>Once shipped, an order cannot be cancelled; the return policy above will apply instead, where eligible.</li>
            </ul>
        </section>

        <!-- Medical Disclaimer -->
        <section>
            <h2 class="font-display text-xl font-bold text-espresso-700 mb-3">Medical Disclaimer</h2>
            <p>Ayurvedic products are for general wellness support and should be used as directed. If a product causes an adverse reaction, discontinue use immediately, consult a physician, and contact us — such cases will be handled under the damaged/defective product process.</p>
        </section>

        <!-- Contact -->
        <section class="bg-cream-100 rounded-2xl p-6 !mt-10">
            <p class="text-sm font-semibold text-espresso-700">For any return/refund query:</p>
            <p class="text-sm text-espresso-500 mt-1">Email: <a href="mailto:Info@theshivara.com" class="text-gold-600 hover:underline">Info@theshivara.com</a> | Phone/WhatsApp: <a href="https://wa.me/919828385808" class="text-gold-600 hover:underline">+91-9828385808</a></p>
        </section>
    </div>
</div>
@endsection
