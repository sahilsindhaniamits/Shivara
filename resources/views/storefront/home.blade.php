@extends('layouts.app')

@section('content')
<!-- Hero Banner Slider (Dynamic from Admin) -->
@php $heroBanners = \App\Models\Banner::active()->orderBy('sort_order')->get(); @endphp
<style>
.shivara-banner { margin-top: 0; }
.shivara-banner .banner-desktop { display: none !important; }
.shivara-banner .banner-mobile { display: block !important; min-height: calc(100vh - 70px); object-fit: cover; }
@media (min-width: 1024px) {
    .shivara-banner .banner-desktop { display: block !important; }
    .shivara-banner .banner-mobile { display: none !important; }
}
</style>
@if($heroBanners->count())
<section x-data="{ current: 0, slides: {{ $heroBanners->count() }} }" x-init="setInterval(() => current = (current + 1) % slides, 3000); initSwipe($el, () => current = (current+1)%slides, () => current = (current-1+slides)%slides)" class="relative overflow-hidden select-none cursor-grab active:cursor-grabbing shivara-banner">
    {{-- First slide: ALWAYS in DOM (relative, sets height), visibility controlled by opacity --}}
    @php $firstBanner = $heroBanners->first(); @endphp
    <div class="relative w-full overflow-hidden transition-opacity duration-700" :class="current === 0 ? 'opacity-100' : 'opacity-0'">
        @if($firstBanner->link)<a href="{{ $firstBanner->link }}" class="block w-full">@endif
        @if($firstBanner->mobile_image_url)
        <img src="{{ $firstBanner->mobile_image_url }}" alt="{{ $firstBanner->title }}" class="banner-mobile" style="width:100%;height:auto;" loading="eager" fetchpriority="high">
        <img src="{{ $firstBanner->image_url }}" alt="{{ $firstBanner->title }}" class="banner-desktop" style="width:100%;height:auto;" loading="eager" fetchpriority="high">
        @else
        <img src="{{ $firstBanner->image_url }}" alt="{{ $firstBanner->title }}" style="width:100%;height:auto;min-height:250px;object-fit:cover;" loading="eager" fetchpriority="high">
        @endif
        @if($firstBanner->link)</a>@endif
        @if($firstBanner->title || $firstBanner->subtitle)
        <div class="absolute inset-0 flex items-center pointer-events-none" style="background: linear-gradient(135deg, rgba(44,36,24,0.5), rgba(44,36,24,0.1));">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 w-full">
                <div class="max-w-xl">
                    @if($firstBanner->title)<h2 class="font-display text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-white leading-tight mb-3">{{ $firstBanner->title }}</h2>@endif
                    @if($firstBanner->subtitle)<p class="text-white/90 text-sm md:text-base max-w-md mb-6">{{ $firstBanner->subtitle }}</p>@endif
                    @if($firstBanner->link)<a href="{{ $firstBanner->link }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white text-sm font-bold rounded-full transition shadow-xl pointer-events-auto" style="color:#2C2418;">Shop Now <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg></a>@endif
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Other slides: absolute on top, use x-show --}}
    @foreach($heroBanners->slice(1)->values() as $idx => $banner)
    <div x-show="current === {{ $idx + 1 }}" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-cloak class="absolute inset-0 w-full h-full overflow-hidden">
        @if($banner->link)<a href="{{ $banner->link }}" class="block w-full h-full">@endif
        @if($banner->mobile_image_url)
        <img src="{{ $banner->mobile_image_url }}" alt="{{ $banner->title }}" class="banner-mobile" style="width:100%;height:100%;object-fit:cover;" loading="lazy">
        <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="banner-desktop" style="width:100%;height:100%;object-fit:cover;" loading="lazy">
        @else
        <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" style="width:100%;height:100%;object-fit:cover;" loading="lazy">
        @endif
        @if($banner->link)</a>@endif
        @if($banner->title || $banner->subtitle)
        <div class="absolute inset-0 flex items-center pointer-events-none" style="background: linear-gradient(135deg, rgba(44,36,24,0.5), rgba(44,36,24,0.1));">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 w-full">
                <div class="max-w-xl">
                    @if($banner->title)<h2 class="font-display text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-white leading-tight mb-3">{{ $banner->title }}</h2>@endif
                    @if($banner->subtitle)<p class="text-white/90 text-sm md:text-base max-w-md mb-6">{{ $banner->subtitle }}</p>@endif
                    @if($banner->link)<a href="{{ $banner->link }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white text-sm font-bold rounded-full transition shadow-xl pointer-events-auto" style="color:#2C2418;">Shop Now <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg></a>@endif
                </div>
            </div>
        </div>
        @endif
    </div>
    @endforeach

    <!-- Slider Dots -->
    @if($heroBanners->count() > 1)
    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex items-center gap-2.5">
        <template x-for="i in slides" :key="i">
            <button @click="current = i - 1" :class="current === i - 1 ? 'w-10 bg-white' : 'w-3 bg-white/40'" class="h-3 rounded-full transition-all duration-500"></button>
        </template>
    </div>
    @endif
