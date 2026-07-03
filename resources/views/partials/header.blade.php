<header class="sticky top-0 z-50" x-data="{ mobileMenu: false, searchOpen: false }">
    <!-- Announcement Bar -->
    <div class="bg-secondary text-white overflow-hidden">
        <div class="py-2.5 flex">
            <div class="animate-marquee flex items-center gap-8 whitespace-nowrap text-xs tracking-wide">
                <span>10% OFF UPTO ₹200 — CODE: WOW10</span>
                <span class="text-primary">·</span>
                <span>15% OFF UPTO ₹500 — CODE: EXTRA15</span>
                <span class="text-primary">·</span>
                <span>FREE SHIPPING ON ALL ORDERS</span>
                <span class="text-primary">·</span>
                <span>AYURVEDA, CRAFTED WITH CARE</span>
                <span class="text-primary mx-8">10% OFF UPTO ₹200 — CODE: WOW10</span>
                <span class="text-primary">·</span>
                <span>15% OFF UPTO ₹500 — CODE: EXTRA15</span>
                <span class="text-primary">·</span>
                <span>FREE SHIPPING ON ALL ORDERS</span>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <div class="bg-white/95 backdrop-blur-md border-b border-border">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex items-center justify-between gap-4">
                <!-- Mobile Menu Toggle -->
                <button @click="mobileMenu = !mobileMenu" class="lg:hidden text-secondary">
                    <i data-lucide="menu" class="w-5 h-5" x-show="!mobileMenu"></i>
                    <i data-lucide="x" class="w-5 h-5" x-show="mobileMenu" x-cloak></i>
                </button>

                <!-- Desktop Nav Left -->
                <nav class="hidden lg:flex items-center gap-8">
                    <a href="{{ route('home') }}" class="text-xs font-medium uppercase tracking-widest text-secondary hover:text-primary transition">Home</a>
                    <div class="relative group">
                        <button class="flex items-center gap-1 text-xs font-medium uppercase tracking-widest text-secondary hover:text-primary transition">
                            Shop <i data-lucide="chevron-down" class="w-3 h-3"></i>
                        </button>
                        <div class="absolute top-full left-0 mt-3 w-52 bg-white rounded-lg shadow-lg border border-border opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <div class="p-4 space-y-1">
                                <a href="{{ route('products.index') }}" class="block px-3 py-2.5 rounded-lg hover:bg-accent text-sm text-secondary hover:text-primary transition">All Products</a>
                                <a href="{{ route('products.index', ['category' => 'capsules']) }}" class="block px-3 py-2.5 rounded-lg hover:bg-accent text-sm text-secondary hover:text-primary transition">Capsules & Tablets</a>
                                <a href="{{ route('products.index', ['category' => 'oils']) }}" class="block px-3 py-2.5 rounded-lg hover:bg-accent text-sm text-secondary hover:text-primary transition">Oils & Syrups</a>
                                <a href="{{ route('products.index', ['category' => 'powders']) }}" class="block px-3 py-2.5 rounded-lg hover:bg-accent text-sm text-secondary hover:text-primary transition">Herbal Powders</a>
                                <a href="{{ route('products.index', ['category' => 'skincare']) }}" class="block px-3 py-2.5 rounded-lg hover:bg-accent text-sm text-secondary hover:text-primary transition">Skin & Hair Care</a>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('products.index') }}" class="text-xs font-medium uppercase tracking-widest text-primary hover:text-primary-dark transition">Offers</a>
                </nav>

                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex flex-col items-center">
                    <h1 class="text-2xl md:text-3xl font-serif tracking-wide text-secondary">SHIVARA</h1>
                    <p class="text-[9px] uppercase tracking-[0.3em] text-muted mt-0.5">{{ config('shivara.tagline') }}</p>
                </a>

                <!-- Actions Right -->
                <div class="flex items-center gap-4">
                    <button @click="searchOpen = !searchOpen" class="text-secondary hover:text-primary transition">
                        <i data-lucide="search" class="w-5 h-5"></i>
                    </button>

                    @auth
                        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : '#' }}" class="hidden sm:block text-secondary hover:text-primary transition">
                            <i data-lucide="user" class="w-5 h-5"></i>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:block text-secondary hover:text-primary transition">
                            <i data-lucide="user" class="w-5 h-5"></i>
                        </a>
                    @endauth

                    <a href="{{ route('cart.index') }}" class="relative text-secondary hover:text-primary transition">
                        <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                        @php $cartCount = auth()->check() ? \App\Models\CartItem::where('user_id', auth()->id())->sum('quantity') : collect(session('cart', []))->sum('quantity'); @endphp
                        @if($cartCount > 0)
                        <span class="absolute -top-1.5 -right-1.5 bg-primary text-white text-[9px] font-medium w-4 h-4 rounded-full flex items-center justify-center">{{ $cartCount }}</span>
                        @endif
                    </a>
                </div>
            </div>
        </div>

        <!-- Bottom Nav (Desktop) -->
        <div class="hidden lg:block border-t border-border/50">
            <div class="max-w-7xl mx-auto px-4">
                <ul class="flex items-center justify-center gap-10 py-2.5">
                    <li><a href="{{ route('contact') }}" class="text-xs font-medium uppercase tracking-widest text-muted hover:text-secondary transition">Contact</a></li>
                    <li><a href="{{ route('products.index') }}" class="text-xs font-medium uppercase tracking-widest text-muted hover:text-secondary transition">Track Order</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Search Bar -->
    <div x-show="searchOpen" x-transition class="bg-white border-b border-border p-4">
        <form action="{{ route('products.index') }}" method="GET" class="relative max-w-xl mx-auto">
            <input type="text" name="search" placeholder="Search for products..." class="w-full px-5 py-3 rounded-full border border-border bg-accent/50 text-sm focus:outline-none focus:ring-1 focus:ring-primary/30 placeholder:text-muted">
            <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-primary"><i data-lucide="search" class="w-4 h-4"></i></button>
        </form>
    </div>

    <!-- Mobile Menu -->
    <div x-show="mobileMenu" x-transition class="lg:hidden fixed inset-0 top-[105px] bg-white z-50 overflow-y-auto">
        <nav class="p-8 space-y-1">
            <a href="{{ route('home') }}" class="block text-sm uppercase tracking-widest text-secondary py-4 border-b border-border">Home</a>
            <a href="{{ route('products.index') }}" class="block text-sm uppercase tracking-widest text-secondary py-4 border-b border-border">All Products</a>
            <a href="{{ route('contact') }}" class="block text-sm uppercase tracking-widest text-secondary py-4 border-b border-border">Contact</a>
            @guest
            <a href="{{ route('login') }}" class="block text-sm uppercase tracking-widest text-primary py-4 border-b border-border">Login / Register</a>
            @else
            <form method="POST" action="{{ route('logout') }}">@csrf
                <button class="block text-sm uppercase tracking-widest text-secondary py-4 border-b border-border w-full text-left">Logout</button>
            </form>
            @endguest
        </nav>
    </div>
</header>
