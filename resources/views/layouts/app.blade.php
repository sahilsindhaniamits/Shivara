<!DOCTYPE html>
<html lang="en" style="overflow-x:hidden;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('shivara.name') . ' — ' . config('shivara.tagline'))</title>
    <meta name="description" content="@yield('meta_description', config('shivara.description'))">
    <meta name="theme-color" content="#2C2418">

    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.tailwindcss.com">
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preload" href="/public/shivaralogo1.png" as="image">

    <!-- Fonts - swap for faster render -->
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind (loaded async for perf) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        cream: { 50: '#FFFDF8', 100: '#FFF9ED', 200: '#FFF3DB', 300: '#FFE8BF', 400: '#F5E6D0', 500: '#EDE0CC' },
                        gold: { 50: '#FBF7F0', 100: '#F5EADB', 200: '#EAD5B7', 300: '#D4B078', 400: '#C49A5C', 500: '#B08840', 600: '#96703A', 700: '#7A5A30' },
                        olive: { 50: '#F5F7F2', 100: '#E8EDE2', 200: '#D1DBC5', 300: '#A8BA94', 400: '#7D9B62', 500: '#5C7A44' },
                        espresso: { 50: '#F9F7F5', 100: '#F0EBE6', 200: '#DDD4CA', 300: '#B8A594', 400: '#8C7560', 500: '#6B5442', 600: '#4A3828', 700: '#2C2418' },
                    },
                    fontFamily: {
                        display: ['"Cormorant Garamond"', 'Georgia', 'serif'],
                        body: ['"DM Sans"', 'system-ui', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'DM Sans', system-ui, sans-serif; background: #FFFDF8; }
        .font-display { font-family: 'Cormorant Garamond', Georgia, serif; }

        /* Animations */
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideInLeft { from { opacity: 0; transform: translateX(-30px); } to { opacity: 1; transform: translateX(0); } }
        @keyframes float { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
        @keyframes marquee { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
        @keyframes scaleIn { from { opacity: 0; transform: scale(0.9); } to { opacity: 1; transform: scale(1); } }
        @keyframes shimmer { 0% { background-position: -200% 0; } 100% { background-position: 200% 0; } }

        .animate-fadeInUp { animation: fadeInUp 0.8s ease-out forwards; }
        .animate-fadeIn { animation: fadeIn 0.6s ease-out forwards; }
        .animate-slideInLeft { animation: slideInLeft 0.7s ease-out forwards; }
        .animate-float { animation: float 3s ease-in-out infinite; }
        .animate-marquee { animation: marquee 30s linear infinite; }
        .animate-scaleIn { animation: scaleIn 0.5s ease-out forwards; }
        .animate-shimmer { background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent); background-size: 200% 100%; animation: shimmer 2s infinite; }

        .delay-100 { animation-delay: 0.1s; opacity: 0; }
        .delay-200 { animation-delay: 0.2s; opacity: 0; }
        .delay-300 { animation-delay: 0.3s; opacity: 0; }
        .delay-400 { animation-delay: 0.4s; opacity: 0; }

        /* Scroll animations */
        .scroll-reveal { opacity: 0; transform: translateY(40px); transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1); }
        .scroll-reveal.revealed { opacity: 1; transform: translateY(0); }

        /* Product card hover */
        .product-card { transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94); }
        .product-card:hover { transform: translateY(-4px); box-shadow: 0 20px 60px rgba(44, 36, 24, 0.1); }

        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #FFF9ED; }
        ::-webkit-scrollbar-thumb { background: #D4B078; border-radius: 3px; }

        /* Force high-quality image rendering */
        img { image-rendering: auto; -ms-interpolation-mode: bicubic; }
        .product-card img { will-change: auto; backface-visibility: hidden; -webkit-backface-visibility: hidden; }

        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }

        /* Glass effect */
        .glass { background: rgba(255,253,248,0.85); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen" style="overflow-x:hidden; max-width:100vw;">

    @include('partials.header')
    <!-- Spacer for fixed header (non-homepage only) -->
    @unless(request()->routeIs('home'))
    <div style="height: 80px;"></div>
    @endunless

    <!-- Flash Messages -->
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition.opacity
         class="fixed top-20 right-4 z-[100] bg-olive-500 text-white px-5 py-3 rounded-xl shadow-2xl text-sm font-medium flex items-center gap-2 animate-scaleIn">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition.opacity
         class="fixed top-20 right-4 z-[100] bg-red-600 text-white px-5 py-3 rounded-xl shadow-2xl text-sm font-medium animate-scaleIn">
        {{ session('error') }}
    </div>
    @endif

    <main class="flex-1">
        @yield('content')
    </main>

    @include('partials.footer')
    @include('partials.side-cart')

    <!-- Welcome Popup (Dynamic from Coupons with show_as_popup) -->
    @php
        try {
            $popupCoupons = \Illuminate\Support\Facades\Cache::remember('popup_coupons', 1800, function () {
                return \App\Models\Coupon::where('show_as_popup', true)->where('is_active', true)->where(function($q) { $q->whereNull('end_date')->orWhere('end_date', '>', now()); })->get();
            });
        } catch (\Exception $e) {
            $popupCoupons = collect();
        }
    @endphp
    @if($popupCoupons->count())
    <div x-data="{ popup: !sessionStorage.getItem('shivara_popup_closed'), slide: 0 }" x-show="popup" x-cloak
         class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" x-transition.opacity>
        <div class="bg-cream-50 rounded-3xl p-8 max-w-md w-full shadow-2xl relative animate-scaleIn text-center" @click.outside="popup = false; sessionStorage.setItem('shivara_popup_closed', '1')">
            <button @click="popup = false; sessionStorage.setItem('shivara_popup_closed', '1')" class="absolute top-4 right-4 w-8 h-8 bg-espresso-100 rounded-full flex items-center justify-center text-espresso-500 hover:bg-espresso-200 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <div class="w-16 h-16 bg-gold-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
            </div>
            @foreach($popupCoupons as $i => $pc)
            <div x-show="slide === {{ $i }}">
                <h3 class="font-display text-3xl font-bold text-espresso-700 mb-2">{{ $pc->description ?: 'Special Offer!' }}</h3>
                <p class="text-espresso-400 text-sm mb-4">Get <span class="text-gold-500 font-bold text-lg">{{ $pc->type === 'percentage' ? $pc->value.'% OFF' : '₹'.$pc->value.' OFF' }}</span> on your order</p>
                <div class="bg-white border-2 border-dashed border-gold-300 rounded-xl p-4 mb-4">
                    <p class="text-xs text-espresso-400 uppercase tracking-wider mb-1">Use Code</p>
                    <p class="text-2xl font-bold text-gold-600 tracking-widest">{{ $pc->code }}</p>
                </div>
            </div>
            @endforeach
            @if($popupCoupons->count() > 1)
            <div class="flex justify-center gap-2 mb-4">
                @foreach($popupCoupons as $i => $pc)
                <button @click="slide = {{ $i }}" :class="slide === {{ $i }} ? 'w-6 bg-gold-500' : 'w-2 bg-gold-200'" class="h-2 rounded-full transition-all"></button>
                @endforeach
            </div>
            @endif
            <a href="{{ route('products.index') }}" @click="sessionStorage.setItem('shivara_popup_closed', '1')"
               class="inline-block w-full px-6 py-3.5 bg-espresso-700 text-cream-50 font-semibold rounded-xl hover:bg-espresso-600 transition">
                Shop Now →
            </a>
            @if($popupCoupons->first()->min_order_amount)
            <p class="text-xs text-espresso-300 mt-3">*Min order ₹{{ number_format($popupCoupons->first()->min_order_amount) }}</p>
            @endif
        </div>
    </div>
    @endif

    <!-- Mobile Floating Bottom Navigation -->
    <nav class="fixed bottom-5 left-1/2 -translate-x-1/2 z-40 lg:hidden">
        <div class="flex items-center gap-6 px-8 py-3.5 rounded-full" style="background: rgba(255,255,255,0.25); backdrop-filter: blur(20px) saturate(180%); -webkit-backdrop-filter: blur(20px) saturate(180%); border: 1.5px solid rgba(255,255,255,0.4); box-shadow: 0 8px 32px rgba(0,0,0,0.18), inset 0 1px 0 rgba(255,255,255,0.3);">
            <a href="{{ route('home') }}" class="w-9 h-9 flex items-center justify-center rounded-full transition {{ request()->routeIs('home') ? 'bg-black/10' : '' }}">
                <svg class="w-[22px] h-[22px]" style="color:#2C2418;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            </a>
            <a href="{{ auth()->check() ? route('account.dashboard') : route('login') }}" class="w-9 h-9 flex items-center justify-center rounded-full transition {{ request()->routeIs('account.*') ? 'bg-black/10' : '' }}">
                <svg class="w-[22px] h-[22px]" style="color:#2C2418;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </a>
            <a href="{{ route('products.index') }}" class="w-9 h-9 flex items-center justify-center rounded-full transition {{ request()->routeIs('products.*') ? 'bg-black/10' : '' }}">
                <svg class="w-[22px] h-[22px]" style="color:#2C2418;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </a>
            <a href="{{ route('products.index', ['search' => '']) }}" onclick="event.preventDefault(); window.dispatchEvent(new CustomEvent('toggle-search'));" class="w-9 h-9 flex items-center justify-center rounded-full transition">
                <svg class="w-[22px] h-[22px]" style="color:#2C2418;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </a>
        </div>
    </nav>

    <!-- WhatsApp Float -->
    <a href="https://wa.me/{{ config('shivara.whatsapp') }}?text=Hi, I have a question about Shivara products" target="_blank"
       id="whatsappFloat"
       class="fixed bottom-24 right-4 z-30 w-12 h-12 lg:bottom-6 lg:right-6 lg:w-14 lg:h-14 bg-green-500 rounded-full flex items-center justify-center shadow-xl hover:bg-green-600 hover:scale-110 transition-all duration-300 animate-float">
        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
    </a>

    <!-- Scroll Reveal + Swipe Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Scroll reveal
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add('revealed'); });
            }, { threshold: 0.1 });
            document.querySelectorAll('.scroll-reveal').forEach(el => observer.observe(el));

            // Auto-open cart
            @if(session('open_cart'))
            setTimeout(() => window.dispatchEvent(new CustomEvent('open-cart')), 300);
            @endif
        });

        // Simple swipe handler - attach to any element with data-swipe
        function initSwipe(el, onLeft, onRight) {
            let sx = 0, sy = 0, dragging = false;
            el.addEventListener('touchstart', e => { sx = e.touches[0].clientX; sy = e.touches[0].clientY; }, {passive:true});
            el.addEventListener('touchend', e => {
                let dx = e.changedTouches[0].clientX - sx;
                if (Math.abs(dx) > 40 && Math.abs(e.changedTouches[0].clientY - sy) < 80) {
                    dx < 0 ? onLeft() : onRight();
                }
            });
            el.addEventListener('mousedown', e => { sx = e.clientX; dragging = true; e.preventDefault(); });
            el.addEventListener('mousemove', e => { if (dragging) e.preventDefault(); });
            document.addEventListener('mouseup', e => {
                if (!dragging) return;
                dragging = false;
                let dx = e.clientX - sx;
                if (Math.abs(dx) > 40) { dx < 0 ? onLeft() : onRight(); }
            });
        }
    </script>
    <!-- Razorpay Magic Checkout -->
    <script src="https://checkout.razorpay.com/v1/magic-checkout.js"></script>

    @stack('scripts')
</body>
</html>