</section>
@else
{{-- Fallback: show a default banner if no banners in DB --}}
<section class="relative min-h-[400px] md:min-h-[550px] lg:min-h-[650px] flex items-center" style="background: linear-gradient(135deg, rgba(44,36,24,0.7), rgba(44,36,24,0.4)), url('https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=1920&q=80') center/cover;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-20 w-full">
        <div class="max-w-2xl">
            <h1 class="font-display text-4xl sm:text-5xl md:text-6xl font-bold text-white leading-tight mb-6">Ancient wisdom.<br><span style="color:#D4B078;">Modern purity.</span></h1>
            <p class="text-white/80 text-lg mb-8">Formulations rooted in 5,000 years of Ayurvedic tradition.</p>
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-white font-bold rounded-full transition shadow-2xl" style="color:#2C2418;">Shop Collection →</a>
        </div>
    </div>
</section>
@endif

<!-- Scrolling Marquee Trust Strip -->
<div class="overflow-hidden py-1.5 sm:py-2" style="background-color: #342a1b;">
    <div class="flex items-center gap-6 sm:gap-8 whitespace-nowrap" style="animation: marquee 15s linear infinite;">
        @for($m = 0; $m < 2; $m++)
        <span class="flex items-center gap-1.5 text-[10px] sm:text-xs font-semibold text-white/90"><svg class="w-3 h-3 sm:w-4 sm:h-4 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg> Secure Payments — UPI, Cards & COD</span>
        <span class="text-gold-500 text-[10px]">✦</span>
        <span class="flex items-center gap-1.5 text-[10px] sm:text-xs font-semibold text-white/90"><svg class="w-3 h-3 sm:w-4 sm:h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Pure Herbs — No Extract, No Chemicals</span>
        <span class="text-gold-500 text-[10px]">✦</span>
        <span class="flex items-center gap-1.5 text-[10px] sm:text-xs font-semibold text-white/90"><svg class="w-3 h-3 sm:w-4 sm:h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg> Free Delivery — On orders above ₹{{ config('shivara.free_shipping_threshold', 999) }}</span>
        <span class="text-gold-500 text-[10px]">✦</span>
        <span class="flex items-center gap-1.5 text-[10px] sm:text-xs font-semibold text-white/90"><svg class="w-3 h-3 sm:w-4 sm:h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg> Lab Tested — GMP Certified</span>
        <span class="text-gold-500 text-[10px]">✦</span>
        <span class="flex items-center gap-1.5 text-[10px] sm:text-xs font-semibold text-white/90"><svg class="w-3 h-3 sm:w-4 sm:h-4 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg> 5000+ Happy Customers</span>
        <span class="text-gold-500 text-[10px] mr-6 sm:mr-8">✦</span>
        @endfor
    </div>
</div>

