@extends('layouts.app')
@section('title', 'About Us - Shivara Ayurveda')

@section('content')
<!-- Hero -->
<div class="relative py-16 md:py-24 mt-0" style="background: linear-gradient(135deg, #2C2418 0%, #4A3828 100%);">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center">
        <span class="text-[11px] font-bold uppercase tracking-[0.3em] text-gold-400">Our Story</span>
        <h1 class="font-display text-4xl md:text-6xl font-bold text-white mt-3">Rooted in Tradition.<br>Built on Trust.</h1>
        <p class="text-cream-300/70 mt-4 max-w-2xl mx-auto text-lg">At Shivara, we bring the ancient wisdom of Ayurveda to modern life — with purity, authenticity, and care.</p>
    </div>
</div>

<div class="max-w-5xl mx-auto px-4 sm:px-6 py-16 space-y-16">
    <!-- Mission -->
    <div class="grid md:grid-cols-2 gap-12 items-center">
        <div>
            <span class="text-[11px] font-bold uppercase tracking-[0.3em] text-gold-500">Our Mission</span>
            <h2 class="font-display text-3xl font-bold text-espresso-700 mt-2 mb-4">Making Ayurveda Accessible to Everyone</h2>
            <p class="text-espresso-500 leading-relaxed mb-4">We believe that nature has the answer to most health challenges. Our mission is to deliver pure, lab-tested, GMP-certified Ayurvedic products that are effective, affordable, and free from harmful chemicals.</p>
            <p class="text-espresso-500 leading-relaxed">Every product is formulated by experienced Ayurvedic practitioners and manufactured in state-of-the-art facilities following strict quality protocols.</p>
        </div>
        <div class="aspect-[4/3] rounded-3xl overflow-hidden border-4 border-white shadow-xl">
            <img src="https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=600&h=450&fit=crop" alt="Ayurvedic herbs" class="w-full h-full object-cover">
        </div>
    </div>

    <!-- Values -->
    <div>
        <div class="text-center mb-10">
            <span class="text-[11px] font-bold uppercase tracking-[0.3em] text-gold-500">What We Stand For</span>
            <h2 class="font-display text-3xl font-bold text-espresso-700 mt-2">Our Core Values</h2>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="text-center p-6 bg-white rounded-2xl border border-gold-100">
                <div class="w-14 h-14 mx-auto mb-4 rounded-2xl flex items-center justify-center" style="background-color: rgba(176,136,64,0.1)">
                    <svg class="w-7 h-7 text-gold-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-espresso-700 mb-2">Purity</h3>
                <p class="text-xs text-espresso-400">100% natural ingredients with no synthetic additives or preservatives</p>
            </div>
            <div class="text-center p-6 bg-white rounded-2xl border border-gold-100">
                <div class="w-14 h-14 mx-auto mb-4 rounded-2xl flex items-center justify-center" style="background-color: rgba(16,185,129,0.1)">
                    <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-espresso-700 mb-2">Trust</h3>
                <p class="text-xs text-espresso-400">GMP-certified manufacturing with third-party lab testing</p>
            </div>
            <div class="text-center p-6 bg-white rounded-2xl border border-gold-100">
                <div class="w-14 h-14 mx-auto mb-4 rounded-2xl flex items-center justify-center" style="background-color: rgba(139,92,246,0.1)">
                    <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-espresso-700 mb-2">Science</h3>
                <p class="text-xs text-espresso-400">Ancient formulations validated by modern research and clinical studies</p>
            </div>
            <div class="text-center p-6 bg-white rounded-2xl border border-gold-100">
                <div class="w-14 h-14 mx-auto mb-4 rounded-2xl flex items-center justify-center" style="background-color: rgba(245,158,11,0.1)">
                    <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-espresso-700 mb-2">Care</h3>
                <p class="text-xs text-espresso-400">Customer-first approach with personalized wellness guidance</p>
            </div>
        </div>
    </div>

    <!-- Numbers -->
    <div class="bg-white rounded-3xl border border-gold-100 p-10 text-center">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div><p class="text-4xl font-display font-bold text-gold-600">5000+</p><p class="text-xs text-espresso-400 uppercase tracking-wider mt-1">Happy Customers</p></div>
            <div><p class="text-4xl font-display font-bold text-gold-600">50+</p><p class="text-xs text-espresso-400 uppercase tracking-wider mt-1">Products</p></div>
            <div><p class="text-4xl font-display font-bold text-gold-600">4.8★</p><p class="text-xs text-espresso-400 uppercase tracking-wider mt-1">Avg Rating</p></div>
            <div><p class="text-4xl font-display font-bold text-gold-600">100%</p><p class="text-xs text-espresso-400 uppercase tracking-wider mt-1">Natural</p></div>
        </div>
    </div>
</div>
@endsection
