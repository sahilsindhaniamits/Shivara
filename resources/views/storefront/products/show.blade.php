@extends('layouts.app')
@section('title', $product->meta_title ?? $product->name . ' - Shivara')
@section('meta_description', $product->meta_description ?? $product->short_description)

@section('content')
<div class="bg-cream-50 min-h-screen">
<!-- Breadcrumb -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 pt-6">
    <nav class="flex items-center gap-2 text-xs text-espresso-400 mb-6">
        <a href="{{ route('home') }}" class="hover:text-gold-600 transition">Home</a>
        <span class="text-espresso-300">›</span>
        <a href="{{ route('products.index') }}" class="hover:text-gold-600 transition">Products</a>
        @if($product->category)
        <span class="text-espresso-300">›</span>
        <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="hover:text-gold-600 transition">{{ $product->category->name }}</a>
        @endif
        <span class="text-espresso-300">›</span>
        <span class="text-espresso-700 font-medium">{{ $product->name }}</span>
    </nav>
</div>

<!-- Product Section -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 pb-10" x-data="{ qty: 1, img: 0, tab: 'description', lightbox: false, selectedPack: -1, selectedAttrs: {} }">
<div class="grid lg:grid-cols-2 gap-8 lg:gap-16">

<!-- Image Lightbox Modal -->
<div x-show="lightbox" x-cloak x-transition.opacity class="fixed inset-0 z-[200] bg-black/90 flex items-center justify-center p-4" @click.self="lightbox = false" @keydown.escape.window="lightbox = false">
    <button @click="lightbox = false" class="absolute top-4 right-4 w-10 h-10 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white z-10">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <button @click="img = (img - 1 + {{ $product->images->count() ?: 1 }}) % {{ $product->images->count() ?: 1 }}" class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </button>
    <button @click="img = (img + 1) % {{ $product->images->count() ?: 1 }}" class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    </button>
    <div class="max-w-lg max-h-[70vh] w-full relative">
        @if($product->images->count())
        @foreach($product->images as $i => $lbImg)
        <img x-show="img === {{ $i }}" x-transition src="{{ str_starts_with($lbImg->url, '/storage/') ? '/public' . $lbImg->url : $lbImg->url }}" alt="{{ $product->name }}" class="w-full h-auto max-h-[70vh] object-contain rounded-xl mx-auto">
        @endforeach
        @endif
    </div>
    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2">
        @if($product->images->count() > 1)
        @foreach($product->images as $i => $dot)
        <button @click="img = {{ $i }}" :class="img === {{ $i }} ? 'w-8 bg-white' : 'w-3 bg-white/40'" class="h-3 rounded-full transition-all"></button>
        @endforeach
        @endif
    </div>
    <p class="absolute bottom-2 left-1/2 -translate-x-1/2 text-xs text-white/50" x-text="(img + 1) + ' / {{ $product->images->count() ?: 1 }}'"></p>
</div>