<!-- Shop by Category — Horizontal Scroll Cards -->
<section class="py-10 md:py-14 scroll-reveal" style="background-color:#f5efe6;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-6 md:mb-8">
            <span class="text-[11px] font-bold uppercase tracking-[0.3em]" style="color:#B7925C;">Shop by Concern</span>
            <h2 class="font-display text-2xl md:text-4xl font-bold mt-1" style="color:#2C2418;">Find your balance.</h2>
        </div>
    </div>
    @php
        $categoryImages = [
            'sexual wellness' => 'https://images.unsplash.com/photo-1611241893603-3c228ee0ae6f?w=400&h=400&fit=crop&q=80',
            'liver support' => 'https://images.unsplash.com/photo-1505751172876-fa1923c5c528?w=400&h=400&fit=crop&q=80',
            'sugar' => 'https://images.unsplash.com/photo-1550831107-1553da8c8464?w=400&h=400&fit=crop&q=80',
            'b.p' => 'https://images.unsplash.com/photo-1559757175-5700dde675bc?w=400&h=400&fit=crop&q=80',
            'hair care' => 'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=400&h=400&fit=crop&q=80',
            'joint care' => 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=400&h=400&fit=crop&q=80',
            'skin care' => 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=400&h=400&fit=crop&q=80',
            'weight loss' => 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?w=400&h=400&fit=crop&q=80',
            'immunity' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=400&h=400&fit=crop&q=80',
            'digestion' => 'https://images.unsplash.com/photo-1615485500704-8e990f9900f7?w=400&h=400&fit=crop&q=80',
        ];
    @endphp
    <div class="flex gap-5 overflow-x-auto pb-4 px-4 sm:px-6 scrollbar-hide snap-x snap-mandatory justify-center flex-wrap lg:flex-nowrap lg:justify-center lg:max-w-7xl lg:mx-auto">
        @foreach($categories as $catIdx => $cat)
        @php
            $catImage = null;
            if($cat->image) {
                $catImage = str_starts_with($cat->image, '/storage/') ? '/public' . $cat->image : $cat->image;
            } else {
                $catImage = $categoryImages[strtolower($cat->name)] ?? 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=400&h=400&fit=crop&q=80';
            }
        @endphp
        <a href="{{ route('products.index', ['category' => $cat->slug]) }}" class="group shrink-0 snap-start cat-card-animate" style="animation-delay: {{ $catIdx * 80 }}ms;">
            <div class="relative w-[150px] sm:w-[165px] md:w-[180px] overflow-hidden rounded-2xl" style="background-color:#f5efe6;">
                <!-- Square Image -->
                <div class="relative w-full overflow-hidden rounded-2xl" style="aspect-ratio:1/1;">
                    <img src="{{ $catImage }}" alt="{{ $cat->name }}" class="w-full h-full object-cover rounded-2xl" loading="lazy">
                    <!-- Glass slide effect on hover -->
                    <div class="absolute inset-0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700 ease-in-out" style="background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.4) 50%, transparent 100%);"></div>
                </div>
                <!-- Category name below -->
                <div class="pt-2.5 pb-1 text-center">
                    <h3 class="text-[11px] sm:text-xs font-bold uppercase tracking-wider transition-colors duration-300 group-hover:text-gold-600" style="color:#2C2418;">{{ $cat->name }}</h3>
                    @if($cat->products_count > 0)
                    <p class="text-[9px] mt-0.5" style="color:#a89070;">{{ $cat->products_count }} {{ $cat->products_count === 1 ? 'product' : 'products' }}</p>
                    @endif
                </div>
            </div>
        </a>
        @endforeach
    </div>
</section>
<style>
@keyframes catCardFadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
.cat-card-animate { opacity: 0; animation: catCardFadeUp 0.5s ease forwards; }
.scroll-reveal.revealed .cat-card-animate { opacity: 0; animation: catCardFadeUp 0.5s ease forwards; }
</style>

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
            @foreach($categories as $tabCat)
            <button @click="activeTab = '{{ $tabCat->slug }}'" :class="activeTab === '{{ $tabCat->slug }}' ? 'text-white' : 'bg-white text-espresso-600 border border-gold-200 hover:border-gold-400'" :style="activeTab === '{{ $tabCat->slug }}' ? 'background-color:#2C2418' : ''" class="px-4 py-2 text-xs font-bold uppercase tracking-wider rounded-full transition">{{ $tabCat->name }}</button>
            @endforeach
        </div>

        <!-- Products Grid (8 on All tab, filtered per category) -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-5">
            @foreach($featuredProducts as $fpIdx => $product)
            <div x-show="(activeTab === 'all' && {{ $fpIdx }} < 8) || activeTab === '{{ $product->category->slug ?? '' }}'" x-transition>
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
        ->where(function($q) { $q->whereNull('stock')->orWhere('stock', '>', 0); })
        ->with(['primaryImage', 'category'])
        ->orderByRaw('((mrp - selling_price) / mrp) DESC')
        ->take(12)
        ->get()
        ->unique('id')
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

