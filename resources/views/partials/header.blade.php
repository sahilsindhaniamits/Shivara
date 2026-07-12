@php
    $marqueeText = \App\Models\Setting::get('marquee_text', 'Free Shipping Pan-India,10% Off — Code: WOW10,100% Natural Ayurvedic,GMP Certified Lab Tested');
    $marqueeItems = array_filter(array_map('trim', explode(',', $marqueeText)));
    $marqueeBg = \App\Models\Setting::get('marquee_bg_color', 'rgb(183, 146, 92)');
@endphp

<!-- Sticky Marquee + Header (no wrapper div - sticky directly on body's child flow) -->
<div class="sticky top-0 z-50" x-data="{ mobileMenu: false, searchOpen: false }" x-effect="document.body.style.overflow = mobileMenu ? 'hidden' : ''">
    @if(count($marqueeItems))
    <div class="text-white overflow-hidden" style="background-color: {{ $marqueeBg }};">
        <div class="flex py-2">
            <div class="animate-marquee flex items-center gap-8 whitespace-nowrap text-[10px] tracking-[0.2em] uppercase font-medium">
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

    <header class="border-b shadow-sm" style="background: rgba(255,253,248,0.97); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-3 items-center h-[70px]">
                <div class="flex items-center">
                    <button @click="mobileMenu = !mobileMenu" class="lg:hidden p-2 -ml-2 transition" style="color:#2C2418;">
                        <svg x-show="!mobileMenu" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        <svg x-show="mobileMenu" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                    <nav class="hidden lg:flex items-center gap-5">
                        <a href="{{ route('home') }}" class="text-[12px] font-bold uppercase tracking-[0.15em] text-espresso-600 hover:text-gold-600 transition">Home</a>
                        <div class="relative group">
                            <a href="{{ route('products.index') }}" class="text-[12px] font-bold uppercase tracking-[0.15em] text-espresso-600 hover:text-gold-600 transition flex items-center gap-1">Shop <svg class="w-3 h-3 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg></a>
                            <div class="absolute top-full left-0 mt-3 w-56 bg-cream-50 rounded-2xl shadow-2xl border border-gold-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 p-3 z-[60]">
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
                <div class="flex justify-center">
                    <a href="{{ route('home') }}"><img src="/public/shivaralogo.png" alt="Shivara" class="h-10 md:h-12 w-auto" onerror="this.style.display='none';this.nextElementSibling.style.display='block'"><span style="display:none" class="text-[26px] font-display font-bold tracking-wide text-espresso-700">SHIVARA</span></a>
                </div>
                <div class="flex items-center justify-end gap-2 sm:gap-3">
                    <button @click="searchOpen = !searchOpen" class="p-2.5 text-espresso-500 hover:text-gold-600 hover:bg-gold-50 rounded-full transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg></button>
                    @auth
                    <a href="{{ route('account.dashboard') }}" class="hidden sm:flex p-2.5 text-espresso-500 hover:text-gold-600 hover:bg-gold-50 rounded-full transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></a>
                    @else
                    <a href="{{ route('login') }}" class="hidden sm:flex p-2.5 text-espresso-500 hover:text-gold-600 hover:bg-gold-50 rounded-full transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></a>
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
        <div x-show="searchOpen" x-transition x-cloak class="absolute top-full left-0 right-0 bg-cream-50 border-b border-gold-100 shadow-xl p-5" style="z-index: 60;">
            <form action="{{ route('products.index') }}" method="GET" class="max-w-2xl mx-auto relative">
                <input type="text" name="search" placeholder="Search products, ingredients, concerns..." class="w-full pl-12 pr-4 py-4 bg-white border border-gold-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-gold-300 placeholder:text-espresso-300" autofocus>
                <svg class="w-5 h-5 text-gold-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </form>
        </div>
    </header>

    <!-- Mobile Nav Drawer (inside x-data scope, slides from left) -->
    <div x-show="mobileMenu" x-cloak class="lg:hidden fixed inset-0" style="z-index: 99999;">
        <div x-show="mobileMenu" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="mobileMenu = false" class="absolute inset-0 bg-black/40"></div>
        <div x-show="mobileMenu" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="relative w-[85%] max-w-[320px] h-full overflow-y-auto shadow-2xl" style="background-color: #FFFDF8;">
            <div class="flex items-center justify-between px-5 h-[60px] border-b" style="border-color: rgba(183,146,92,0.2);">
                <a href="{{ route('home') }}"><img src="/public/shivaralogo.png" alt="Shivara" class="h-9 w-auto" onerror="this.style.display='none';"></a>
                <button @click="mobileMenu = false" class="p-2" style="color:#2C2418;"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <nav class="p-5 space-y-0">
                <a href="{{ route('home') }}" class="block px-4 py-3.5 text-[13px] font-bold uppercase tracking-[0.15em] border-b" style="color:#2C2418; border-color: rgba(183,146,92,0.15);">Home</a>
                <div x-data="{ shopOpen: false }">
                    <button @click="shopOpen = !shopOpen" class="w-full flex items-center justify-between px-4 py-3.5 text-[13px] font-bold uppercase tracking-[0.15em] border-b" style="color:#2C2418; border-color: rgba(183,146,92,0.15);"><span>Shop</span><svg :class="shopOpen ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg></button>
                    <div x-show="shopOpen" x-cloak class="pl-6 pb-2">
                        <a href="{{ route('products.index') }}" class="block px-4 py-2 text-sm font-medium" style="color:#2C2418;">All Products</a>
                        <a href="{{ route('products.index', ['category' => 'capsules']) }}" class="block px-4 py-2 text-sm" style="color:#6b5442;">Capsules & Tablets</a>
                        <a href="{{ route('products.index', ['category' => 'powders']) }}" class="block px-4 py-2 text-sm" style="color:#6b5442;">Herbal Powders</a>
                        <a href="{{ route('products.index', ['category' => 'oils']) }}" class="block px-4 py-2 text-sm" style="color:#6b5442;">Oils & Syrups</a>
                        <a href="{{ route('products.index', ['category' => 'skincare']) }}" class="block px-4 py-2 text-sm" style="color:#6b5442;">Skin & Hair Care</a>
                    </div>
                </div>
                <a href="{{ route('contact') }}" class="block px-4 py-3.5 text-[13px] font-bold uppercase tracking-[0.15em] border-b" style="color:#2C2418; border-color: rgba(183,146,92,0.15);">Contact</a>
                <a href="{{ route('blog.index') }}" class="block px-4 py-3.5 text-[13px] font-bold uppercase tracking-[0.15em] border-b" style="color:#2C2418; border-color: rgba(183,146,92,0.15);">Blog</a>
                <a href="{{ route('track.order') }}" class="block px-4 py-3.5 text-[13px] font-bold uppercase tracking-[0.15em] border-b" style="color:#2C2418; border-color: rgba(183,146,92,0.15);">Track Order</a>
                @guest
                <a href="{{ route('login') }}" class="block px-4 py-3.5 text-[13px] font-bold uppercase tracking-[0.15em] border-b" style="color:#B7925C; border-color: rgba(183,146,92,0.15);">Login / Register</a>
                @else
                <a href="{{ route('account.dashboard') }}" class="block px-4 py-3.5 text-[13px] font-bold uppercase tracking-[0.15em] border-b" style="color:#2C2418; border-color: rgba(183,146,92,0.15);">My Account</a>
                <form method="POST" action="{{ route('logout') }}">@csrf<button class="block w-full text-left px-4 py-3.5 text-[13px] font-bold uppercase tracking-[0.15em]" style="color:#dc2626;">Logout</button></form>
                @endguest
            </nav>
        </div>
    </div>
</div>
