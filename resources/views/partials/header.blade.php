@php
    $marqueeText = \App\Models\Setting::get('marquee_text', 'Free Shipping Pan-India,10% Off — Code: WOW10,100% Natural Ayurvedic,GMP Certified Lab Tested');
    $marqueeItems = array_filter(array_map('trim', explode(',', $marqueeText)));
    $marqueeBg = \App\Models\Setting::get('marquee_bg_color', 'rgb(183, 146, 92)');
    $isHome = request()->routeIs('home');
@endphp

<style>
/* Mobile: sticky header above banner */
.shivara-header-wrap { position: sticky; top: 0; z-index: 50; }
/* Desktop: fixed header overlays banner */
@media (min-width: 1024px) { .shivara-header-wrap { position: fixed; top: 0; left: 0; right: 0; } }
</style>

<div class="shivara-header-wrap overflow-x-hidden" x-data="{ mobileMenu: false, searchOpen: false, scrolled: false }" x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 60 })" x-effect="document.body.style.overflow = mobileMenu ? 'hidden' : ''">
    <!-- Marquee -->
    @if(count($marqueeItems))
    <div class="text-white overflow-hidden" style="background-color: {{ $marqueeBg }};">
        <div class="flex py-1.5 sm:py-2">
            <div class="animate-marquee flex items-center gap-8 whitespace-nowrap text-[9px] sm:text-[10px] tracking-[0.2em] uppercase font-medium">
                @for($m = 0; $m < 2; $m++)
                @foreach($marqueeItems as $item)
                <span>{{ $item }}</span>
                <span class="opacity-60">✦</span>
                @endforeach
                @endfor
            </div>
        </div>
    </div>
    @endif

    <!-- Header -->
    <header class="transition-all duration-300" :style="scrolled ? 'background:rgba(255,253,248,0.85);backdrop-filter:blur(12px);box-shadow:0 1px 3px rgba(0,0,0,0.06)' : 'background:transparent'">
        <div class="max-w-7xl mx-auto px-3 sm:px-6">
            <div class="flex items-center justify-between h-[50px] sm:h-[55px] lg:h-[60px]">
                <!-- Left: Logo (mobile) + Shop/Blog (desktop) -->
                <div class="flex items-center gap-3 lg:gap-5">
                    <a href="{{ route('home') }}" class="lg:hidden"><img src="/public/shivaralogo1.png" alt="Shivara" class="h-7 sm:h-8 w-auto"></a>
                    <a href="{{ route('products.index') }}" class="hidden lg:block text-[12px] font-bold uppercase tracking-[0.12em] transition" style="color:#2C2418;">Shop</a>
                    <a href="{{ route('blog.index') }}" class="hidden lg:block text-[12px] font-bold uppercase tracking-[0.12em] transition" style="color:#2C2418;">Blog</a>
                </div>
                <!-- Center: Logo (desktop only) -->
                <div class="hidden lg:block absolute left-1/2 -translate-x-1/2">
                    <a href="{{ route('home') }}"><img src="/public/shivaralogo1.png" alt="Shivara" class="h-9 md:h-10 w-auto"></a>
                </div>
                <!-- Right: Icons -->
                <div class="flex items-center gap-0.5 sm:gap-1.5" style="color:#2C2418;">
                    <button @click="searchOpen = !searchOpen" class="p-2 rounded-full transition hover:bg-black/5"><svg class="w-[18px] h-[18px] sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg></button>
                    @auth
                    <a href="{{ route('account.dashboard') }}" class="hidden sm:flex p-2 rounded-full transition hover:bg-black/5"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></a>
                    @else
                    <a href="{{ route('login') }}" class="hidden sm:flex p-2 rounded-full transition hover:bg-black/5"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></a>
                    @endauth
                    <button @click="$dispatch('open-cart')" class="relative p-2 rounded-full transition hover:bg-black/5">
                        <svg class="w-[18px] h-[18px] sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        @php $cartCount = auth()->check() ? \App\Models\CartItem::where('user_id', auth()->id())->sum('quantity') : collect(session('cart', []))->sum('quantity'); @endphp
                        @if($cartCount > 0)<span class="absolute -top-0.5 -right-0.5 bg-gold-500 text-white text-[7px] font-bold min-w-[14px] h-[14px] rounded-full flex items-center justify-center">{{ $cartCount }}</span>@endif
                    </button>
                    <button @click="mobileMenu = !mobileMenu" class="p-2 rounded-full transition hover:bg-black/5">
                        <svg class="w-[18px] h-[18px] sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </div>
            </div>
        </div>
        <!-- Search -->
        <div x-show="searchOpen" x-transition x-cloak class="absolute top-full left-0 right-0 bg-cream-50 border-b shadow-xl p-4" style="z-index:60;">
            <form action="{{ route('products.index') }}" method="GET" class="max-w-xl mx-auto relative">
                <input type="text" name="search" placeholder="Search products..." class="w-full pl-11 pr-4 py-3 bg-white border border-gold-200 rounded-xl text-sm focus:outline-none" autofocus>
                <svg class="w-4 h-4 text-gold-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </form>
        </div>
    </header>

    <!-- Nav Drawer -->
    <div x-show="mobileMenu" x-cloak class="fixed inset-0" style="z-index:99999;">
        <div x-show="mobileMenu" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="mobileMenu=false" class="absolute inset-0 bg-black/40"></div>
        <div x-show="mobileMenu" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="absolute right-0 top-0 bottom-0 w-[80%] max-w-[300px] overflow-y-auto shadow-2xl" style="background-color:#FFFDF8;">
            <div class="flex items-center justify-between px-5 h-[50px] border-b" style="border-color:rgba(183,146,92,0.12);"><a href="{{ route('home') }}"><img src="/public/shivaralogo1.png" alt="Shivara" class="h-7 w-auto"></a><button @click="mobileMenu=false" style="color:#2C2418;"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button></div>
            <nav class="p-4">
                <a href="{{ route('home') }}" class="block px-4 py-3 text-[13px] font-bold uppercase tracking-[0.1em] border-b" style="color:#2C2418;border-color:rgba(183,146,92,0.08);">Home</a>
                <a href="{{ route('products.index') }}" class="block px-4 py-3 text-[13px] font-bold uppercase tracking-[0.1em] border-b" style="color:#2C2418;border-color:rgba(183,146,92,0.08);">Shop</a>
                <a href="{{ route('about') }}" class="block px-4 py-3 text-[13px] font-bold uppercase tracking-[0.1em] border-b" style="color:#2C2418;border-color:rgba(183,146,92,0.08);">About</a>
                <a href="{{ route('blog.index') }}" class="block px-4 py-3 text-[13px] font-bold uppercase tracking-[0.1em] border-b" style="color:#2C2418;border-color:rgba(183,146,92,0.08);">Blog</a>
                <a href="{{ route('contact') }}" class="block px-4 py-3 text-[13px] font-bold uppercase tracking-[0.1em] border-b" style="color:#2C2418;border-color:rgba(183,146,92,0.08);">Contact</a>
                <a href="{{ route('track.order') }}" class="block px-4 py-3 text-[13px] font-bold uppercase tracking-[0.1em] border-b" style="color:#2C2418;border-color:rgba(183,146,92,0.08);">Track Order</a>
                @guest<a href="{{ route('login') }}" class="block px-4 py-3 text-[13px] font-bold uppercase tracking-[0.1em]" style="color:#B7925C;">Login / Register</a>
                @else<a href="{{ route('account.dashboard') }}" class="block px-4 py-3 text-[13px] font-bold uppercase tracking-[0.1em] border-b" style="color:#2C2418;border-color:rgba(183,146,92,0.08);">My Account</a>
                <form method="POST" action="{{ route('logout') }}">@csrf<button class="block w-full text-left px-4 py-3 text-[13px] font-bold uppercase tracking-[0.1em]" style="color:#dc2626;">Logout</button></form>@endguest
            </nav>
        </div>
    </div>
</div>