<!-- Certification Logos — Infinite Scroll -->
<section class="py-6 overflow-hidden" style="background-color: #FBF7F0;">
    <div class="flex items-center" style="animation: certScroll 10s linear infinite;">
        @for($loop = 0; $loop < 3; $loop++)
        <img src="/public/our_manufacture_logo1.webp" alt="FSSAI" class="h-14 sm:h-16 md:h-20 mx-6 sm:mx-8 object-contain shrink-0">
        <img src="/public/our_manufacture_logo2.avif" alt="OHSAS" class="h-14 sm:h-16 md:h-20 mx-6 sm:mx-8 object-contain shrink-0">
        <img src="/public/our_manufacture_logo3.webp" alt="ISO" class="h-14 sm:h-16 md:h-20 mx-6 sm:mx-8 object-contain shrink-0">
        <img src="/public/our_manufacture_logo4.webp" alt="GMP" class="h-14 sm:h-16 md:h-20 mx-6 sm:mx-8 object-contain shrink-0">
        <img src="/public/ayushlogo.webp" alt="AYUSH" class="h-14 sm:h-16 md:h-20 mx-6 sm:mx-8 object-contain shrink-0">
        @endfor
    </div>
</section>
<style>
@keyframes certScroll { 0% { transform: translateX(0); } 100% { transform: translateX(-33.33%); } }
</style>
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
<section class="py-16 md:py-20 scroll-reveal" style="background-color: #FFFDF8;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-10">
            <h2 class="font-display text-3xl md:text-4xl font-bold" style="color:#2C2418;">Real Customers, Real Reviews</h2>
        </div>

        <!-- Auto-sliding Carousel -->
        <div x-data="videoTestimonials()" x-init="init()">

            <div class="relative">
                <div x-ref="carousel"
                     @mouseenter="paused = true"
                     @mouseleave="paused = false"
                     @touchstart.passive="paused = true"
                     @touchend.passive="setTimeout(() => paused = false, 5000)"
                     class="flex gap-4 overflow-x-auto pb-6 px-2 vtc-hide-scrollbar">

                    @foreach($videoTestimonials as $vt)
                    <div @click="
                        @if($vt->video_type === 'instagram')
                            window.open('{{ $vt->video_url }}', '_blank')
                        @else
                            openModal({{ $vt->id }}, '{{ $vt->video_type === 'youtube' ? 'https://www.youtube.com/embed/' . $vt->embed_url . '?autoplay=1&mute=0&rel=0&modestbranding=1&playsinline=1' : $vt->embed_url }}', {{ json_encode(['name' => $vt->product?->name, 'price' => $vt->product ? '₹' . number_format($vt->product->selling_price) : null, 'image' => $vt->product?->primary_image_url, 'url' => $vt->product ? route('products.show', $vt->product->slug) : null]) }})
                        @endif
                    " class="shrink-0 w-[155px] sm:w-[180px] md:w-[200px] cursor-pointer group transition-transform duration-300 hover:scale-105">
                        <div class="relative aspect-[9/16] rounded-2xl overflow-hidden shadow-md group-hover:shadow-2xl transition-all duration-300" style="border: 2px solid #e5e7eb; background-color: #1a1a1a;">
                            @if($vt->video_file)
                            {{-- MP4 autoplay muted --}}
                            <video autoplay muted loop playsinline class="w-full h-full object-cover" poster="{{ $vt->thumbnail_url }}">
                                <source src="{{ str_starts_with($vt->video_file, '/storage/') ? '/public' . $vt->video_file : $vt->video_file }}" type="video/mp4">
                            </video>
                            @elseif($vt->video_type === 'youtube')
                            {{-- YouTube iframe autoplay muted with controls hidden via CSS scaling --}}
                            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                                <iframe class="absolute top-1/2 left-1/2" style="width: 300%; height: 300%; transform: translate(-50%, -50%);" src="https://www.youtube.com/embed/{{ $vt->embed_url }}?autoplay=1&mute=1&loop=1&playlist={{ $vt->embed_url }}&controls=0&showinfo=0&rel=0&modestbranding=1&playsinline=1&disablekb=1&fs=0&iv_load_policy=3" frameborder="0" allow="autoplay; encrypted-media" loading="lazy"></iframe>
                            </div>
                            @else
                            <img src="{{ $vt->thumbnail_url }}" alt="{{ $vt->customer_name }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="mt-2.5 px-1">
                            @if($vt->product)
                            <p class="text-[11px] font-medium truncate" style="color:#2C2418;">{{ $vt->product->name }}</p>
                            <p class="text-xs font-bold mt-0.5" style="color:#2C2418;">₹{{ number_format($vt->product->selling_price, 2) }}</p>
                            @endif
                            <div class="flex items-center gap-0.5 mt-1">
                                @for($s = 1; $s <= $vt->rating; $s++)
                                <svg class="w-3 h-3" style="color:#f59e0b; fill:#f59e0b;" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                @endfor
                                <span class="text-[9px] ml-1" style="color:#6b7280;">Verified review</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Video Modal - placed OUTSIDE the section to avoid z-index/overflow issues --}}
