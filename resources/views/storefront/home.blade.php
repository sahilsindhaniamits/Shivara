@extends('layouts.app')

@section('content')
<!-- Hero -->
<section class="relative bg-gradient-to-br from-brand-50 via-white to-brand-50/30 overflow-hidden">
    <div class="absolute inset-0 opacity-5" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23c9a96e&quot; fill-opacity=&quot;0.4&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-20 md:py-32 lg:py-40 relative">
        <div class="max-w-3xl mx-auto text-center animate-fade-in">
            <span class="inline-block text-[11px] font-bold uppercase tracking-[0.3em] text-brand-600 bg-brand-50 px-4 py-1.5 rounded-full mb-6">Heritage Ayurveda</span>
            <h1 class="font-display text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold text-dark leading-[1.1] mb-6">
                Ancient wisdom.<br><span class="text-brand-600">Modern purity.</span>
            </h1>
            <p class="text-gray-500 text-base md:text-lg max-w-xl mx-auto mb-10 leading-relaxed">
                Formulations rooted in 5,000 years of Ayurvedic tradition, crafted with single-origin herbs from Rajasthan.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('products.index') }}" class="px-8 py-3.5 bg-dark text-white text-sm font-semibold uppercase tracking-wider rounded-full hover:bg-dark-light transition shadow-lg shadow-dark/20">
                    Explore Collection
                </a>
                <a href="{{ route('contact') }}" class="px-8 py-3.5 border-2 border-gray-900 text-gray-900 text-sm font-semibold uppercase tracking-wider rounded-full hover:bg-gray-900 hover:text-white transition">
                    Our Philosophy
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Categories -->
<section class="py-16 md:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-12">
            <span class="text-[11px] font-bold uppercase tracking-[0.3em] text-brand-600">Shop by Concern</span>
            <h2 class="font-display text-3xl md:text-4xl font-bold text-dark mt-3">Find your balance.</h2>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($categories as $cat)
            <a href="{{ route('products.index', ['category' => $cat->slug]) }}" class="group text-center p-5 md:p-6 bg-gray-50 rounded-2xl border border-gray-100 hover:border-brand-200 hover:bg-brand-50/50 transition-all duration-300">
                <div class="w-12 h-12 mx-auto mb-3 bg-white rounded-xl flex items-center justify-center shadow-sm group-hover:shadow-md group-hover:scale-110 transition-all duration-300">
                    <svg class="w-6 h-6 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <h3 class="text-xs font-semibold text-gray-800 group-hover:text-brand-700 transition">{{ $cat->name }}</h3>
                <p class="text-[10px] text-gray-400 mt-1">{{ $cat->products_count }} products</p>
            </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-16 md:py-20 bg-gray-50/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="flex items-end justify-between mb-10">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-[0.3em] text-brand-600">Best Sellers</span>
                <h2 class="font-display text-3xl md:text-4xl font-bold text-dark mt-2">Amazing deals.</h2>
            </div>
            <a href="{{ route('products.index') }}" class="hidden sm:flex items-center gap-2 text-sm font-semibold text-brand-700 hover:text-brand-800 transition">
                View All
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
            @forelse($featuredProducts as $product)
                @include('partials.product-card', ['product' => $product])
            @empty
                <p class="col-span-full text-center text-gray-400 py-16">No featured products yet.</p>
            @endforelse
        </div>

        <div class="mt-10 text-center sm:hidden">
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-6 py-3 border-2 border-dark text-dark text-sm font-semibold rounded-full hover:bg-dark hover:text-white transition">
                View All Products
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>

<!-- Why Shivara -->
<section class="py-16 md:py-24 bg-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 text-center">
        <span class="text-[11px] font-bold uppercase tracking-[0.3em] text-brand-600">Our Story</span>
        <h2 class="font-display text-3xl md:text-4xl font-bold text-dark mt-3 mb-6">Purity you can trust.</h2>
        <p class="text-gray-500 leading-relaxed max-w-2xl mx-auto mb-4">
            At Shivara, we believe wellness begins with what nature provides. Every product is a testament to our commitment — sourcing the finest single-origin herbs from Rajasthan, processing them in GMP-certified facilities, and delivering them with the integrity your health deserves.
        </p>
        <p class="text-gray-500 leading-relaxed max-w-2xl mx-auto mb-12">
            No shortcuts. No compromises. Just Ayurveda in its purest form.
        </p>
        <div class="flex flex-wrap justify-center gap-8 md:gap-16">
            <div class="text-center">
                <p class="text-3xl md:text-4xl font-display font-bold text-dark">5,000+</p>
                <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400 mt-1">Happy Customers</p>
            </div>
            <div class="text-center">
                <p class="text-3xl md:text-4xl font-display font-bold text-dark">50+</p>
                <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400 mt-1">Formulations</p>
            </div>
            <div class="text-center">
                <p class="text-3xl md:text-4xl font-display font-bold text-brand-600">4.8</p>
                <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400 mt-1">Average Rating</p>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter -->
<section class="py-16 md:py-20 bg-dark">
    <div class="max-w-xl mx-auto px-4 sm:px-6 text-center">
        <span class="text-[11px] font-bold uppercase tracking-[0.3em] text-gold">Stay Connected</span>
        <h2 class="font-display text-2xl md:text-3xl font-bold text-white mt-3 mb-4">Join the community.</h2>
        <p class="text-gray-400 text-sm mb-8">Exclusive offers, Ayurvedic wisdom, and new product launches — straight to your inbox.</p>
        <form class="flex flex-col sm:flex-row gap-3">
            <input type="email" placeholder="Your email address" class="flex-1 px-5 py-3.5 bg-dark-light border border-gray-700 rounded-full text-sm text-white placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-gold/30 focus:border-gold/50">
            <button type="button" class="px-7 py-3.5 bg-gold text-dark text-sm font-semibold uppercase tracking-wider rounded-full hover:bg-gold-light transition">Subscribe</button>
        </form>
        <p class="text-[10px] text-gray-500 mt-3">No spam. Unsubscribe anytime.</p>
    </div>
</section>
@endsection
