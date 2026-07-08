@extends('layouts.app')

@section('content')
<!-- Hero Banner Slider -->
<section x-data="{ current: 0, slides: 3 }" x-init="setInterval(() => current = (current + 1) % slides, 5000); initSwipe($el, () => current = (current+1)%slides, () => current = (current-1+slides)%slides)" class="relative overflow-hidden select-none cursor-grab active:cursor-grabbing">
    <!-- Slide 1 -->
    <div x-show="current === 0" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 scale-105" x-transition:enter-end="opacity-100 scale-100" class="relative min-h-[500px] md:min-h-[600px] lg:min-h-[650px] flex items-center" style="background: linear-gradient(135deg, rgba(44,36,24,0.7), rgba(44,36,24,0.4)), url('https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=1920&q=80') center/cover;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-20 w-full">
            <div class="max-w-2xl animate-fadeInUp">
                <span class="inline-block text-[11px] font-bold uppercase tracking-[0.3em] text-gold-300 bg-white/10 backdrop-blur-sm px-4 py-1.5 rounded-full mb-6 border border-white/20">Heritage Ayurveda</span>
                <h1 class="font-display text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold text-white leading-[1.05] mb-6">
                    Ancient wisdom.<br><span class="text-gold-300">Modern purity.</span>
                </h1>
                <p class="text-cream-200/90 text-base md:text-lg max-w-lg mb-8 leading-relaxed">
                    Formulations rooted in 5,000 years of Ayurvedic tradition, crafted with single-origin herbs from Rajasthan.
                </p>
                <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-white text-espresso-700 font-bold rounded-full hover:bg-cream-100 transition shadow-2xl">
                    Shop Collection
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Slide 2 -->
    <div x-show="current === 1" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 scale-105" x-transition:enter-end="opacity-100 scale-100" x-cloak class="relative min-h-[500px] md:min-h-[600px] lg:min-h-[650px] flex items-center" style="background: linear-gradient(135deg, rgba(176,136,64,0.85), rgba(150,112,58,0.75)), url('https://images.unsplash.com/photo-1611241893603-3c359704e0ee?w=1920&q=80') center/cover;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-20 w-full text-center">
            <span class="inline-block text-[11px] font-bold uppercase tracking-[0.3em] text-white bg-white/20 backdrop-blur-sm px-4 py-1.5 rounded-full mb-6">Limited Offer</span>
            <h2 class="font-display text-4xl sm:text-5xl md:text-6xl font-bold text-white mb-4">Flat 15% Off</h2>
            <p class="text-white/90 text-lg mb-8">Use code <span class="font-bold text-white text-2xl bg-white/20 px-3 py-1 rounded-lg">EXTRA15</span> on orders above ₹999</p>
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-white text-espresso-700 font-bold rounded-full hover:bg-cream-100 transition shadow-2xl">
                Claim Offer →
            </a>
        </div>
    </div>

    <!-- Slide 3 -->
    <div x-show="current === 2" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 scale-105" x-transition:enter-end="opacity-100 scale-100" x-cloak class="relative min-h-[500px] md:min-h-[600px] lg:min-h-[650px] flex items-center" style="background: linear-gradient(135deg, rgba(44,36,24,0.85), rgba(44,36,24,0.7)), url('https://images.unsplash.com/photo-1471864190281-a93a3070b6de?w=1920&q=80') center/cover;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-20 w-full text-center">
            <span class="inline-block text-[11px] font-bold uppercase tracking-[0.3em] text-gold-300 bg-gold-400/10 backdrop-blur-sm px-4 py-1.5 rounded-full mb-6 border border-gold-400/20">New Launch</span>
            <h2 class="font-display text-4xl sm:text-5xl md:text-6xl font-bold text-white mb-4">Shilajit Gold Resin</h2>
            <p class="text-cream-200/90 text-lg mb-8">Pure Himalayan Shilajit for energy & stamina</p>
            <a href="{{ route('products.show', 'shilajit-gold-resin') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-gold-500 text-white font-bold rounded-full hover:bg-gold-400 transition shadow-2xl">
                Discover Now →
            </a>
        </div>
    </div>

    <!-- Slider Dots -->
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex items-center gap-2.5">
        <template x-for="i in slides" :key="i">
            <button @click="current = i - 1" :class="current === i - 1 ? 'w-10 bg-white' : 'w-3 bg-white/40'" class="h-3 rounded-full transition-all duration-500"></button>
        </template>
    </div>