<div x-data="videoTestimonialModal()" x-show="open" x-cloak
     style="display:none;"
     class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
     @keydown.escape.window="close()">
    {{-- Backdrop --}}
    <div class="absolute inset-0" style="background-color: rgba(0,0,0,0.8);" @click="close()"></div>

    {{-- Modal Content --}}
    <div class="relative w-full max-w-[360px] mx-auto" @click.stop>
        <!-- Close Button -->
        <button @click="close()"
                class="absolute top-3 right-3 z-30 w-9 h-9 rounded-full flex items-center justify-center"
                style="background-color: rgba(255,255,255,0.9);">
            <svg class="w-5 h-5" style="color:#2C2418;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <!-- Mute/Unmute Toggle -->
        <button @click="toggleMute()"
                class="absolute top-3 left-3 z-30 w-9 h-9 rounded-full flex items-center justify-center"
                style="background-color: rgba(255,255,255,0.9);">
            <svg x-show="!muted" class="w-5 h-5" style="color:#2C2418;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072M17.95 6.05a8 8 0 010 11.9M6 9H3v6h3l5 5V4L6 9z"/></svg>
            <svg x-show="muted" class="w-5 h-5" style="color:#2C2418;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707A1 1 0 0112 5v14a1 1 0 01-1.707.707L5.586 15zM17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"/></svg>
        </button>

        <!-- Video Frame -->
        <div class="relative rounded-2xl overflow-hidden shadow-2xl" style="background-color:#000; aspect-ratio: 9/16; max-height: 80vh;">
            <iframe x-ref="vtFrame" class="absolute inset-0 w-full h-full" :src="videoUrl" frameborder="0" allow="autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

            <!-- Product Card overlaid at bottom inside video -->
            <div x-show="product && product.name" class="absolute bottom-0 left-0 right-0 z-20 p-3">
                <div class="flex items-center gap-3 p-3 rounded-xl" style="background-color: rgba(255,255,255,0.95); backdrop-filter: blur(10px);">
                    <div class="w-11 h-11 rounded-lg overflow-hidden shrink-0" style="background-color:#f3f4f6;">
                        <img x-show="product && product.image" :src="product ? product.image : ''" class="w-full h-full object-cover" alt="">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold truncate" style="color:#2C2418;" x-text="product ? product.name : ''"></p>
                        <p class="text-xs font-semibold mt-0.5" style="color:#6b7280;" x-text="product ? product.price : ''"></p>
                    </div>
                    <a :href="product ? product.url : '#'"
                       class="px-4 py-2.5 text-white text-xs font-bold rounded-lg whitespace-nowrap"
                       style="background-color:#2C2418;">Shop Now</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.vtc-hide-scrollbar { scrollbar-width: none; -ms-overflow-style: none; }
.vtc-hide-scrollbar::-webkit-scrollbar { display: none; }
</style>

