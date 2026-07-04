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
<div class="max-w-7xl mx-auto px-4 sm:px-6 pb-10" x-data="{ qty: 1, img: 0, tab: 'description' }">
<div class="grid lg:grid-cols-2 gap-8 lg:gap-16">


<!-- LEFT: Image Gallery -->
<div class="space-y-4">
    <div class="aspect-square rounded-3xl overflow-hidden bg-white border border-gold-100 shadow-sm relative group">
        @if($product->primaryImage && $product->primaryImage->url)
            <img src="{{ $product->primaryImage->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
        @else
            <img src="https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=800&h=800&fit=crop" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
        @endif
        @if($product->discount_percent > 0)
        <div class="absolute top-4 left-4 flex flex-col gap-2">
            <span class="bg-red-500 text-white text-[11px] font-bold px-3 py-1.5 rounded-full shadow-lg">{{ $product->discount_percent }}% OFF</span>
        </div>
        @endif
        @if($product->is_featured)
        <span class="absolute top-4 right-4 bg-gold-500 text-white text-[10px] font-bold px-3 py-1.5 rounded-full shadow-lg uppercase tracking-wider">Bestseller</span>
        @endif
    </div>

    <!-- Product Image Thumbnails -->
    @if($product->images->count() > 1)
    <div class="flex gap-2 overflow-x-auto scrollbar-hide pb-1">
        @foreach($product->images as $i => $img)
        <button class="w-16 h-16 rounded-lg overflow-hidden shrink-0 border-2 border-gold-100 hover:border-gold-400 transition">
            <img src="{{ $img->url }}" alt="" class="w-full h-full object-cover">
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
        <p class="text-[11px] text-espresso-400 mt-2">Inclusive of all taxes • Free shipping on orders above ₹{{ config('shivara.free_shipping_threshold') }}</p>

        <!-- Offer Tags (dynamic from active coupons) -->
        @php $coupons = \App\Models\Coupon::where('is_active', true)->where('end_date', '>', now())->take(2)->get(); @endphp
        @if($coupons->count())
        <div class="mt-3 flex flex-wrap gap-2">
            @foreach($coupons as $c)
            <span class="text-[10px] font-bold bg-gold-50 text-gold-700 px-2.5 py-1 rounded-full border border-gold-200">🎁 {{ $c->description ?? $c->code }}</span>
            @endforeach
            <span class="text-[10px] font-bold bg-blue-50 text-blue-700 px-2.5 py-1 rounded-full border border-blue-200">🚚 Free Delivery</span>
        </div>
        @else
        <div class="mt-3 flex flex-wrap gap-2">
            <span class="text-[10px] font-bold bg-blue-50 text-blue-700 px-2.5 py-1 rounded-full border border-blue-200">🚚 Free Delivery</span>
            <span class="text-[10px] font-bold bg-purple-50 text-purple-700 px-2.5 py-1 rounded-full border border-purple-200">↩ 7-Day Returns</span>
        </div>
        @endif
    </div>


    <!-- Quantity & Add to Cart -->
    @if($product->stock > 0)
    <form method="POST" action="{{ route('cart.add') }}" class="space-y-3">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        <input type="hidden" name="quantity" x-bind:value="qty">

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
            <button type="submit" class="flex-1 px-8 py-4 bg-espresso-700 text-cream-50 font-bold text-sm uppercase tracking-wider rounded-xl hover:bg-espresso-600 transition shadow-xl shadow-espresso-700/20 flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                Add to Cart
            </button>
            <a href="{{ route('checkout.index') }}" onclick="event.preventDefault(); this.closest('form').action='{{ route('cart.add') }}'; this.closest('form').submit();" class="flex-1 px-8 py-4 bg-gold-500 text-white font-bold text-sm uppercase tracking-wider rounded-xl hover:bg-gold-600 transition shadow-xl shadow-gold-500/20 flex items-center justify-center gap-2">
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
            <svg class="w-6 h-6 text-gold-500 mx-auto mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            <p class="text-[9px] font-bold text-espresso-600 uppercase tracking-wider">Genuine</p>
        </div>
        <div class="text-center p-3 bg-white rounded-xl border border-gold-100/50">
            <svg class="w-6 h-6 text-gold-500 mx-auto mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            <p class="text-[9px] font-bold text-espresso-600 uppercase tracking-wider">Returns</p>
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
</div>
</div>



