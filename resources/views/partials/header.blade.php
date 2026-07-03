<!-- Announcement Bar -->
<div class="bg-dark text-white overflow-hidden">
    <div class="flex py-2">
        <div class="animate-marquee flex items-center gap-10 whitespace-nowrap text-[11px] tracking-widest uppercase font-medium">
            <span>Free Shipping on All Orders</span>
            <span class="text-gold">✦</span>
            <span>Use Code WOW10 for 10% Off</span>
            <span class="text-gold">✦</span>
            <span>100% Natural Ayurvedic Products</span>
            <span class="text-gold">✦</span>
            <span>GMP Certified Facility</span>
            <span class="text-gold">✦</span>
            <span>Free Shipping on All Orders</span>
            <span class="text-gold">✦</span>
            <span>Use Code WOW10 for 10% Off</span>
            <span class="text-gold">✦</span>
            <span>100% Natural Ayurvedic Products</span>
            <span class="text-gold">✦</span>
            <span>GMP Certified Facility</span>
        </div>
    </div>
</div>

<!-- Main Header -->
<header class="sticky top-0 z-50 bg-white/98 backdrop-blur-lg border-b border-gray-100 shadow-sm" x-data="{ mobileMenu: false, searchOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-between h-16 md:h-[72px]">

            <!-- Mobile Menu Button -->
            <button @click="mobileMenu = !mobileMenu" class="lg:hidden p-2 -ml-2 text-gray-700 hover:text-dark transition">
                <svg x-show="!mobileMenu" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg x-show="mobileMenu" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <!-- Desktop Navigation Left -->
            <nav class="hidden lg:flex items-center gap-8">
                <a href="{{ route('home') }}" class="text-[13px] font-semibold uppercase tracking-wider text-gray-700 hover:text-dark transition">Home</a>
                <div class="relative group">
                    <button class="text-[13px] font-semibold uppercase tracking-wider text-gray-700 hover:text-dark transition flex items-center gap-1">
                        Shop
                        <svg class="w-3.5 h-3.5 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div class="absolute top-full left-0 mt-2 w-56 bg-white rounded-xl shadow-2xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 p-2">
                        <a href="{{ route('products.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-brand-50 hover:text-brand-700 rounded-lg transition">All Products</a>
                        <a href="{{ route('products.index', ['category' => 'capsules']) }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-brand-50 hover:text-brand-700 rounded-lg transition">Capsules & Tablets</a>
                        <a href="{{ route('products.index', ['category' => 'powders']) }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-brand-50 hover:text-brand-700 rounded-lg transition">Herbal Powders</a>
                        <a href="{{ route('products.index', ['category' => 'oils']) }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-brand-50 hover:text-brand-700 rounded-lg transition">Oils & Syrups</a>
                        <a href="{{ route('products.index', ['category' => 'skincare']) }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-brand-50 hover:text-brand-700 rounded-lg transition">Skin & Hair Care</a>
                        <a href="{{ route('products.index', ['category' => 'immunity']) }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-brand-50 hover:text-brand-700 rounded-lg transition">Immunity Boosters</a>
                    </div>
                </div>
                <a href="{{ route('contact') }}" class="text-[13px] font-semibold uppercase tracking-wider text-gray-700 hover:text-dark transition">Contact</a>
            </nav>

            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex flex-col items-center group">
                <span class="text-2xl md:text-[28px] font-display font-bold tracking-wide text-dark group-hover:text-brand-700 transition">SHIVARA</span>
                <span class="text-[9px] uppercase tracking-[0.35em] text-gray-400 -mt-0.5 hidden sm:block">{{ config('shivara.tagline') }}</span>
            </a>

            <!-- Actions Right -->
            <div class="flex items-center gap-1 sm:gap-3">
                <!-- Search Toggle -->
                <button @click="searchOpen = !searchOpen" class="p-2 text-gray-600 hover:text-dark transition rounded-full hover:bg-gray-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>

                <!-- Account -->
                @auth
                <a href="{{ route('account.dashboard') }}" class="hidden sm:flex p-2 text-gray-600 hover:text-dark transition rounded-full hover:bg-gray-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </a>
                @else
                <a href="{{ route('login') }}" class="hidden sm:flex p-2 text-gray-600 hover:text-dark transition rounded-full hover:bg-gray-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </a>
                @endauth

                <!-- Wishlist -->
                @auth
                <a href="{{ route('account.wishlist') }}" class="hidden sm:flex p-2 text-gray-600 hover:text-dark transition rounded-full hover:bg-gray-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </a>
                @endauth

                <!-- Cart -->
                <a href="{{ route('cart.index') }}" class="relative p-2 text-gray-600 hover:text-dark transition rounded-full hover:bg-gray-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    @php $cartCount = auth()->check() ? \App\Models\CartItem::where('user_id', auth()->id())->sum('quantity') : collect(session('cart', []))->sum('quantity'); @endphp
                    @if($cartCount > 0)
                    <span class="absolute -top-0.5 -right-0.5 bg-brand-600 text-white text-[10px] font-bold min-w-[18px] h-[18px] rounded-full flex items-center justify-center px-1">{{ $cartCount }}</span>
                    @endif
                </a>
            </div>
        </div>
    </div>

    <!-- Search Overlay -->
    <div x-show="searchOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-cloak
         class="absolute top-full left-0 right-0 bg-white border-b border-gray-100 shadow-lg p-4 md:p-6">
        <form action="{{ route('products.index') }}" method="GET" class="max-w-2xl mx-auto relative">
            <input type="text" name="search" placeholder="Search for products, ingredients, concerns..."
                   class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:border-brand-400 placeholder:text-gray-400" autofocus>
            <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <button type="button" @click="searchOpen = false" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </form>
    </div>

    <!-- Mobile Menu -->
    <div x-show="mobileMenu" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-cloak
         class="lg:hidden fixed inset-0 top-[calc(64px+32px)] bg-white z-50 overflow-y-auto">
        <nav class="p-6 space-y-1">
            <a href="{{ route('home') }}" class="block px-4 py-3.5 text-base font-medium text-gray-800 border-b border-gray-100">Home</a>
            <a href="{{ route('products.index') }}" class="block px-4 py-3.5 text-base font-medium text-gray-800 border-b border-gray-100">All Products</a>
            <a href="{{ route('products.index', ['category' => 'capsules']) }}" class="block px-4 py-3.5 text-sm text-gray-600 border-b border-gray-50 pl-8">Capsules & Tablets</a>
            <a href="{{ route('products.index', ['category' => 'powders']) }}" class="block px-4 py-3.5 text-sm text-gray-600 border-b border-gray-50 pl-8">Herbal Powders</a>
            <a href="{{ route('products.index', ['category' => 'oils']) }}" class="block px-4 py-3.5 text-sm text-gray-600 border-b border-gray-50 pl-8">Oils & Syrups</a>
            <a href="{{ route('products.index', ['category' => 'skincare']) }}" class="block px-4 py-3.5 text-sm text-gray-600 border-b border-gray-50 pl-8">Skin & Hair Care</a>
            <a href="{{ route('contact') }}" class="block px-4 py-3.5 text-base font-medium text-gray-800 border-b border-gray-100">Contact Us</a>
            @auth
            <a href="{{ route('account.dashboard') }}" class="block px-4 py-3.5 text-base font-medium text-gray-800 border-b border-gray-100">My Account</a>
            <a href="{{ route('account.orders') }}" class="block px-4 py-3.5 text-sm text-gray-600 border-b border-gray-50 pl-8">My Orders</a>
            <a href="{{ route('account.wishlist') }}" class="block px-4 py-3.5 text-sm text-gray-600 border-b border-gray-50 pl-8">Wishlist</a>
            <form method="POST" action="{{ route('logout') }}">@csrf
                <button class="block w-full text-left px-4 py-3.5 text-base font-medium text-red-600 border-b border-gray-100">Logout</button>
            </form>
            @else
            <a href="{{ route('login') }}" class="block px-4 py-3.5 text-base font-medium text-brand-700 border-b border-gray-100">Login / Register</a>
            @endauth
        </nav>
    </div>
</header>