<script>
document.addEventListener('alpine:init', () => {
    // Carousel auto-slide
    Alpine.data('videoTestimonials', () => ({
        paused: false,
        autoSlideInterval: null,
        init() {
            this.startAutoSlide();
        },
        startAutoSlide() {
            this.autoSlideInterval = setInterval(() => {
                if (!this.paused && this.$refs.carousel) {
                    const el = this.$refs.carousel;
                    const maxScroll = el.scrollWidth - el.clientWidth;
                    if (el.scrollLeft >= maxScroll - 10) {
                        el.scrollTo({ left: 0, behavior: 'smooth' });
                    } else {
                        el.scrollBy({ left: 200, behavior: 'smooth' });
                    }
                }
            }, 3000);
        },
        openModal(id, url, product) {
            window.dispatchEvent(new CustomEvent('open-video-modal', { detail: { id, url, product } }));
        }
    }));

    // Modal (separate from section, avoids z-index issues)
    Alpine.data('videoTestimonialModal', () => ({
        open: false,
        videoUrl: '',
        product: null,
        muted: false,
        init() {
            window.addEventListener('open-video-modal', (e) => {
                this.videoUrl = e.detail.url;
                this.product = e.detail.product;
                this.muted = false;
                this.open = true;
                document.body.style.overflow = 'hidden';
            });
        },
        close() {
            this.open = false;
            this.videoUrl = '';
            this.product = null;
            document.body.style.overflow = '';
        },
        toggleMute() {
            this.muted = !this.muted;
            if (this.$refs.vtFrame) {
                let src = this.$refs.vtFrame.src;
                src = src.replace(/mute=[01]/, 'mute=' + (this.muted ? '1' : '0'));
                this.$refs.vtFrame.src = src;
            }
        }
    }));
});
</script>
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
        @endphp
        @if($homeReviews->count())
        <div class="relative" x-data="{ revSlide: 0 }" x-init="setInterval(() => revSlide = (revSlide + 1) % {{ $homeReviews->count() }}, 5000)">
            <div class="overflow-hidden">
                <!-- MOBILE: One review at a time -->
                <div class="md:hidden">
                    <div class="flex transition-transform duration-700 ease-in-out" :style="'transform: translateX(-' + (revSlide * 100) + '%)'">
                        @foreach($homeReviews as $rev)
                        <div class="w-full flex-shrink-0 px-1">
                            <div class="bg-white/5 border border-gold-400/10 rounded-2xl p-6 backdrop-blur-sm">
                                <div class="flex items-center gap-0.5 mb-3">
                                    @for($s = 1; $s <= 5; $s++)
                                    <svg class="w-4 h-4 {{ $s <= $rev->rating ? 'text-gold-400 fill-gold-400' : 'text-gray-600 fill-gray-600' }}" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                    @endfor
                                </div>
                                <p class="text-cream-200 text-sm leading-relaxed mb-4">"{{ $rev->comment }}"</p>
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
                        </div>
                        @endforeach
                    </div>
                </div>
                <!-- DESKTOP: 3 per slide -->
                <div class="hidden md:block">
                    <div class="flex transition-transform duration-700 ease-in-out" :style="'transform: translateX(-' + (Math.floor(revSlide / 3) * 100) + '%)'">
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
            </div>
            <!-- Slider dots -->
            <div class="flex justify-center gap-2 mt-6">
                <!-- Mobile dots (one per review) -->
                <div class="md:hidden flex gap-2">
                    @for($d = 0; $d < $homeReviews->count(); $d++)
                    <button @click="revSlide = {{ $d }}" :class="revSlide === {{ $d }} ? 'w-8 bg-gold-400' : 'w-3 bg-white/20'" class="h-2.5 rounded-full transition-all"></button>
                    @endfor
                </div>
                <!-- Desktop dots (one per group of 3) -->
                <div class="hidden md:flex gap-2">
                    @for($d = 0; $d < ceil($homeReviews->count() / 3); $d++)
                    <button @click="revSlide = {{ $d * 3 }}" :class="Math.floor(revSlide / 3) === {{ $d }} ? 'w-8 bg-gold-400' : 'w-3 bg-white/20'" class="h-2.5 rounded-full transition-all"></button>
                    @endfor
                </div>
            </div>
        </div>
        @else
        <!-- No reviews yet - show encouraging message -->
        <div class="text-center py-8">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center" style="background-color: rgba(183,146,92,0.15);">
                <svg class="w-7 h-7 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            </div>
            <p class="text-cream-200 text-sm mb-2">Be the first to share your experience!</p>
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-bold rounded-full transition" style="background-color:#B7925C; color:#fff;">Shop & Review</a>
        </div>
        @endif
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
        <form class="flex flex-col sm:flex-row gap-3" x-data="{ email: '', msg: '', success: false, sending: false }" @submit.prevent="
            sending = true; msg = '';
            fetch('{{ route('newsletter.subscribe') }}', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' }, body: JSON.stringify({ email: email }) })
            .then(r => r.json()).then(d => { sending = false; if(d.success) { success = true; msg = d.message; email = ''; } else { msg = d.message || 'Please enter a valid email.'; } })
            .catch(() => { sending = false; msg = 'Something went wrong. Try again.'; });">
            <input type="email" x-model="email" placeholder="Your email address" required class="flex-1 px-5 py-4 bg-white border border-gold-200 rounded-xl text-sm text-espresso-700 placeholder:text-espresso-300 focus:outline-none focus:ring-2 focus:ring-gold-300 focus:border-gold-400">
            <button type="submit" :disabled="sending" class="px-7 py-4 bg-gold-500 text-white text-sm font-bold uppercase tracking-wider rounded-xl hover:bg-gold-600 transition shadow-lg shadow-gold-500/20 disabled:opacity-50" x-text="sending ? 'Subscribing...' : 'Subscribe'"></button>
            <template x-if="msg"><p class="text-xs font-semibold mt-2 sm:mt-0 sm:self-center" :class="success ? 'text-green-600' : 'text-red-500'" x-text="msg"></p></template>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
@endpush