</section>

<!-- Scrolling Marquee Trust Strip -->
<div class="overflow-hidden py-2.5" style="background-color: #342a1b;">
    <div class="animate-marquee flex items-center gap-8 whitespace-nowrap">
        @for($m = 0; $m < 2; $m++)
        <span class="flex items-center gap-2 text-xs font-semibold text-white/90"><svg class="w-4 h-4 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg> Secure Payments — UPI, Cards & COD</span>
        <span class="text-gold-500">✦</span>
        <span class="flex items-center gap-2 text-xs font-semibold text-white/90"><svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Pure Herbs — No Extract, No Chemicals</span>
        <span class="text-gold-500">✦</span>
        <span class="flex items-center gap-2 text-xs font-semibold text-white/90"><svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg> Free Delivery — On orders above ₹{{ config('shivara.free_shipping_threshold', 399) }}</span>
        <span class="text-gold-500">✦</span>
        <span class="flex items-center gap-2 text-xs font-semibold text-white/90"><svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg> Lab Tested — GMP Certified Products</span>
        <span class="text-gold-500">✦</span>
        <span class="flex items-center gap-2 text-xs font-semibold text-white/90"><svg class="w-4 h-4 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg> 5000+ Happy Customers Across India</span>
        <span class="text-gold-500 mr-8">✦</span>
        @endfor
    </div>
</div>

<!-- Shop by Category -->
<section class="py-16 md:py-20 scroll-reveal" style="background-color: #F5EFE6;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-12">
            <span class="text-[11px] font-bold uppercase tracking-[0.3em] text-gold-500">Shop by Concern</span>
            <h2 class="font-display text-3xl md:text-5xl font-bold text-espresso-700 mt-3">Find your balance.</h2>
        </div>
        <div class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-6 gap-3 sm:gap-4">
            @foreach($categories as $cat)
            <a href="{{ route('products.index', ['category' => $cat->slug]) }}" class="group text-center">
                <div class="relative mx-auto w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 mb-2 sm:mb-3 rounded-full overflow-hidden border-2 border-gold-100 group-hover:border-gold-400 shadow-sm group-hover:shadow-lg transition-all duration-300 group-hover:scale-105">
                    @if($cat->image)
                    <img src="{{ str_starts_with($cat->image, '/storage/') ? '/public' . $cat->image : $cat->image }}" alt="{{ $cat->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    @else
                    <div class="w-full h-full bg-gradient-to-br from-gold-50 to-gold-100 flex items-center justify-center">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                    </div>
                    @endif
                </div>
                <h3 class="text-[10px] sm:text-xs font-bold text-espresso-700 group-hover:text-gold-600 transition uppercase tracking-wider leading-tight">{{ $cat->name }}</h3>
                <p class="text-[9px] sm:text-[10px] text-espresso-300 mt-0.5">{{ $cat->products_count }} products</p>
            </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Products with Category Tabs -->
<section class="py-16 md:py-20 scroll-reveal" style="background-color: #FFFDF8;" x-data="{ activeTab: 'all' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-8">
            <span class="text-[11px] font-bold uppercase tracking-[0.3em] text-gold-500">Shop by Concern</span>
            <h2 class="font-display text-3xl md:text-5xl font-bold text-espresso-700 mt-2">Featured Products.</h2>
        </div>

        <!-- Category Tabs -->
        <div class="flex flex-wrap justify-center gap-2 mb-10">
            <button @click="activeTab = 'all'" :class="activeTab === 'all' ? 'text-white' : 'bg-white text-espresso-600 border border-gold-200 hover:border-gold-400'" :style="activeTab === 'all' ? 'background-color:#2C2418' : ''" class="px-4 py-2 text-xs font-bold uppercase tracking-wider rounded-full transition">All</button>
            @foreach($categories->take(5) as $tabCat)
            <button @click="activeTab = '{{ $tabCat->slug }}'" :class="activeTab === '{{ $tabCat->slug }}' ? 'text-white' : 'bg-white text-espresso-600 border border-gold-200 hover:border-gold-400'" :style="activeTab === '{{ $tabCat->slug }}' ? 'background-color:#2C2418' : ''" class="px-4 py-2 text-xs font-bold uppercase tracking-wider rounded-full transition">{{ $tabCat->name }}</button>
            @endforeach
        </div>

        <!-- Products Grid (2 rows x 4) -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-5">
            @foreach($featuredProducts as $product)
            <div x-show="activeTab === 'all' || activeTab === '{{ $product->category->slug ?? '' }}'" x-transition>
                @include('partials.product-card', ['product' => $product])
            </div>
            @endforeach
        </div>

        <!-- View All Button -->
        <div class="text-center mt-10">
            <a :href="activeTab === 'all' ? '{{ route('products.index') }}' : '{{ route('products.index') }}?category=' + activeTab" class="inline-flex items-center gap-2 px-8 py-3.5 text-white text-sm font-bold uppercase tracking-wider rounded-full hover:opacity-90 transition shadow-lg" style="background-color:#2C2418">
                View All Products
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>