<!-- LEFT: Image Gallery -->
<div class="space-y-4">
    <div @click="lightbox = true" class="aspect-square rounded-3xl overflow-hidden bg-white border border-gray-200 shadow-sm relative group select-none cursor-zoom-in" x-init="initSwipe($el, () => img = (img+1) % {{ $product->images->count() ?: 1 }}, () => img = (img-1+{{ $product->images->count() ?: 1 }}) % {{ $product->images->count() ?: 1 }})">
        @if($product->images->count())
            @foreach($product->images as $i => $image)
            <img x-show="img === {{ $i }}" x-transition src="{{ str_starts_with($image->url, '/storage/') ? '/public' . $image->url : $image->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover absolute inset-0 transition-transform duration-500 group-hover:scale-110">
            @endforeach
        @elseif($product->primaryImage && $product->primaryImage->url)
            <img src="{{ str_starts_with($product->primaryImage->url, '/storage/') ? '/public' . $product->primaryImage->url : $product->primaryImage->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
        @else
            <img src="https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=800&h=800&fit=crop" alt="{{ $product->name }}" class="w-full h-full object-cover">
        @endif
        @if($product->discount_percent > 0)
        <div class="absolute top-4 left-4 z-10">
            <span class="bg-red-500 text-white text-[11px] font-bold px-3 py-1.5 rounded-full shadow-lg">{{ $product->discount_percent }}% OFF</span>
        </div>
        @endif
        <!-- Left/Right Arrows -->
        @if($product->images->count() > 1)
        <button @click="img = (img - 1 + {{ $product->images->count() }}) % {{ $product->images->count() }}" class="absolute left-3 top-1/2 -translate-y-1/2 z-10 w-9 h-9 bg-white/80 backdrop-blur-sm rounded-full flex items-center justify-center shadow-lg opacity-0 group-hover:opacity-100 transition-opacity">
            <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <button @click="img = (img + 1) % {{ $product->images->count() }}" class="absolute right-3 top-1/2 -translate-y-1/2 z-10 w-9 h-9 bg-white/80 backdrop-blur-sm rounded-full flex items-center justify-center shadow-lg opacity-0 group-hover:opacity-100 transition-opacity">
            <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </button>
        <!-- Pagination Dots -->
        <div class="absolute bottom-3 left-1/2 -translate-x-1/2 z-10 flex gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
            @foreach($product->images as $i => $dot)
            <button @click="img = {{ $i }}" :class="img === {{ $i }} ? 'w-5 bg-white' : 'w-2 bg-white/60'" class="h-2 rounded-full transition-all duration-300 shadow-sm"></button>
            @endforeach
        </div>
        @endif
    </div>

    <!-- Clickable Thumbnails -->
    @if($product->images->count() > 1)
    <div class="flex gap-3 overflow-x-auto scrollbar-hide pb-1">
        @foreach($product->images as $i => $imgItem)
        <button @click="img = {{ $i }}" :class="img === {{ $i }} ? 'ring-2 ring-amber-500 ring-offset-2' : 'border-gray-200'" class="w-16 h-16 rounded-xl overflow-hidden shrink-0 border-2 transition-all cursor-pointer">
            <img src="{{ str_starts_with($imgItem->url, '/storage/') ? '/public' . $imgItem->url : $imgItem->url }}" alt="" class="w-full h-full object-cover">
        </button>
        @endforeach
    </div>
    @endif
</div>


