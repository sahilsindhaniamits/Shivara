@extends('layouts.app')
@section('title', 'Contact Us - Shivara Ayurveda')
@section('meta_description', 'Get in touch with Shivara Ayurveda. We are here to help with orders, products, and wellness guidance.')

@section('content')
<!-- Hero -->
<div class="relative py-16 md:py-20 mt-0" style="background: linear-gradient(135deg, #2C2418 0%, #4A3828 100%);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 text-center">
        <span class="text-[11px] font-bold uppercase tracking-[0.3em] text-gold-400">We're Here to Help</span>
        <h1 class="font-display text-4xl md:text-5xl font-bold text-white mt-3">Contact Us</h1>
        <p class="text-cream-300/70 mt-3 max-w-md mx-auto">Have questions about our products, your order, or Ayurvedic wellness? We'd love to hear from you.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 py-16">
    <div class="grid lg:grid-cols-5 gap-12">

        <!-- Contact Info Cards -->
        <div class="lg:col-span-2 space-y-5">
            <div class="bg-white p-6 rounded-2xl border border-gold-100 shadow-sm">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-4" style="background-color: rgba(176,136,64,0.1)">
                    <svg class="w-5 h-5 text-gold-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-espresso-700 mb-1">Phone / WhatsApp</h3>
                <a href="tel:{{ config('shivara.phone', '+91 9876543210') }}" class="text-sm text-espresso-500 hover:text-gold-600 transition">{{ config('shivara.phone', '+91 9876543210') }}</a>
                <p class="text-[10px] text-espresso-300 mt-1">Mon-Sat, 9AM - 6PM IST</p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gold-100 shadow-sm">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-4" style="background-color: rgba(16,185,129,0.1)">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-espresso-700 mb-1">Email</h3>
                <a href="mailto:{{ config('shivara.email', 'support@shivara.in') }}" class="text-sm text-espresso-500 hover:text-gold-600 transition">{{ config('shivara.email', 'support@shivara.in') }}</a>
                <p class="text-[10px] text-espresso-300 mt-1">We reply within 24 hours</p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gold-100 shadow-sm">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-4" style="background-color: rgba(139,92,246,0.1)">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-espresso-700 mb-1">Address</h3>
                <p class="text-sm text-espresso-500">{{ config('shivara.address.line1', 'Shivara Ayurveda') }}</p>
                <p class="text-sm text-espresso-500">{{ config('shivara.address.line2', '') }}</p>
                <p class="text-sm text-espresso-500">{{ config('shivara.address.city', 'Jaipur') }}, {{ config('shivara.address.state', 'Rajasthan') }} - {{ config('shivara.address.pincode', '335001') }}</p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gold-100 shadow-sm">
                <h3 class="text-sm font-bold text-espresso-700 mb-3">Follow Us</h3>
                <div class="flex gap-3">
                    <a href="https://www.instagram.com/shivarawellness" target="_blank" class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-gold-50 flex items-center justify-center text-espresso-400 hover:text-gold-600 transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                    </a>
                    <a href="https://www.facebook.com/profile.php?id=61588139999665" target="_blank" class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-gold-50 flex items-center justify-center text-espresso-400 hover:text-gold-600 transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="lg:col-span-3">
            <div class="bg-white p-8 md:p-10 rounded-3xl border border-gold-100 shadow-sm">
                <h2 class="font-display text-2xl font-bold text-espresso-700 mb-2">Send us a message</h2>
                <p class="text-sm text-espresso-400 mb-8">Fill out the form below and we'll get back to you within 24 hours.</p>

                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-5">
                    @csrf
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-espresso-600 uppercase tracking-wide mb-1.5">Full Name *</label>
                            <input type="text" name="name" required placeholder="Your name" class="w-full px-4 py-3.5 bg-cream-50 border border-gold-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-gold-200 focus:border-gold-300 focus:bg-white transition placeholder:text-espresso-300">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-espresso-600 uppercase tracking-wide mb-1.5">Phone *</label>
                            <input type="tel" name="phone" required placeholder="+91 XXXXX XXXXX" class="w-full px-4 py-3.5 bg-cream-50 border border-gold-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-gold-200 focus:border-gold-300 focus:bg-white transition placeholder:text-espresso-300">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-espresso-600 uppercase tracking-wide mb-1.5">Email Address *</label>
                        <input type="email" name="email" required placeholder="you@example.com" class="w-full px-4 py-3.5 bg-cream-50 border border-gold-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-gold-200 focus:border-gold-300 focus:bg-white transition placeholder:text-espresso-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-espresso-600 uppercase tracking-wide mb-1.5">Subject</label>
                        <select name="subject" class="w-full px-4 py-3.5 bg-cream-50 border border-gold-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-gold-200 focus:border-gold-300 focus:bg-white transition text-espresso-500">
                            <option value="">Select a topic</option>
                            <option value="order">Order Related</option>
                            <option value="product">Product Inquiry</option>
                            <option value="return">Return / Exchange</option>
                            <option value="wholesale">Wholesale / Bulk Order</option>
                            <option value="feedback">Feedback / Suggestion</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-espresso-600 uppercase tracking-wide mb-1.5">Message *</label>
                        <textarea name="message" rows="5" required placeholder="How can we help you?" class="w-full px-4 py-3.5 bg-cream-50 border border-gold-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-gold-200 focus:border-gold-300 focus:bg-white transition placeholder:text-espresso-300 resize-none"></textarea>
                    </div>
                    <button type="submit" class="w-full sm:w-auto px-10 py-4 text-white font-bold text-sm uppercase tracking-wider rounded-xl hover:opacity-90 transition shadow-lg" style="background-color:#2C2418">
                        Send Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
