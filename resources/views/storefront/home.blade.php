@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="relative bg-accent">
    <div class="max-w-7xl mx-auto px-4 py-20 md:py-32">
        <div class="max-w-3xl mx-auto text-center">
            <p class="section-label mb-6">Heritage Ayurveda</p>
            <h1 class="font-editorial text-4xl md:text-6xl lg:text-7xl text-secondary mb-6 leading-tight">
                Ancient wisdom.<br>Modern purity.
            </h1>
            <p class="text-muted text-base md:text-lg max-w-xl mx-auto mb-10 leading-relaxed">
                Formulations rooted in 5,000 years of Ayurvedic tradition,
                crafted with single-origin herbs from the farms of Rajasthan.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('products.index') }}" class="px-8 py-3 bg-secondary text-white text-sm font-medium uppercase tracking-wider hover:bg-secondary-light transition">Explore Collection</a>
                <a href="{{ route('contact') }}" class="px-8 py-3 border border-secondary text-secondary text-sm font-medium uppercase tracking-wider hover:bg-secondary hover:text-white transition">Our Philosophy</a>
            </div>
            <p class="text-[10px] uppercase tracking-[0.3em] text-muted mt-16">Scroll</p>
        </div>
    </div>
</section>

<!-- Trust Badges -->
<section class="bg-accent/60 border-y border-border">
    <div class="max-w-7xl mx-auto px-4 py-12">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="flex flex-col items-center text-center gap-3">
                <i data-lucide="leaf" class="w-6 h-6 text-primary"></i>
                <h3 class="font-serif text-sm text-secondary">Single-Origin Herbs</h3>
                <p class="text-xs text-muted">Sourced from heritage farms in Rajasthan</p>
            </div>
            <div class="flex flex-col items-center text-center gap-3">
                <i data-lucide="shield" class="w-6 h-6 text-primary"></i>
                <h3 class="font-serif text-sm text-secondary">GMP Certified</h3>
                <p class="text-xs text-muted">Crafted in audited facilities</p>
            </div>
            <div class="flex flex-col items-center text-center gap-3">
                <i data-lucide="sparkles" class="w-6 h-6 text-primary"></i>
                <h3 class="font-serif text-sm text-secondary">Free Shipping</h3>
                <p class="text-xs text-muted">On all orders within India</p>
            </div>
            <div class="flex flex-col items-center text-center gap-3">
                <i data-lucide="award" class="w-6 h-6 text-primary"></i>
                <h3 class="font-serif text-sm text-secondary">Lab Tested</h3>
                <p class="text-xs text-muted">Purity verified in certified labs</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-14">
            <p class="section-label mb-4">Editor's Selection</p>
            <h2 class="font-editorial text-3xl md:text-5xl text-secondary">Amazing deals.</h2>
            <p class="text-muted text-sm mt-4 max-w-md mx-auto">Our most-loved formulations, currently offered at heritage prices.</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-5">
            @forelse($featuredProducts as $product)
                @include('partials.product-card', ['product' => $product])
            @empty
                <p class="col-span-4 text-center text-muted py-12">No featured products yet.</p>
            @endforelse
        </div>

        <div class="mt-12 text-center">
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-8 py-3 border border-secondary text-secondary text-sm font-medium uppercase tracking-wider hover:bg-secondary hover:text-white transition">
                View All Products <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>
    </div>
</section>

<!-- Categories -->
<section class="py-20 bg-accent/40">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-14">
            <p class="section-label mb-4">Shop by Concern</p>
            <h2 class="font-editorial text-3xl md:text-5xl text-secondary">Find your balance.</h2>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($categories as $cat)
            <a href="{{ route('products.index', ['category' => $cat->slug]) }}" class="group text-center p-6 bg-white border border-border hover:border-primary/30 transition-all duration-300">
                <div class="text-3xl mb-3 group-hover:scale-110 transition-transform duration-300">🌿</div>
                <h3 class="text-xs font-medium uppercase tracking-wider text-secondary group-hover:text-primary transition">{{ $cat->name }}</h3>
                <p class="text-[10px] text-muted mt-1">{{ $cat->products_count }} products</p>
            </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Story / About -->
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <p class="section-label mb-4">Our Story</p>
        <h2 class="font-editorial text-3xl md:text-5xl text-secondary mb-8">Purity you can trust.</h2>
        <p class="text-muted leading-relaxed max-w-2xl mx-auto mb-6">
            At Shivara, we believe that wellness begins with what nature provides. Every product is a testament to our commitment — sourcing the finest single-origin herbs from Rajasthan, processing them in GMP-certified facilities, and delivering them with the integrity your health deserves.
        </p>
        <p class="text-muted leading-relaxed max-w-2xl mx-auto">No shortcuts. No compromises. Just Ayurveda in its purest form.</p>
        <div class="w-10 h-px bg-primary mx-auto mt-10 mb-10"></div>
        <div class="flex justify-center gap-12">
            <div class="text-center">
                <p class="text-3xl font-serif text-secondary">5,000+</p>
                <p class="text-[10px] uppercase tracking-widest text-muted mt-1">Happy Customers</p>
            </div>
            <div class="text-center">
                <p class="text-3xl font-serif text-secondary">50+</p>
                <p class="text-[10px] uppercase tracking-widest text-muted mt-1">Formulations</p>
            </div>
            <div class="text-center">
                <p class="text-3xl font-serif text-secondary">4.8</p>
                <p class="text-[10px] uppercase tracking-widest text-muted mt-1">Average Rating</p>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter -->
<section class="py-20 bg-white border-t border-border">
    <div class="max-w-xl mx-auto px-4 text-center">
        <p class="section-label mb-4">Stay Connected</p>
        <h2 class="font-editorial text-3xl text-secondary mb-4">Join the community.</h2>
        <p class="text-muted text-sm mb-8">Exclusive offers, Ayurvedic wisdom, and new product launches delivered to your inbox.</p>
        <form class="flex flex-col sm:flex-row gap-3">
            <input type="email" placeholder="Your email address" class="flex-1 px-5 py-3 border border-border bg-white text-sm focus:outline-none focus:border-primary/50 placeholder:text-muted">
            <button type="button" class="px-6 py-3 bg-secondary text-white text-sm font-medium uppercase tracking-wider hover:bg-secondary-light transition">Subscribe</button>
        </form>
        <p class="text-[10px] text-muted mt-3 uppercase tracking-wider">No spam. Unsubscribe anytime.</p>
    </div>
</section>
@endsection