<!-- RIGHT: Product Info -->
<div class="space-y-5">
    <!-- Category & Title -->
    <div>
        @if($product->category)
        <span class="text-[11px] font-bold uppercase tracking-[0.2em] text-gold-600">{{ $product->category->name }}</span>
        @endif
        <h1 class="font-display text-3xl md:text-4xl font-bold text-espresso-700 mt-1 leading-tight">{{ $product->name }}</h1>
        @if($product->short_description)
        <p class="text-espresso-400 mt-2 text-sm leading-relaxed">{{ $product->short_description }}</p>
        @endif
    </div>

    <!-- Rating & Stock -->
    <div class="flex items-center gap-4 flex-wrap">
        <div class="flex items-center gap-1">
            @for($s = 1; $s <= 5; $s++)
            <svg class="w-4 h-4 {{ $s <= ($product->average_rating ?: 4) ? 'text-amber-400 fill-amber-400' : 'text-gray-200 fill-gray-200' }}" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            @endfor
            <span class="text-xs text-espresso-400 ml-1">({{ $product->review_count ?: rand(12,48) }} reviews)</span>
        </div>
        <span class="w-1 h-1 bg-espresso-300 rounded-full"></span>
        <span class="text-xs font-bold uppercase tracking-wider {{ $product->stock > 0 ? 'text-green-600' : 'text-red-500' }}">
            {{ $product->stock > 0 ? '✓ In Stock' : '✗ Out of Stock' }}
        </span>
    </div>

    <!-- Price Box -->
    <div class="bg-white rounded-2xl p-5 border border-gold-100 shadow-sm">
        <div class="flex items-baseline gap-3">
            <span class="text-4xl font-bold text-espresso-700">₹{{ number_format($product->selling_price) }}</span>
            @if($product->discount_percent > 0)
            <span class="text-lg text-espresso-300 line-through">₹{{ number_format($product->mrp) }}</span>
            <span class="px-3 py-1 bg-green-50 text-green-700 text-xs font-bold rounded-full border border-green-200">Save ₹{{ number_format($product->mrp - $product->selling_price) }}</span>
            @endif
        </div>
        <div class="flex items-center gap-3 mt-3 text-[11px] text-espresso-400">
            <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg> Inclusive of all taxes</span>
            <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg> Free shipping above ₹{{ config('shivara.free_shipping_threshold') }}</span>
        </div>

    </div>

    <!-- Pack/Variant Selector -->
    @if($product->variants->count())
    <div>
        <p class="text-sm font-bold text-espresso-700 mb-3">Select Pack</p>
        <div class="flex gap-3 overflow-x-auto scrollbar-hide pb-2">
            @foreach($product->variants as $i => $variant)
            <button type="button" @click="selectedPack = {{ $i }}" :class="selectedPack === {{ $i }} ? 'border-2' : 'border'" class="shrink-0 w-40 rounded-xl overflow-hidden text-center transition-all cursor-pointer" :style="selectedPack === {{ $i }} ? 'border-color:#B08840; box-shadow: 0 0 0 2px rgba(176,136,64,0.2)' : 'border-color:#e5e7eb'">
                @if($variant->mrp > $variant->selling_price)
                <div class="relative"><span class="absolute -top-0 left-1/2 -translate-x-1/2 text-[9px] font-bold text-white px-2.5 py-0.5 rounded-b-lg z-10" style="background-color:#16a34a;">Save ₹{{ number_format($variant->mrp - $variant->selling_price) }}</span></div>
                @endif
                <div class="p-4 pt-6" style="background-color:#FFFDF8;">
                    <p class="text-2xl font-bold text-espresso-700">₹{{ number_format($variant->selling_price) }}</p>
                    <p class="text-xs text-espresso-400 line-through mt-0.5">₹{{ number_format($variant->mrp) }}</p>
                </div>
                <div class="p-2.5 text-center text-white" style="background-color:#1a1a1a;">
                    <p class="text-xs font-bold tracking-wide">{{ $variant->name }}</p>
                    @if($variant->weight_display)
                    <p class="text-[10px] text-gray-300 mt-0.5">{{ $variant->weight_display }}</p>
                    @elseif($variant->weight)
                    <p class="text-[10px] text-gray-300 mt-0.5">{{ $variant->weight >= 1000 ? number_format($variant->weight/1000, 1) . ' kg' : intval($variant->weight) . ' g' }}</p>
                    @endif
                </div>
            </button>
            @endforeach
        </div>
    </div>
    @endif


    <!-- Quantity & Add to Cart -->
    @if($product->stock > 0)
    <form method="POST" action="{{ route('cart.add') }}" class="space-y-3" x-data="{ adding: false }" @submit.prevent="
        adding = true;
        let formData = new FormData($el);
        fetch('{{ route('cart.add') }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' },
            body: formData
        }).then(r => r.json()).then(d => {
            adding = false;
            if (d.success) {
                window.dispatchEvent(new CustomEvent('open-cart'));
                window.dispatchEvent(new CustomEvent('cart-updated'));
            } else {
                window.location.reload();
            }
        }).catch(() => { adding = false; $el.submit(); });
    ">
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        <input type="hidden" name="quantity" x-bind:value="qty">
        @if($product->variants->count())
        <input type="hidden" name="variant_id" x-bind:value="selectedPack >= 0 ? [{{ $product->variants->pluck('id')->implode(',') }}][selectedPack] : ''">
        @endif

        <!-- Quantity Selector -->
        <div class="flex items-center gap-4">
            <span class="text-sm font-semibold text-espresso-600">Quantity:</span>
            <div class="flex items-center bg-cream-100 rounded-xl border border-gold-100">
                <button type="button" @click="qty = Math.max(1, qty - 1)" class="w-10 h-10 flex items-center justify-center text-espresso-500 hover:text-espresso-700 transition text-lg font-bold">−</button>
                <span class="w-10 h-10 flex items-center justify-center text-sm font-bold text-espresso-700" x-text="qty"></span>
                <button type="button" @click="qty = Math.min({{ $product->stock }}, qty + 1)" class="w-10 h-10 flex items-center justify-center text-espresso-500 hover:text-espresso-700 transition text-lg font-bold">+</button>
            </div>
        </div>

        <!-- Buttons -->
        <div class="flex flex-col sm:flex-row gap-3">
            <button type="submit" :disabled="adding" class="flex-1 px-8 py-4 text-white font-bold text-sm uppercase tracking-wider rounded-xl hover:opacity-90 transition shadow-xl flex items-center justify-center gap-2 disabled:opacity-60" style="background-color:#2C2418">
                <svg x-show="!adding" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <svg x-show="adding" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                <span x-text="adding ? 'Adding...' : 'Add to Cart'"></span>
            </button>
            <a href="{{ route('checkout.index') }}" onclick="event.preventDefault(); let f=this.closest('form'); f.removeAttribute('x-data'); f.setAttribute('action','{{ route('cart.add') }}'); let csrf=document.createElement('input'); csrf.type='hidden'; csrf.name='_token'; csrf.value=document.querySelector('meta[name=csrf-token]').content; f.appendChild(csrf); f.submit();" class="flex-1 px-8 py-4 text-white font-bold text-sm uppercase tracking-wider rounded-xl hover:opacity-90 transition shadow-xl flex items-center justify-center gap-2" style="background-color:#B08840">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Buy Now
            </a>
        </div>
    </form>
    @else
    <div class="px-8 py-4 bg-gray-100 text-gray-500 font-bold text-sm uppercase tracking-wider rounded-xl text-center">Currently Unavailable</div>
    @endif

    <!-- Trust Badges Row -->
    <div class="grid grid-cols-4 gap-2 pt-3">
        <div class="text-center p-3 bg-white rounded-xl border border-gold-100/50">
            <svg class="w-6 h-6 text-gold-500 mx-auto mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            <p class="text-[9px] font-bold text-espresso-600 uppercase tracking-wider">Free Ship</p>
        </div>
        <div class="text-center p-3 bg-white rounded-xl border border-gold-100/50">
            <svg class="w-6 h-6 text-gold-500 mx-auto mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            <p class="text-[9px] font-bold text-espresso-600 uppercase tracking-wider">{{ $product->return_policy ?? 'Easy Returns' }}</p>
        </div>
        <div class="text-center p-3 bg-white rounded-xl border border-gold-100/50">
            <svg class="w-6 h-6 text-gold-500 mx-auto mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            <p class="text-[9px] font-bold text-espresso-600 uppercase tracking-wider">Genuine</p>
        </div>
        <div class="text-center p-3 bg-white rounded-xl border border-gold-100/50">
            <svg class="w-6 h-6 text-gold-500 mx-auto mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            <p class="text-[9px] font-bold text-espresso-600 uppercase tracking-wider">Secure</p>
        </div>
    </div>

    <!-- Delivery Info -->
    <div class="bg-white rounded-2xl p-4 border border-gold-100/50 space-y-3">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/></svg>
            <p class="text-sm text-espresso-600"><span class="font-semibold">Delivery:</span> {{ config('shivara.standard_days') }}</p>
        </div>
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/></svg>
            <p class="text-sm text-espresso-600"><span class="font-semibold">Cash on Delivery</span> available</p>
        </div>
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/></svg>
            <p class="text-sm text-espresso-600"><span class="font-semibold">100% Natural</span> • Lab Tested • GMP Certified</p>
        </div>
    </div>

    <!-- Active Offers -->
    @php $activeCoupons = \App\Models\Coupon::where('is_active', true)->where('end_date', '>', now())->take(4)->get(); @endphp
    @if($activeCoupons->count())
    <div>
        <p class="text-sm font-bold text-espresso-700 mb-3">Active Offers</p>
        <div class="flex gap-3 overflow-x-auto scrollbar-hide pb-2">
            @foreach($activeCoupons as $coupon)
            <div class="shrink-0 w-48 border-2 border-gold-200 rounded-xl overflow-hidden bg-white">
                <div class="p-2.5 text-center" style="background-color: rgba(176,136,64,0.1);">
                    <p class="text-[10px] font-bold uppercase tracking-wider" style="color:#7A5A30;">CODE: {{ $coupon->code }}</p>
                </div>
                <div class="p-3 text-center">
                    <p class="text-xs text-espresso-600 leading-tight">{{ $coupon->description ?? ($coupon->type == 'percentage' ? $coupon->value.'% off' : '₹'.$coupon->value.' off') }}</p>
                </div>
                <div class="px-3 pb-2.5 text-center border-t border-dashed border-gold-200 pt-2">
                    <button onclick="navigator.clipboard.writeText('{{ $coupon->code }}'); this.textContent='✓ COPIED!'; setTimeout(()=>this.textContent='📋 COPY CODE',1500)" class="text-[10px] font-bold hover:opacity-70 transition cursor-pointer" style="color:#7A5A30;">📋 COPY CODE</button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
