<!-- Announcement Marquee -->
@php
    $marqueeText = \App\Models\Setting::get('marquee_text', 'Free Shipping Pan-India,10% Off — Code: WOW10,100% Natural Ayurvedic,GMP Certified Lab Tested');
    $marqueeItems = array_filter(array_map('trim', explode(',', $marqueeText)));
    $marqueeBg = \App\Models\Setting::get('marquee_bg_color', 'rgb(183, 146, 92)');
@endphp
@if(count($marqueeItems))
<div class="text-white overflow-hidden" style="background-color: {{ $marqueeBg }};">
    <div class="flex py-2.5">
        <div class="animate-marquee flex items-center gap-8 whitespace-nowrap text-[11px] tracking-[0.2em] uppercase font-medium">
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

<!-- Main Header -->
<header class="sticky top-0 z-50 glass border-b border-gold-100/50 shadow-sm" x-data="{ mobileMenu: false, searchOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-3 items-center h-[70px]">

            <!-- Left: Mobile Menu + Desktop Nav -->
            <div class="flex items-center">
                <!-- Mobile Menu Button -->
                <button @click="mobileMenu = !mobileMenu" class="lg:hidden p-2 -ml-2 text-espresso-600 hover:text-espresso-700 transition">
                    <svg x-show="!mobileMenu" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="mobileMenu" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>

                <!-- Desktop Nav -->
                <nav class="hidden lg:flex items-center gap-5">
                    <a href="{{ route('home') }}" class="text-[12px] font-bold uppercase tracking-[0.15em] text-espresso-600 hover:text-gold-600 transition">Home</a>
                    <div class="relative group">
                        <a href="{{ route('products.index') }}" class="text-[12px] font-bold uppercase tracking-[0.15em] text-espresso-600 hover:text-gold-600 transition flex items-center gap-1">
                            Shop <svg class="w-3 h-3 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </a>
                        <div class="absolute top-full left-0 mt-3 w-56 bg-cream-50 rounded-2xl shadow-2xl border border-gold-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 p-3">
                            <a href="{{ route('products.index') }}" class="block px-4 py-2.5 text-sm text-espresso-600 hover:bg-gold-50 hover:text-gold-700 rounded-xl transition font-medium">All Products</a>
                            <a href="{{ route('products.index', ['category' => 'capsules']) }}" class="block px-4 py-2.5 text-sm text-espresso-500 hover:bg-gold-50 hover:text-gold-700 rounded-xl transition">Capsules & Tablets</a>
                            <a href="{{ route('products.index', ['category' => 'powders']) }}" class="block px-4 py-2.5 text-sm text-espresso-500 hover:bg-gold-50 hover:text-gold-700 rounded-xl transition">Herbal Powders</a>
                            <a href="{{ route('products.index', ['category' => 'oils']) }}" class="block px-4 py-2.5 text-sm text-espresso-500 hover:bg-gold-50 hover:text-gold-700 rounded-xl transition">Oils & Syrups</a>
                            <a href="{{ route('products.index', ['category' => 'skincare']) }}" class="block px-4 py-2.5 text-sm text-espresso-500 hover:bg-gold-50 hover:text-gold-700 rounded-xl transition">Skin & Hair Care</a>
                        </div>
                    </div>
                    <a href="{{ route('contact') }}" class="text-[12px] font-bold uppercase tracking-[0.15em] text-espresso-600 hover:text-gold-600 transition">Contact</a>
                    <a href="{{ route('blog.index') }}" class="text-[12px] font-bold uppercase tracking-[0.15em] text-espresso-600 hover:text-gold-600 transition">Blog</a>
                    <a href="{{ route('track.order') }}" class="text-[12px] font-bold uppercase tracking-[0.15em] text-espresso-600 hover:text-gold-600 transition">Track Order</a>
                </nav>
            </div>

            <!-- Center: Logo -->
            <div class="flex justify-center">
                <a href="{{ route('home') }}" class="flex flex-col items-center group">
                    <img src="/public/shivaralogo.png" alt="Shivara" class="h-10 md:h-12 w-auto" onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
                    <span style="display:none" class="text-[26px] font-display font-bold tracking-wide text-espresso-700">SHIVARA</span>
                </a>
            </div>

            <!-- Right: Actions -->
            <div class="flex items-center justify-end gap-2 sm:gap-3">
                <button @click="searchOpen = !searchOpen" class="p-2.5 text-espresso-500 hover:text-gold-600 hover:bg-gold-50 rounded-full transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>
                @auth
                <a href="{{ route('account.dashboard') }}" class="hidden sm:flex p-2.5 text-espresso-500 hover:text-gold-600 hover:bg-gold-50 rounded-full transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </a>
                @else
                <a href="{{ route('login') }}" class="hidden sm:flex p-2.5 text-espresso-500 hover:text-gold-600 hover:bg-gold-50 rounded-full transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </a>
                @endauth
                <button @click="$dispatch('open-cart')" class="relative p-2.5 text-espresso-500 hover:text-gold-600 hover:bg-gold-50 rounded-full transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    @php $cartCount = auth()->check() ? \App\Models\CartItem::where('user_id', auth()->id())->sum('quantity') : collect(session('cart', []))->sum('quantity'); @endphp
                    @if($cartCount > 0)
                    <span class="absolute -top-0.5 -right-0.5 bg-gold-500 text-white text-[9px] font-bold min-w-[18px] h-[18px] rounded-full flex items-center justify-center px-1">{{ $cartCount }}</span>
                    @endif
                </button>
            </div>
        </div>
    </div>

    <!-- Search Overlay -->
    <div x-show="searchOpen" x-transition x-cloak class="absolute top-full left-0 right-0 bg-cream-50 border-b border-gold-100 shadow-xl p-5">
        <form action="{{ route('products.index') }}" method="GET" class="max-w-2xl mx-auto relative">
            <input type="text" name="search" placeholder="Search products, ingredients, concerns..." class="w-full pl-12 pr-4 py-4 bg-white border border-gold-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-gold-300 placeholder:text-espresso-300" autofocus>
            <svg class="w-5 h-5 text-gold-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </form>
    </div>

    <!-- Mobile Nav -->
    <div x-show="mobileMenu" x-transition x-cloak class="lg:hidden fixed inset-0 top-[102px] bg-cream-50 z-50 overflow-y-auto">
        <nav class="p-6 space-y-1">
            <a href="{{ route('home') }}" class="block px-4 py-4 text-base font-medium text-espresso-700 border-b border-gold-100">Home</a>
            <a href="{{ route('products.index') }}" class="block px-4 py-4 text-base font-medium text-espresso-700 border-b border-gold-100">All Products</a>
            <a href="{{ route('contact') }}" class="block px-4 py-4 text-base font-medium text-espresso-700 border-b border-gold-100">Contact</a>
            <a href="{{ route('track.order') }}" class="block px-4 py-4 text-base font-medium text-espresso-700 border-b border-gold-100">Track Order</a>
            @guest
            <a href="{{ route('login') }}" class="block px-4 py-4 text-base font-bold text-gold-600 border-b border-gold-100">Login / Register</a>
            @else
            <a href="{{ route('account.dashboard') }}" class="block px-4 py-4 text-base font-medium text-espresso-700 border-b border-gold-100">My Account</a>
            <form method="POST" action="{{ route('logout') }}">@csrf<button class="block w-full text-left px-4 py-4 text-base font-medium text-red-600">Logout</button></form>
            @endguest
        </nav>
    </div>
</header>
