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

<!-- Trust Bar -->
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

<!-- Shop by Category -->
<section class="py-16 md:py-20 scroll-reveal" style="background-color: #F5EFE6;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-12">
            <span class="text-[11px] font-bold uppercase tracking-[0.3em] text-gold-500">Shop by Concern</span>
            <h2 class="font-display text-3xl md:text-5xl font-bold text-espresso-700 mt-3">Find your balance.</h2>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($categories as $cat)
            <a href="{{ route('products.index', ['category' => $cat->slug]) }}" class="group text-center p-5 md:p-6 bg-white rounded-2xl border border-gold-100/50 hover:border-gold-300 hover:shadow-lg transition-all duration-400 hover:-translate-y-1">
                <div class="w-14 h-14 mx-auto mb-3 bg-gradient-to-br from-gold-50 to-gold-100 rounded-2xl flex items-center justify-center shadow-sm group-hover:shadow-md group-hover:scale-110 transition-all duration-300">
                    <svg class="w-6 h-6 text-gold-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <h3 class="text-xs font-bold text-espresso-700 group-hover:text-gold-600 transition uppercase tracking-wider">{{ $cat->name }}</h3>
                <p class="text-[10px] text-espresso-300 mt-1">{{ $cat->products_count }} products</p>
            </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Amazing Deals -->
<section class="py-16 md:py-20 scroll-reveal" style="background-color: #FFFDF8;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="flex items-end justify-between mb-10">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-[0.3em] text-gold-500">Limited Time</span>
                <h2 class="font-display text-3xl md:text-5xl font-bold text-espresso-700 mt-2">Amazing deals.</h2>
            </div>
            <a href="{{ route('products.index') }}" class="hidden sm:flex items-center gap-2 px-5 py-2.5 border-2 border-espresso-700 text-espresso-700 text-xs font-bold uppercase tracking-wider rounded-full hover:bg-espresso-700 hover:text-cream-50 transition">
                View All <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
            @forelse($featuredProducts as $product)
                @include('partials.product-card', ['product' => $product])
            @empty
                <p class="col-span-full text-center text-espresso-400 py-16">Products coming soon.</p>
            @endforelse
        </div>
    </div>
</section>

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

<!-- Testimonials -->
<section class="py-16 md:py-20 scroll-reveal" style="background-color: rgb(44, 36, 24);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-12">
            <span class="text-[11px] font-bold uppercase tracking-[0.3em] text-gold-400">Customer Love</span>
            <h2 class="font-display text-3xl md:text-4xl font-bold text-cream-50 mt-3">What they say.</h2>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            @php $testimonials = [
                ['name'=>'Priya S.','text'=>'Madhu Balance Capsules have transformed my daily routine. My sugar levels are stable and I feel more energetic.','rating'=>5],
                ['name'=>'Rahul M.','text'=>'The Shilajit Gold Resin is pure gold! I can feel the difference in my stamina within weeks.','rating'=>5],
                ['name'=>'Anita K.','text'=>'Finally found an Ayurvedic brand I can trust. The packaging is premium and products are genuine.','rating'=>5],
            ]; @endphp
            @foreach($testimonials as $t)
            <div class="bg-espresso-600/50 border border-gold-400/10 rounded-2xl p-6 backdrop-blur-sm">
                <div class="flex items-center gap-0.5 mb-3">
                    @for($s = 1; $s <= $t['rating']; $s++)
                    <svg class="w-4 h-4 text-gold-400 fill-gold-400" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    @endfor
                </div>
                <p class="text-cream-200 text-sm leading-relaxed mb-4">"{{ $t['text'] }}"</p>
                <p class="text-gold-400 text-sm font-semibold">— {{ $t['name'] }}</p>
            </div>
            @endforeach
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