</div>




<!-- Product Tabs: Description, Ingredients, How to Use, Reviews -->
<div class="mt-12" x-data="{ activeTab: 'description' }">
    <!-- Tab Headers -->
    <div class="flex flex-wrap gap-2 border-b border-gold-100 mb-6">
        <button @click="activeTab = 'description'" :class="activeTab === 'description' ? 'border-b-2 border-gold-500 text-espresso-700 font-bold' : 'text-espresso-400 hover:text-espresso-600'" class="px-4 py-3 text-sm transition">Description</button>
        <button @click="activeTab = 'ingredients'" :class="activeTab === 'ingredients' ? 'border-b-2 border-gold-500 text-espresso-700 font-bold' : 'text-espresso-400 hover:text-espresso-600'" class="px-4 py-3 text-sm transition">Ingredients</button>
        <button @click="activeTab = 'how_to_use'" :class="activeTab === 'how_to_use' ? 'border-b-2 border-gold-500 text-espresso-700 font-bold' : 'text-espresso-400 hover:text-espresso-600'" class="px-4 py-3 text-sm transition">How to Use</button>
    </div>

    <!-- Tab Content -->
    <div class="bg-white rounded-2xl border border-gold-100/50 p-6 shadow-sm">
        <!-- Description Tab -->
        <div x-show="activeTab === 'description'" x-cloak>
            @if($product->description)
            <div class="prose prose-sm max-w-none text-espresso-600 leading-relaxed">
                {!! nl2br(e($product->description)) !!}
            </div>
            @endif
            @if($product->benefits)
            <div class="mt-6">
                <h4 class="text-sm font-bold text-espresso-700 mb-3">Key Benefits</h4>
                <ul class="space-y-2">
                    @foreach(array_filter(preg_split('/[\n,]+/', $product->benefits)) as $benefit)
                    <li class="flex items-start gap-2 text-sm text-espresso-600">
                        <svg class="w-4 h-4 text-green-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ trim($benefit) }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
            @if(!$product->description && !$product->benefits)
            <p class="text-sm text-espresso-400">No description available yet.</p>
            @endif
        </div>

        <!-- Ingredients Tab -->
        <div x-show="activeTab === 'ingredients'" x-cloak>
            @if($product->ingredients)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                @foreach(explode(',', $product->ingredients) as $ingredient)
                <div class="bg-cream-50 border border-gold-100/50 rounded-xl p-3 text-center">
                    <p class="text-sm font-medium text-espresso-700">{{ trim($ingredient) }}</p>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-sm text-espresso-400">Ingredients information not available yet.</p>
            @endif
        </div>

        <!-- How to Use Tab -->
        <div x-show="activeTab === 'how_to_use'" x-cloak>
            @if($product->how_to_use)
            <div class="prose prose-sm max-w-none text-espresso-600 leading-relaxed">
                {!! nl2br(e($product->how_to_use)) !!}
            </div>
            @else
            <p class="text-sm text-espresso-400">Usage instructions not available yet.</p>
            @endif
        </div>

        
    </div>
