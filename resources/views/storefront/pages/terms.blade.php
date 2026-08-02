@extends('layouts.app')
@section('title', 'Terms and Conditions - Shivara Wellness')
@section('meta_description', 'Shivara Wellness terms and conditions. Read our terms of use, payment policy, product disclaimers, and customer responsibilities.')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-12 md:py-16">
    <div class="mb-10">
        <span class="text-[11px] font-bold uppercase tracking-[0.3em] text-gold-500">Legal</span>
        <h1 class="font-display text-3xl md:text-4xl font-bold text-espresso-700 mt-2">Terms and Conditions</h1>
        <p class="text-sm text-espresso-400 mt-2">Last Updated: July 18, 2026</p>
        <p class="text-sm text-espresso-500 mt-3 leading-relaxed">Welcome to Shivara Wellness (<a href="https://theshivara.com" class="text-gold-600 hover:underline">https://theshivara.com</a>). By accessing this Site or placing an order, you agree to these Terms and Conditions.</p>
    </div>

    <div class="prose prose-sm max-w-none text-espresso-600 space-y-8 leading-relaxed">


        <!-- About Us -->
        <section>
            <h2 class="font-display text-xl font-bold text-espresso-700 mb-3">About Us</h2>
            <p>Shivara Wellness (GSTIN: 08PQWPS3195F1ZN), registered at H1-386-387, Agro Food Park, Udyog Vihar, RIICO Industrial Area, Sri Ganganagar, Rajasthan 335002, operates this Site on a direct-to-consumer (D2C) basis, serving customers within India and internationally.</p>
        </section>

        <!-- Eligibility -->
        <section>
            <h2 class="font-display text-xl font-bold text-espresso-700 mb-3">Eligibility</h2>
            <p>By using this Site, you confirm you are at least 18 years old, or are using the Site under the supervision of a parent/legal guardian.</p>
        </section>

        <!-- Products & Medical Disclaimer -->
        <section>
            <h2 class="font-display text-xl font-bold text-espresso-700 mb-3">Products & Medical Disclaimer</h2>
            <ul class="list-disc pl-5 space-y-2">
                <li>Our products are Ayurvedic/herbal formulations intended for <strong>general wellness support only</strong>. They are not intended to diagnose, treat, cure, or prevent any disease.</li>
                <li>Please consult a qualified physician before use, especially if you are pregnant, nursing, on prescription medication, or have a pre-existing medical condition.</li>
                <li>Product images and descriptions are provided as accurately as possible; minor variation in packaging/appearance may occur.</li>
                <li>We reserve the right to limit quantities or discontinue any product without prior notice.</li>
            </ul>
        </section>


        <!-- Pricing & Payment -->
        <section>
            <h2 class="font-display text-xl font-bold text-espresso-700 mb-3">Pricing & Payment</h2>
            <ul class="list-disc pl-5 space-y-2">
                <li>Prices are listed in Indian Rupees (INR); international customers may be charged in INR with conversion handled by their bank/card provider, or in the displayed currency where supported by our payment gateway.</li>
                <li>We accept payment via UPI, cards, net banking, and Cash on Delivery (domestic orders only, where eligible). International orders must be prepaid.</li>
                <li>We reserve the right to correct pricing errors on the Site and to cancel any order affected by such an error, with a full refund issued.</li>
            </ul>
        </section>

        <!-- Orders -->
        <section>
            <h2 class="font-display text-xl font-bold text-espresso-700 mb-3">Orders</h2>
            <ul class="list-disc pl-5 space-y-2">
                <li>Placing an order is an offer to purchase, which we may accept or decline (e.g., suspected fraud, stock unavailability, pricing error, or shipping restrictions to your location).</li>
                <li>Order confirmation does not guarantee product availability.</li>
            </ul>
        </section>

        <!-- Shipping, Returns -->
        <section>
            <h2 class="font-display text-xl font-bold text-espresso-700 mb-3">Shipping, Returns & Refunds</h2>
            <p>Governed by our <a href="{{ route('shipping-policy') }}" class="text-gold-600 hover:underline">Shipping Policy</a> and <a href="{{ route('return-policy') }}" class="text-gold-600 hover:underline">Return & Refund Policy</a>, including the condition that opened/used products cannot be returned except in cases of damage, defect, or incorrect item received.</p>
        </section>

        <!-- International Orders -->
        <section>
            <h2 class="font-display text-xl font-bold text-espresso-700 mb-3">International Orders & Customs</h2>
            <p>International customers are responsible for any customs duties, import taxes, or restrictions imposed by their country. Shivara Wellness is not responsible for shipments held, delayed, or seized by customs authorities, or for products restricted/prohibited in the destination country.</p>
        </section>


        <!-- Intellectual Property -->
        <section>
            <h2 class="font-display text-xl font-bold text-espresso-700 mb-3">Intellectual Property</h2>
            <p>All content on this Site — including the Shivara Wellness name, logo, product names, images, and text — is our property or used under licence, and protected under applicable intellectual property laws. You may not reproduce or use this content without our prior written consent.</p>
        </section>

        <!-- User Accounts -->
        <section>
            <h2 class="font-display text-xl font-bold text-espresso-700 mb-3">User Accounts</h2>
            <p>You are responsible for maintaining the confidentiality of your account credentials and for all activity under your account. Notify us immediately at <a href="mailto:Info@theshivara.com" class="text-gold-600 hover:underline">Info@theshivara.com</a> of any unauthorized use.</p>
        </section>

        <!-- Prohibited Use -->
        <section>
            <h2 class="font-display text-xl font-bold text-espresso-700 mb-3">Prohibited Use</h2>
            <p>You agree not to use the Site for unlawful purposes, attempt unauthorized access to our systems, or post false/defamatory/misleading content (including in reviews).</p>
        </section>

        <!-- Limitation of Liability -->
        <section>
            <h2 class="font-display text-xl font-bold text-espresso-700 mb-3">Limitation of Liability</h2>
            <p>To the maximum extent permitted by law, Shivara Wellness shall not be liable for indirect, incidental, or consequential damages arising from use of our products or Site. Our total liability for any claim shall not exceed the amount paid for the relevant product.</p>
        </section>

        <!-- Disclaimer -->
        <section>
            <h2 class="font-display text-xl font-bold text-espresso-700 mb-3">Disclaimer of Warranties</h2>
            <p>Products and the Site are provided "as is" and "as available." We make no warranty that any product will have a specific effect on any individual's health condition.</p>
        </section>

        <!-- Governing Law -->
        <section>
            <h2 class="font-display text-xl font-bold text-espresso-700 mb-3">Governing Law & Jurisdiction</h2>
            <p>These Terms are governed by the laws of India. Any disputes shall be subject to the exclusive jurisdiction of the courts at Sri Ganganagar, Rajasthan, regardless of the customer's location, including international customers.</p>
        </section>

        <!-- Changes -->
        <section>
            <h2 class="font-display text-xl font-bold text-espresso-700 mb-3">Changes to Terms</h2>
            <p>We may update these Terms at any time. Continued use of the Site after changes constitutes your acceptance of the revised Terms.</p>
        </section>

        <!-- Contact -->
        <section class="bg-cream-100 rounded-2xl p-6 !mt-10">
            <p class="text-sm font-semibold text-espresso-700">Contact Us:</p>
            <p class="text-sm text-espresso-500 mt-1">Email: <a href="mailto:Info@theshivara.com" class="text-gold-600 hover:underline">Info@theshivara.com</a></p>
            <p class="text-sm text-espresso-500">Phone/WhatsApp: <a href="https://wa.me/919828385808" class="text-gold-600 hover:underline">+91-9828385808</a></p>
            <p class="text-sm text-espresso-500">Address: H1-386-387, Agro Food Park, Udyog Vihar, RIICO Industrial Area, Sri Ganganagar, Rajasthan 335002</p>
        </section>
    </div>
</div>
@endsection