<!-- Amazing Deals - Horizontal Slider -->
@php
    $amazingDeals = \App\Models\Product::where('is_active', true)
        ->whereColumn('mrp', '>', 'selling_price')
        ->with(['primaryImage', 'images', 'category', 'variants'])
        ->orderByRaw('((mrp - selling_price) / mrp) DESC')
        ->take(12)
        ->get()
        ->filter(function($p) { return $p->discount_percent > 10; });
@endphp
@if($amazingDeals->count())
<section class="py-16 md:py-20 scroll-reveal" style="background-color: #F5EFE6;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="flex items-end justify-between mb-8">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-[0.3em] text-gold-500">Limited Time</span>
                <h2 class="font-display text-3xl md:text-5xl font-bold text-espresso-700 mt-2">Amazing Deals.</h2>
            </div>
            <div class="flex items-center gap-2">
                <button data-dir="left" class="w-9 h-9 rounded-full border border-gold-200 flex items-center justify-center hover:bg-white transition">
                    <svg class="w-4 h-4 text-espresso-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button data-dir="right" class="w-9 h-9 rounded-full border border-gold-200 flex items-center justify-center hover:bg-white transition">
                    <svg class="w-4 h-4 text-espresso-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>
        <div id="dealsSlider" class="flex gap-4 overflow-x-hidden pb-4 relative">
            <div id="dealsTrack" class="flex gap-4 transition-transform duration-500 ease-out">
            @foreach($amazingDeals as $product)
            <div class="shrink-0 w-[180px] sm:w-[230px] md:w-[260px]">
                @include('partials.product-card', ['product' => $product])
            </div>
            @endforeach
            <!-- Duplicate for infinite loop -->
            @foreach($amazingDeals->take(4) as $product)
            <div class="shrink-0 w-[180px] sm:w-[230px] md:w-[260px]">
                @include('partials.product-card', ['product' => $product])
            </div>
            @endforeach
            </div>
        </div>