</div>


<!-- Product Banners (per-product) -->
@if($product->banners && count($product->banners))
<div class="mt-8" x-data="{ pb: 0 }" x-init="setInterval(() => pb = (pb + 1) % {{ count($product->banners) }}, 5000)">
    <div class="relative rounded-2xl overflow-hidden aspect-[3/1]">
        @foreach($product->banners as $i => $bannerUrl)
        <div x-show="pb === {{ $i }}" x-transition class="absolute inset-0"><img src="{{ str_starts_with($bannerUrl, '/storage/') ? '/public' . $bannerUrl : $bannerUrl }}" alt="Banner" class="w-full h-full object-cover rounded-2xl"></div>
        @endforeach
    </div>
    @if(count($product->banners) > 1)
    <div class="flex justify-center gap-1.5 mt-3">@foreach($product->banners as $i => $b)<button @click="pb={{ $i }}" :class="pb==={{ $i }}?'w-6 bg-gold-500':'w-2 bg-gray-300'" class="h-2 rounded-full transition-all"></button>@endforeach</div>
    @endif
</div>
@endif


<!-- Customer Reviews Section -->
<div class="mt-16 bg-white rounded-3xl border border-gold-100/50 p-6 md:p-8 shadow-sm">
    <h2 class="font-display text-2xl font-bold text-espresso-700 text-center mb-8">Customer Reviews</h2>

    @php
        $approvedReviews = $product->reviews()->where('is_approved', true)->with('user')->latest()->get();
        $totalReviews = $approvedReviews->count();
        $avgRating = $totalReviews > 0 ? $approvedReviews->avg('rating') : 0;
        $ratingCounts = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
        foreach($approvedReviews as $r) { if(isset($ratingCounts[$r->rating])) $ratingCounts[$r->rating]++; }
    @endphp

    <!-- Rating Summary -->
    <div class="flex flex-col md:flex-row items-center gap-8 mb-8 pb-8 border-b border-gray-100">
        <!-- Left: Average -->
        <div class="text-center">
            <p class="text-5xl font-bold text-espresso-700">{{ number_format($avgRating, 1) }}</p>
            <div class="flex items-center gap-0.5 mt-2 justify-center">
                @for($s = 1; $s <= 5; $s++)
                <svg class="w-5 h-5 {{ $s <= round($avgRating) ? 'text-amber-400 fill-amber-400' : 'text-gray-200 fill-gray-200' }}" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                @endfor
            </div>
            <p class="text-sm text-espresso-400 mt-1">Based on {{ $totalReviews }} reviews</p>
        </div>

        <!-- Right: Rating Bars -->
        <div class="flex-1 space-y-2 w-full max-w-sm">
            @foreach($ratingCounts as $star => $count)
            <div class="flex items-center gap-2">
                <div class="flex items-center gap-0.5 w-16 justify-end">
                    @for($s = 1; $s <= $star; $s++)
                    <svg class="w-3 h-3 text-amber-400 fill-amber-400" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    @endfor
                </div>
                <div class="flex-1 h-2.5 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full rounded-full" style="width: {{ $totalReviews > 0 ? ($count/$totalReviews)*100 : 0 }}%; background-color: #B08840;"></div>
                </div>
                <span class="text-xs text-espresso-400 w-8 text-right">{{ $count }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Write Review -->
    @auth
    <div class="mb-8 p-5 rounded-2xl border border-gold-100" style="background-color: #FBF7F0;">
        <h4 class="text-sm font-bold text-espresso-700 mb-3">Write a Review</h4>
        <form method="POST" action="{{ route('products.review', $product->slug) }}" enctype="multipart/form-data" class="space-y-3" x-data="{ rating: 5 }">
            @csrf
            <div class="flex items-center gap-1">
                @for($s = 1; $s <= 5; $s++)
                <button type="button" @click="rating = {{ $s }}" :class="{{ $s }} <= rating ? 'text-amber-400' : 'text-gray-300'" class="transition">
                    <svg class="w-7 h-7 fill-current" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                </button>
                @endfor
            </div>
            <input type="hidden" name="rating" x-bind:value="rating">
            <textarea name="comment" rows="3" placeholder="Share your experience..." class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-gold-200 placeholder:text-gray-400" required></textarea>
            <!-- Image Upload -->
            <div x-data="{ previews: [] }">
                <label class="block text-xs font-semibold text-espresso-600 mb-1.5">Add Photos (optional)</label>
                <div class="flex items-center gap-3 flex-wrap">
                    <label class="w-16 h-16 bg-white border-2 border-dashed border-gray-300 rounded-xl flex items-center justify-center cursor-pointer hover:border-gold-400 hover:bg-gold-50/30 transition">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/></svg>
                        <input type="file" name="review_images[]" multiple accept="image/*" class="hidden" @change="
                            previews = [];
                            for (let f of $event.target.files) {
                                let r = new FileReader();
                                r.onload = e => previews.push(e.target.result);
                                r.readAsDataURL(f);
                            }
                        ">
                    </label>
                    <template x-for="(src, i) in previews" :key="i">
                        <div class="w-16 h-16 rounded-xl overflow-hidden border border-gray-200">
                            <img :src="src" class="w-full h-full object-cover">
                        </div>
                    </template>
                </div>
                <p class="text-[10px] text-gray-400 mt-1">Up to 5 images. Max 2MB each.</p>
            </div>
            <button type="submit" class="px-5 py-2.5 text-white text-xs font-bold uppercase tracking-wider rounded-lg hover:opacity-90 transition" style="background-color:#2C2418">Submit Review</button>
        </form>
    </div>
    @else
    <div class="mb-8 p-4 rounded-xl border border-gold-100 text-center" style="background-color: #FBF7F0;">
        <p class="text-sm text-espresso-500"><a href="{{ route('login') }}" class="font-bold hover:underline" style="color:#B08840">Log in</a> to write a review</p>
    </div>
    @endauth

    <!-- Customer Photos -->
    @php
        $reviewImages = $approvedReviews->pluck('images')->filter()->flatten()->take(12)->values();
    @endphp
    @if($reviewImages->count())
    <div class="mb-8">
        <h4 class="text-sm font-bold text-espresso-700 mb-3 flex items-center gap-2">
            <svg class="w-4 h-4 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            Customer Photos & Videos
        </h4>
        <div class="flex gap-2 overflow-x-auto scrollbar-hide pb-2" x-data="{ lightboxImg: null }">
            @foreach($reviewImages as $rImg)
            <div class="shrink-0 w-20 h-20 rounded-xl overflow-hidden border border-gray-200 cursor-pointer hover:opacity-80 transition" @click="lightboxImg = '{{ str_starts_with($rImg, '/storage/') ? '/public' . $rImg : $rImg }}'">
                <img src="{{ str_starts_with($rImg, '/storage/') ? '/public' . $rImg : $rImg }}" alt="Customer photo" class="w-full h-full object-cover" loading="lazy">
            </div>
            @endforeach
            <!-- Lightbox for customer photos -->
            <div x-show="lightboxImg" x-cloak x-transition.opacity @click.self="lightboxImg = null" @keydown.escape.window="lightboxImg = null" class="fixed inset-0 z-[200] bg-black/90 flex items-center justify-center p-4">
                <button @click="lightboxImg = null" class="absolute top-4 right-4 w-10 h-10 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <img :src="lightboxImg" class="max-w-full max-h-[80vh] rounded-xl object-contain">
            </div>
        </div>
    </div>
    @endif

    <!-- Reviews List -->
    @if($approvedReviews->count())
    <div class="space-y-5">
        @foreach($approvedReviews as $review)
        <div class="border-b border-gray-100 pb-5 last:border-0 last:pb-0">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-0.5">
                    @for($s = 1; $s <= 5; $s++)
                    <svg class="w-4 h-4 {{ $s <= $review->rating ? 'text-amber-400 fill-amber-400' : 'text-gray-200 fill-gray-200' }}" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    @endfor
                </div>
                <span class="text-xs text-espresso-400">{{ $review->created_at->format('d/m/Y') }}</span>
            </div>
            <div class="flex items-center gap-2 mb-2">
                <div class="w-7 h-7 rounded-full flex items-center justify-center text-[10px] font-bold text-white" style="background-color:#2C2418">{{ substr($review->user->name ?? 'C', 0, 1) }}</div>
                <span class="text-sm font-semibold text-espresso-700">{{ $review->user->name ?? 'Customer' }}</span>
                @if($review->is_verified)<span class="text-[9px] font-bold text-green-700 bg-green-50 border border-green-200 px-1.5 py-0.5 rounded">Verified</span>@endif
            </div>
            <p class="text-sm text-espresso-600 leading-relaxed">{{ $review->comment }}</p>
            @if($review->images && count($review->images))
            <div class="flex gap-2 mt-3 flex-wrap">
                @foreach($review->images as $revImg)
                <div class="w-16 h-16 rounded-lg overflow-hidden border border-gray-200 cursor-pointer hover:opacity-80 transition">
                    <img src="{{ str_starts_with($revImg, '/storage/') ? '/public' . $revImg : $revImg }}" alt="Review photo" class="w-full h-full object-cover" loading="lazy">
                </div>
                @endforeach
            </div>
            @endif
        </div>
        @endforeach
    </div>
    @else
    <p class="text-sm text-espresso-400 text-center py-8">No reviews yet. Be the first to share your experience!</p>
    @endif
</div>

<!-- Why Choose Shivara -->
<div class="mt-12 bg-white rounded-3xl border border-gold-100/50 p-8 shadow-sm">
    <h3 class="font-display text-2xl font-bold text-espresso-700 text-center mb-8">Why Choose Shivara?</h3>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="text-center">
            <div class="w-14 h-14 mx-auto mb-3 bg-gold-50 rounded-2xl flex items-center justify-center"><svg class="w-7 h-7 text-gold-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg></div>
            <h4 class="text-sm font-bold text-espresso-700">100% Natural</h4>
            <p class="text-xs text-espresso-400 mt-1">No chemicals or preservatives</p>
        </div>
        <div class="text-center">
            <div class="w-14 h-14 mx-auto mb-3 bg-olive-50 rounded-2xl flex items-center justify-center"><svg class="w-7 h-7 text-olive-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg></div>
            <h4 class="text-sm font-bold text-espresso-700">GMP Certified</h4>
            <p class="text-xs text-espresso-400 mt-1">Made in audited facilities</p>
        </div>
        <div class="text-center">
            <div class="w-14 h-14 mx-auto mb-3 bg-gold-50 rounded-2xl flex items-center justify-center"><svg class="w-7 h-7 text-gold-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg></div>
            <h4 class="text-sm font-bold text-espresso-700">Lab Tested</h4>
            <p class="text-xs text-espresso-400 mt-1">Purity verified by experts</p>
        </div>
        <div class="text-center">
            <div class="w-14 h-14 mx-auto mb-3 bg-olive-50 rounded-2xl flex items-center justify-center"><svg class="w-7 h-7 text-olive-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg></div>
            <h4 class="text-sm font-bold text-espresso-700">5000+ Happy Customers</h4>
            <p class="text-xs text-espresso-400 mt-1">Trusted across India</p>
        </div>
    </div>
</div>

<!-- Related Products -->
@if($relatedProducts->count())
<div class="mt-12">
    <h2 class="font-display text-2xl font-bold text-espresso-700 mb-6">You may also like</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-5">
        @foreach($relatedProducts as $product)
            @include('partials.product-card', ['product' => $product])
        @endforeach
    </div>
</div>
@endif

</div>
</div>

@endsection