<!-- Offers Slider -->
@php $allCoupons = \App\Models\Coupon::where('is_active', true)->where('end_date', '>', now())->get(); @endphp
@if($allCoupons->count())
<div class="mt-12" x-data="{ s: 0 }" x-init="setInterval(() => s = (s + 1) % {{ $allCoupons->count() }}, 4000)">
    <div class="relative rounded-2xl overflow-hidden">
        @foreach($allCoupons as $i => $coupon)
        <div x-show="s === {{ $i }}" x-transition {{ $i > 0 ? 'x-cloak' : '' }} class="rounded-2xl p-5 md:p-6 flex flex-col sm:flex-row items-center justify-between gap-4" style="background: linear-gradient(135deg, rgba(183,146,92,0.9), rgba(150,112,58,0.85)), url('https://images.unsplash.com/photo-1611241893603-3c359704e0ee?w=1200&q=60') center/cover;">
            <div>
                <p class="text-cream-200 text-[10px] font-bold uppercase tracking-[0.3em] mb-1">Limited Time Offer</p>
                <h3 class="text-white font-display text-lg md:text-2xl font-bold">{{ $coupon->description ?? $coupon->value . ($coupon->type == 'percentage' ? '% Off' : '₹ Off') }}</h3>
                <p class="text-cream-200/80 text-xs mt-1">Use code <span class="text-white font-bold bg-white/20 px-2 py-0.5 rounded">{{ $coupon->code }}</span></p>
            </div>
            <a href="{{ route('products.index') }}" class="px-5 py-2.5 bg-white text-espresso-700 font-bold text-xs uppercase rounded-full hover:bg-cream-100 transition shadow-lg shrink-0">Shop Now →</a>
        </div>
        @endforeach
    </div>
    @if($allCoupons->count() > 1)
    <div class="flex justify-center gap-1.5 mt-3">@foreach($allCoupons as $i => $c)<button @click="s={{ $i }}" :class="s==={{ $i }}?'w-6 bg-gold-500':'w-2 bg-gray-300'" class="h-2 rounded-full transition-all"></button>@endforeach</div>
    @endif
</div>
@endif

<!-- Product Banners Slider -->
@php $pBanners = \App\Models\ProductBanner::active()->get(); @endphp
@if($pBanners->count())
<div class="mt-8" x-data="{ pb: 0 }" x-init="setInterval(() => pb = (pb + 1) % {{ $pBanners->count() }}, 5000)">
    <div class="relative rounded-2xl overflow-hidden aspect-[3/1]">
        @foreach($pBanners as $i => $pb)
        <a href="{{ $pb->link ?? '#' }}" x-show="pb === {{ $i }}" x-transition {{ $i > 0 ? 'x-cloak' : '' }} class="block w-full h-full absolute inset-0">
            <img src="{{ $pb->image }}" alt="{{ $pb->title }}" class="w-full h-full object-cover rounded-2xl">
        </a>
        @endforeach
    </div>
    @if($pBanners->count() > 1)
    <div class="flex justify-center gap-1.5 mt-3">@foreach($pBanners as $i => $pb)<button @click="pb={{ $i }}" :class="pb==={{ $i }}?'w-6 bg-gold-500':'w-2 bg-gray-300'" class="h-2 rounded-full transition-all"></button>@endforeach</div>
    @endif
</div>
@endif

<!-- Why Choose Shivara -->
<div class="mt-12 bg-white rounded-3xl border border-gold-100/50 p-8 shadow-sm">
    <h3 class="font-display text-2xl font-bold text-espresso-700 text-center mb-8">Why Choose Shivara?</h3>
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
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
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($relatedProducts as $product)
            @include('partials.product-card', ['product' => $product])
        @endforeach
    </div>
</div>
@endif

</div>
</div>
@endsection