<script>
(function() {
    const container = document.getElementById('dealsSlider');
    const track = document.getElementById('dealsTrack');
    if (!container || !track) return;

    let position = 0;
    let autoInterval;
    let isDragging = false;
    let dragStartX = 0;
    let dragStartPos = 0;
    let velocity = 0;
    let lastX = 0;
    let lastTime = 0;
    const speed = 0.5; // auto-scroll speed (px per frame)
    const totalWidth = track.scrollWidth - container.clientWidth;

    function setPosition(pos) {
        position = pos;
        // Loop back when reaching end
        if (position >= totalWidth) position = 0;
        if (position < 0) position = totalWidth - 10;
        track.style.transform = 'translateX(-' + position + 'px)';
    }

    // Auto scroll
    function startAuto() {
        autoInterval = requestAnimationFrame(function tick() {
            if (!isDragging) {
                setPosition(position + speed);
            }
            autoInterval = requestAnimationFrame(tick);
        });
    }
    startAuto();

    // Mouse drag
    container.addEventListener('mousedown', function(e) {
        isDragging = true;
        dragStartX = e.clientX;
        dragStartPos = position;
        lastX = e.clientX;
        lastTime = Date.now();
        velocity = 0;
        track.style.transition = 'none';
        container.style.cursor = 'grabbing';
        e.preventDefault();
    });

    document.addEventListener('mousemove', function(e) {
        if (!isDragging) return;
        var dx = dragStartX - e.clientX;
        var now = Date.now();
        velocity = (lastX - e.clientX) / (now - lastTime + 1);
        lastX = e.clientX;
        lastTime = now;
        track.style.transform = 'translateX(-' + (dragStartPos + dx) + 'px)';
    });

    document.addEventListener('mouseup', function() {
        if (!isDragging) return;
        isDragging = false;
        container.style.cursor = 'grab';
        // Apply momentum
        var momentum = velocity * 150;
        position = dragStartPos + (dragStartX - lastX) + momentum;
        if (position < 0) position = 0;
        if (position > totalWidth) position = totalWidth;
        track.style.transition = 'transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
        track.style.transform = 'translateX(-' + position + 'px)';
        setTimeout(function() { track.style.transition = 'none'; }, 600);
    });

    // Touch drag (mobile)
    container.addEventListener('touchstart', function(e) {
        isDragging = true;
        dragStartX = e.touches[0].clientX;
        dragStartPos = position;
        lastX = e.touches[0].clientX;
        lastTime = Date.now();
        velocity = 0;
        track.style.transition = 'none';
    }, { passive: true });

    container.addEventListener('touchmove', function(e) {
        if (!isDragging) return;
        var dx = dragStartX - e.touches[0].clientX;
        var now = Date.now();
        velocity = (lastX - e.touches[0].clientX) / (now - lastTime + 1);
        lastX = e.touches[0].clientX;
        lastTime = now;
        track.style.transform = 'translateX(-' + (dragStartPos + dx) + 'px)';
    }, { passive: true });

    container.addEventListener('touchend', function() {
        if (!isDragging) return;
        isDragging = false;
        var momentum = velocity * 120;
        position = dragStartPos + (dragStartX - lastX) + momentum;
        if (position < 0) position = 0;
        if (position > totalWidth) position = totalWidth;
        track.style.transition = 'transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
        track.style.transform = 'translateX(-' + position + 'px)';
        setTimeout(function() { track.style.transition = 'none'; }, 500);
    });

    // Arrow buttons
    container.parentElement.querySelectorAll('button[data-dir]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var dir = this.dataset.dir === 'left' ? -1 : 1;
            position += dir * 300;
            if (position < 0) position = 0;
            if (position > totalWidth) position = totalWidth;
            track.style.transition = 'transform 0.5s ease';
            track.style.transform = 'translateX(-' + position + 'px)';
            setTimeout(function() { track.style.transition = 'none'; }, 500);
        });
    });
})();
</script>
        <!-- Dot Navigation -->
        <div class="flex justify-center gap-2 mt-4">
            @for($dot = 0; $dot < min(5, ceil($amazingDeals->count() / 3)); $dot++)
            <button onclick="var t=document.getElementById('dealsTrack');t.style.transition='transform 0.5s ease';t.style.transform='translateX(-{{ $dot * 280 * 3 }}px)';setTimeout(()=>t.style.transition='none',500)" class="w-2.5 h-2.5 rounded-full bg-gold-200 hover:bg-gold-500 transition"></button>
            @endfor
        </div>
    </div>
</section>

<!-- Trust Badges -->
<section class="border-y border-gold-200/50 py-8" style="background-color: #FBF7F0;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="flex items-center gap-3 justify-center">
                <div class="w-10 h-10 bg-gold-100 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-gold-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                </div>
                <div><p class="text-xs font-bold text-espresso-700 uppercase tracking-wider">100% Natural</p><p class="text-[10px] text-espresso-400">Pure Ayurvedic</p></div>
            </div>
            <div class="flex items-center gap-3 justify-center">
                <div class="w-10 h-10 bg-olive-100 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-olive-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div><p class="text-xs font-bold text-espresso-700 uppercase tracking-wider">GMP Certified</p><p class="text-[10px] text-espresso-400">Lab Tested</p></div>
            </div>
            <div class="flex items-center gap-3 justify-center">
                <div class="w-10 h-10 bg-gold-100 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-gold-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div><p class="text-xs font-bold text-espresso-700 uppercase tracking-wider">Free Shipping</p><p class="text-[10px] text-espresso-400">Pan India</p></div>
            </div>
            <div class="flex items-center gap-3 justify-center">
                <div class="w-10 h-10 bg-olive-100 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-olive-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                </div>
                <div><p class="text-xs font-bold text-espresso-700 uppercase tracking-wider">Easy Returns</p><p class="text-[10px] text-espresso-400">7-Day Policy</p></div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- About Us Banner -->
<section class="py-16 md:py-24 scroll-reveal" style="background-color: #F5EFE6;">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div class="order-2 md:order-1">
                <span class="text-[11px] font-bold uppercase tracking-[0.3em] text-gold-500 mb-4 block">About Shivara</span>
                <h2 class="font-display text-3xl md:text-4xl lg:text-5xl font-bold text-espresso-700 leading-tight mb-6">Rooted in tradition.<br>Built on trust.</h2>
                <p class="text-espresso-400 leading-relaxed mb-4">
                    At Shivara, every product is a promise — a promise of purity, efficacy, and reverence for the ancient science of Ayurveda. We source the finest single-origin herbs from heritage farms in Rajasthan.
                </p>
                <p class="text-espresso-400 leading-relaxed mb-8">
                    Our formulations are crafted in GMP-certified facilities, tested in certified laboratories, and delivered with the care your wellness deserves. No shortcuts. No compromises.
                </p>
                <div class="flex flex-wrap gap-8">
                    <div><p class="text-3xl font-display font-bold text-gold-600">5K+</p><p class="text-[10px] uppercase tracking-[0.2em] text-espresso-400">Happy Customers</p></div>
                    <div><p class="text-3xl font-display font-bold text-gold-600">50+</p><p class="text-[10px] uppercase tracking-[0.2em] text-espresso-400">Products</p></div>
                    <div><p class="text-3xl font-display font-bold text-gold-600">4.8★</p><p class="text-[10px] uppercase tracking-[0.2em] text-espresso-400">Avg Rating</p></div>
                </div>
            </div>
            <div class="order-1 md:order-2 relative">
                <div class="aspect-[4/5] rounded-3xl overflow-hidden border-4 border-white shadow-2xl">
                    <img src="https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=600&h=750&fit=crop" alt="Ayurvedic herbs and formulations" class="w-full h-full object-cover">
                </div>
                <div class="absolute -bottom-4 -left-4 w-24 h-24 bg-olive-200 rounded-2xl -z-10"></div>
                <div class="absolute -top-4 -right-4 w-16 h-16 bg-gold-300 rounded-full -z-10"></div>
            </div>
        </div>
    </div>
</section>

<!-- Video Testimonials Section -->
@php $videoTestimonials = \App\Models\VideoTestimonial::active()->with('product')->orderBy('sort_order')->take(10)->get(); @endphp
@if($videoTestimonials->count())
<section class="py-16 md:py-20 scroll-reveal" style="background-color: #FFFDF8;" x-data="{ openVideo: null, openUrl: '' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-10">
            <h2 class="font-display text-3xl md:text-4xl font-bold text-espresso-700">Real Customers, Real Reviews</h2>
        </div>
        <div class="flex gap-4 overflow-x-auto scrollbar-hide pb-4">
            @foreach($videoTestimonials as $vt)
            <div @click="openVideo = {{ $vt->id }}; openUrl = '{{ $vt->video_type === 'youtube' ? 'https://www.youtube.com/embed/' . $vt->embed_url . '?autoplay=1&rel=0' : $vt->embed_url }}'" class="shrink-0 w-[180px] sm:w-[200px] cursor-pointer group">
                <div class="relative aspect-[3/4] rounded-2xl overflow-hidden bg-gray-100 shadow-sm group-hover:shadow-xl transition">
                    <img src="{{ $vt->thumbnail_url }}" alt="{{ $vt->customer_name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                    <!-- Play icon -->
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-12 h-12 bg-white/90 rounded-full flex items-center justify-center shadow-lg group-hover:scale-110 transition">
                            <svg class="w-5 h-5 text-espresso-700 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        </div>
                    </div>
                    <!-- Bottom info -->
                    <div class="absolute bottom-0 left-0 right-0 p-3">
                        @if($vt->product)
                        <p class="text-white text-xs font-semibold truncate">{{ $vt->product->name }}</p>
                        <p class="text-white/80 text-[10px]">₹{{ number_format($vt->product->selling_price) }}</p>
                        @else
                        <p class="text-white text-xs font-semibold">{{ $vt->customer_name }}</p>
                        @endif
                        <div class="flex items-center gap-1 mt-1">
                            @for($s = 1; $s <= $vt->rating; $s++)
                            <svg class="w-3 h-3 text-amber-400 fill-amber-400" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            @endfor
                            @if($vt->is_verified)<span class="text-[9px] text-green-300 ml-1">✓ Verified</span>@endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Video Lightbox Popup (outside the scroll container) -->
    <template x-if="openVideo">
        <div x-transition.opacity @click.self="openVideo = null; openUrl = ''" @keydown.escape.window="openVideo = null; openUrl = ''" class="fixed inset-0 z-[300] bg-black/90 flex items-center justify-center p-4">
            <div class="relative w-full max-w-sm bg-black rounded-3xl overflow-hidden shadow-2xl" @click.stop>
                <button @click="openVideo = null; openUrl = ''" class="absolute top-3 right-3 z-10 w-8 h-8 bg-white/20 hover:bg-white/40 rounded-full flex items-center justify-center text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <!-- Video -->
                <div class="aspect-[9/16] bg-black">
                    <iframe class="w-full h-full" :src="openUrl" frameborder="0" allow="autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <!-- Product card -->
                @foreach($videoTestimonials as $vt)
                @if($vt->product)
                <div x-show="openVideo === {{ $vt->id }}" class="p-4 flex items-center gap-3 bg-white">
                    @php $prodImg = $vt->product->primary_image_url; @endphp
                    <div class="w-11 h-11 rounded-lg overflow-hidden bg-gray-100 shrink-0">
                        @if($prodImg)<img src="{{ $prodImg }}" class="w-full h-full object-cover">@endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-espresso-700 truncate">{{ $vt->product->name }}</p>
                        <p class="text-xs text-espresso-500">₹{{ number_format($vt->product->selling_price) }}</p>
                    </div>
                    <a href="{{ route('products.show', $vt->product->slug) }}" class="px-4 py-2 text-white text-xs font-bold rounded-lg hover:opacity-90 transition" style="background-color:#2C2418">Shop Now</a>
                </div>
                @endif
                @endforeach
            </div>
        </div>
    </template>
</section>
@endif

<!-- Customer Reviews Slider -->
<section class="py-16 md:py-20 scroll-reveal" style="background-color: rgb(44, 36, 24);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-10">
            <span class="text-[11px] font-bold uppercase tracking-[0.3em] text-gold-400">Customer Love</span>
            <h2 class="font-display text-3xl md:text-4xl font-bold text-cream-50 mt-3">Real Reviews from Real People.</h2>
        </div>
        @php
            $homeReviews = \App\Models\Review::where('is_approved', true)
                ->with(['user', 'product'])
                ->latest()
                ->take(10)
                ->get();
            if($homeReviews->isEmpty()) {
                $homeReviews = collect([
                    (object)['user' => (object)['name' => 'Priya S.'], 'product' => (object)['name' => 'Madhu Balance'], 'rating' => 5, 'comment' => 'Madhu Balance Capsules have transformed my daily routine. My sugar levels are stable and I feel more energetic.', 'images' => null],
                    (object)['user' => (object)['name' => 'Rahul M.'], 'product' => (object)['name' => 'Shilajit Gold'], 'rating' => 5, 'comment' => 'The Shilajit Gold Resin is pure gold! I can feel the difference in my stamina within weeks of usage.', 'images' => null],
                    (object)['user' => (object)['name' => 'Anita K.'], 'product' => (object)['name' => 'Hair Growth Oil'], 'rating' => 5, 'comment' => 'Finally found an Ayurvedic brand I can trust. The packaging is premium and products are genuine.', 'images' => null],
                    (object)['user' => (object)['name' => 'Deepak R.'], 'product' => (object)['name' => 'Joint Support'], 'rating' => 4, 'comment' => 'Great product for joint pain. Noticed improvement in just 2 weeks. Will continue using.', 'images' => null],
                    (object)['user' => (object)['name' => 'Meera J.'], 'product' => (object)['name' => 'Liver Detox'], 'rating' => 5, 'comment' => 'Best ayurvedic brand I have used. Products are authentic and results are visible. Highly recommend!', 'images' => null],
                ]);
            }
        @endphp
        <div class="relative" x-data="{ revSlide: 0 }" x-init="setInterval(() => revSlide = (revSlide + 1) % {{ ceil($homeReviews->count() / 3) }}, 5000)">
            <div class="overflow-hidden">
                <div class="flex transition-transform duration-700" :style="'transform: translateX(-' + (revSlide * 100) + '%)'">
                    @foreach($homeReviews->chunk(3) as $chunk)
                    <div class="w-full flex-shrink-0 grid md:grid-cols-3 gap-5 px-1">
                        @foreach($chunk as $rev)
                        <div class="bg-white/5 border border-gold-400/10 rounded-2xl p-6 backdrop-blur-sm">
                            <div class="flex items-center gap-0.5 mb-3">
                                @for($s = 1; $s <= 5; $s++)
                                <svg class="w-4 h-4 {{ $s <= $rev->rating ? 'text-gold-400 fill-gold-400' : 'text-gray-600 fill-gray-600' }}" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                @endfor
                            </div>
                            <p class="text-cream-200 text-sm leading-relaxed mb-4 line-clamp-3">"{{ $rev->comment }}"</p>
                            @if($rev->images && count($rev->images))
                            <div class="flex gap-1.5 mb-3">
                                @foreach(array_slice($rev->images, 0, 3) as $rImg)
                                <div class="w-10 h-10 rounded-lg overflow-hidden border border-white/10">
                                    <img src="{{ str_starts_with($rImg, '/storage/') ? '/public' . $rImg : $rImg }}" class="w-full h-full object-cover" loading="lazy">
                                </div>
                                @endforeach
                            </div>
                            @endif
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-[10px] font-bold text-white" style="background-color:#B08840">{{ substr($rev->user->name ?? 'C', 0, 1) }}</div>
                                <div>
                                    <p class="text-gold-300 text-xs font-semibold">{{ $rev->user->name ?? 'Customer' }}</p>
                                    <p class="text-[10px] text-cream-300/50">{{ $rev->product->name ?? 'Verified Buyer' }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endforeach
                </div>
            </div>
            <!-- Slider dots -->
            @if($homeReviews->count() > 3)
            <div class="flex justify-center gap-2 mt-6">
                @for($d = 0; $d < ceil($homeReviews->count() / 3); $d++)
                <button @click="revSlide = {{ $d }}" :class="revSlide === {{ $d }} ? 'w-8 bg-gold-400' : 'w-3 bg-white/20'" class="h-2.5 rounded-full transition-all"></button>
                @endfor
            </div>
            @endif
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-16 md:py-20 scroll-reveal" style="background-color: #FBF7F0;">
    <div class="max-w-3xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-12">
            <span class="text-[11px] font-bold uppercase tracking-[0.3em] text-gold-500">Have Questions?</span>
            <h2 class="font-display text-3xl md:text-4xl font-bold text-espresso-700 mt-3">Frequently Asked</h2>
        </div>
        <div class="space-y-3" x-data="{ open: null }">
            @php $faqs = [
                ['q'=>'Are your products 100% natural?','a'=>'Yes! All Shivara products are made from pure, natural Ayurvedic ingredients sourced from heritage farms in Rajasthan. We use no artificial preservatives, colors, or chemicals.'],
                ['q'=>'How long before I see results?','a'=>'Ayurvedic products work holistically with your body. Most customers report noticeable improvements within 2-4 weeks of consistent use. For chronic conditions, we recommend 3-6 months.'],
                ['q'=>'Do you offer free shipping?','a'=>'Yes! We offer free standard shipping on all orders across India. Express delivery is available for ₹149.'],
                ['q'=>'What is your return policy?','a'=>'We offer a 7-day hassle-free return policy. If you are not satisfied with any product, simply contact us within 7 days of delivery for a full refund.'],
                ['q'=>'Are Shivara products safe to use with other medications?','a'=>'Our products are generally safe, but we always recommend consulting your physician before starting any new supplement, especially if you are on prescription medication.'],
            ]; @endphp
            @foreach($faqs as $i => $faq)
            <div class="bg-white rounded-2xl border border-gold-100/50 overflow-hidden transition-all duration-300" :class="open === {{ $i }} ? 'shadow-lg' : ''">
                <button @click="open = open === {{ $i }} ? null : {{ $i }}" class="w-full text-left px-6 py-5 flex items-center justify-between gap-4">
                    <span class="text-sm font-semibold text-espresso-700">{{ $faq['q'] }}</span>
                    <svg :class="open === {{ $i }} ? 'rotate-45' : ''" class="w-5 h-5 text-gold-500 shrink-0 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </button>
                <div x-show="open === {{ $i }}" x-collapse x-cloak>
                    <p class="px-6 pb-5 text-sm text-espresso-400 leading-relaxed">{{ $faq['a'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA Banner -->
<section class="py-16 md:py-20 scroll-reveal" style="background: linear-gradient(135deg, #2C2418 0%, #4A3828 100%);">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center">
        <span class="inline-block text-[11px] font-bold uppercase tracking-[0.3em] text-gold-400 bg-gold-400/10 px-4 py-1.5 rounded-full mb-6 border border-gold-400/20">Start Your Wellness Journey</span>
        <h2 class="font-display text-3xl md:text-5xl font-bold text-white mb-4 leading-tight">Transform Your Health<br>with Ancient Ayurveda.</h2>
        <p class="text-cream-300/70 text-base md:text-lg max-w-2xl mx-auto mb-8">Join 5000+ customers who trust Shivara for 100% natural, lab-tested Ayurvedic products. Free shipping on all orders.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 text-white font-bold text-sm uppercase tracking-wider rounded-full hover:opacity-90 transition shadow-xl" style="background-color:#B08840">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                Shop Now
            </a>
            <a href="{{ route('contact') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white/10 text-white font-bold text-sm uppercase tracking-wider rounded-full hover:bg-white/20 transition border border-white/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                Contact Us
            </a>
        </div>
    </div>
</section>

<!-- Blog Section -->
@php
    $latestBlogs = \App\Models\Blog::where('is_published', true)->whereNotNull('published_at')->latest('published_at')->take(3)->get();
@endphp
@if($latestBlogs->count())
<section class="py-16 md:py-20 scroll-reveal" style="background-color: #FFFDF8;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="flex items-end justify-between mb-10">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-[0.3em] text-gold-500">Ayurvedic Wisdom</span>
                <h2 class="font-display text-3xl md:text-5xl font-bold text-espresso-700 mt-2">From Our Blog.</h2>
            </div>
            <a href="{{ route('blog.index') }}" class="hidden sm:flex items-center gap-2 px-5 py-2.5 border-2 border-espresso-700 text-espresso-700 text-xs font-bold uppercase tracking-wider rounded-full hover:bg-espresso-700 hover:text-cream-50 transition">
                All Articles <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            @foreach($latestBlogs as $blog)
            <a href="{{ route('blog.show', $blog->slug) }}" class="group bg-white rounded-2xl border border-gold-100/50 overflow-hidden hover:shadow-xl transition-all duration-400 hover:-translate-y-1">
                <div class="aspect-[16/10] overflow-hidden bg-cream-100">
                    @if($blog->featured_image)
                    <img src="{{ str_starts_with($blog->featured_image, '/storage/') ? '/public' . $blog->featured_image : $blog->featured_image }}" alt="{{ $blog->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                    @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gold-50 to-gold-100">
                        <svg class="w-12 h-12 text-gold-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    </div>
                    @endif
                </div>
                <div class="p-5">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-gold-500">{{ $blog->category ?? 'Ayurveda' }}</span>
                        <span class="text-[10px] text-espresso-300">&bull;</span>
                        <span class="text-[10px] text-espresso-300">{{ $blog->published_at->format('M d, Y') }}</span>
                    </div>
                    <h3 class="text-sm font-bold text-espresso-700 group-hover:text-gold-600 transition line-clamp-2 leading-snug">{{ $blog->title }}</h3>
                    <p class="text-xs text-espresso-400 mt-2 line-clamp-2">{{ $blog->excerpt ?? Str::limit(strip_tags($blog->content), 100) }}</p>
                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-gold-600 mt-3 group-hover:gap-2 transition-all">Read More <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg></span>
                </div>
            </a>
            @endforeach
        </div>

        <!-- Read More Articles Button -->
        <div class="text-center mt-10">
            <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 px-8 py-3.5 text-white text-sm font-bold uppercase tracking-wider rounded-full hover:opacity-90 transition shadow-lg" style="background-color:#2C2418">
                Read More Articles
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>
@endif

<!-- Newsletter -->
<section class="py-16 md:py-20 scroll-reveal" style="background-color: #F5EFE6;">
    <div class="max-w-xl mx-auto px-4 sm:px-6 text-center">
        <span class="text-[11px] font-bold uppercase tracking-[0.3em] text-gold-500">Stay Connected</span>
        <h2 class="font-display text-3xl md:text-4xl font-bold text-espresso-700 mt-3 mb-4">Join the community.</h2>
        <p class="text-espresso-400 text-sm mb-8">Exclusive offers, Ayurvedic wisdom & new launches — straight to your inbox.</p>
        <form class="flex flex-col sm:flex-row gap-3">
            <input type="email" placeholder="Your email address" class="flex-1 px-5 py-4 bg-white border border-gold-200 rounded-xl text-sm text-espresso-700 placeholder:text-espresso-300 focus:outline-none focus:ring-2 focus:ring-gold-300 focus:border-gold-400">
            <button type="button" class="px-7 py-4 bg-gold-500 text-white text-sm font-bold uppercase tracking-wider rounded-xl hover:bg-gold-600 transition shadow-lg shadow-gold-500/20">Subscribe</button>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
@endpush
