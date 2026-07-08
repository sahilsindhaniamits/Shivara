@extends('layouts.app')
@section('title', $product->meta_title ?? $product->name . ' - Shivara')
@section('meta_description', $product->meta_description ?? $product->short_description)

@section('content')
@php
    $avgRating = $product->average_rating ?: 4.5;
    $reviewCount = $product->review_count ?: rand(12,48);
    $approvedReviews = $product->reviews()->where('is_approved', true)->with('user')->latest()->get();
    $totalReviews = $approvedReviews->count();
    if($totalReviews > 0) { $avgRating = $approvedReviews->avg('rating'); $reviewCount = $totalReviews; }
    $ratingCounts = [5=>0,4=>0,3=>0,2=>0,1=>0];
    foreach($approvedReviews as $r) { if(isset($ratingCounts[$r->rating])) $ratingCounts[$r->rating]++; }
    $activeCoupons = \App\Models\Coupon::where('is_active', true)->where('end_date', '>', now())->take(3)->get();
    $reviewImages = $approvedReviews->pluck('images')->filter()->flatten()->take(12)->values();
@endphp

<div x-data="{ qty: 1, img: 0, selectedPack: -1, lightbox: false, stickyCart: false }"
     x-init="window.addEventListener('scroll', () => { stickyCart = window.scrollY > 600 })">


<!-- Sticky Add to Cart Bar (appears on scroll) -->
<div x-show="stickyCart" x-transition:enter="transition ease-out duration-300 transform"
     x-transition:enter-start="-translate-y-full" x-transition:enter-end="translate-y-0"
     x-transition:leave="transition ease-in duration-200 transform"
     x-transition:leave-start="translate-y-0" x-transition:leave-end="-translate-y-full"
     class="fixed top-[70px] left-0 right-0 z-40 border-b shadow-lg" style="background-color:#FFFDF8; border-color: rgba(183,146,92,0.2);" x-cloak>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 flex items-center justify-between gap-4">
        <div class="flex items-center gap-3 min-w-0">
            @if($product->images->count())
            <img src="{{ str_starts_with($product->images->first()->url, '/storage/') ? '/public' . $product->images->first()->url : $product->images->first()->url }}" class="w-10 h-10 rounded-lg object-cover shrink-0 border" alt="">
            @endif
            <div class="min-w-0">
                <p class="text-sm font-bold truncate" style="color:#2C2418;">{{ $product->name }}</p>
                <p class="text-xs font-bold" style="color:#B7925C;">₹{{ number_format($product->selling_price) }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('cart.add') }}" class="shrink-0">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="quantity" value="1">
            <button type="submit" class="px-6 py-2.5 text-white text-xs font-bold uppercase tracking-wider rounded-full hover:opacity-90 transition" style="background-color:#2C2418;">Add to Cart</button>
        </form>
    </div>
</div>


<!-- Hero Product Section -->
<section class="relative" style="background: linear-gradient(180deg, #FFFDF8 0%, #FFF9ED 100%);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 pt-6 pb-16">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs text-espresso-400 mb-8">
            <a href="{{ route('home') }}" class="hover:text-gold-600 transition">Home</a>
            <span style="color:#B7925C;">›</span>
            <a href="{{ route('products.index') }}" class="hover:text-gold-600 transition">Products</a>
            @if($product->category)
            <span style="color:#B7925C;">›</span>
            <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="hover:text-gold-600 transition">{{ $product->category->name }}</a>
            @endif
            <span style="color:#B7925C;">›</span>
            <span class="font-medium" style="color:#2C2418;">{{ $product->name }}</span>
        </nav>

        <div class="grid lg:grid-cols-12 gap-8 lg:gap-12">
            <!-- LEFT: Image Gallery (55%) -->
            <div class="lg:col-span-7 space-y-4">
                <!-- Main Image -->
                <div @click="lightbox = true" class="relative rounded-3xl overflow-hidden cursor-zoom-in group" style="background-color:#f8f5f0; aspect-ratio: 1/1;" x-init="initSwipe($el, () => img = (img+1) % {{ $product->images->count() ?: 1 }}, () => img = (img-1+{{ $product->images->count() ?: 1 }}) % {{ $product->images->count() ?: 1 }})">
                    @if($product->images->count())
                        @foreach($product->images as $i => $image)
                        <img x-show="img === {{ $i }}" x-transition.opacity.duration.500ms src="{{ str_starts_with($image->url, '/storage/') ? '/public' . $image->url : $image->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover absolute inset-0 transition-transform duration-700 group-hover:scale-105">
                        @endforeach
                    @elseif($product->primaryImage)
                        <img src="{{ str_starts_with($product->primaryImage->url, '/storage/') ? '/public' . $product->primaryImage->url : $product->primaryImage->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @else
                        <img src="https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=800&h=1000&fit=crop" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @endif


                    <!-- Discount Badge -->
                    @if($product->discount_percent > 0)
                    <div class="absolute top-5 left-5 z-10">
                        <span class="px-4 py-2 text-white text-xs font-bold rounded-full shadow-xl" style="background-color:#c06d22;">{{ $product->discount_percent }}% OFF</span>
                    </div>
                    @endif
                    <!-- Arrows -->
                    @if($product->images->count() > 1)
                    <button @click.stop="img = (img - 1 + {{ $product->images->count() }}) % {{ $product->images->count() }}" class="absolute left-4 top-1/2 -translate-y-1/2 z-10 w-11 h-11 rounded-full flex items-center justify-center shadow-xl opacity-0 group-hover:opacity-100 transition-all duration-300" style="background-color: rgba(255,255,255,0.9);">
                        <svg class="w-5 h-5" style="color:#2C2418;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button @click.stop="img = (img + 1) % {{ $product->images->count() }}" class="absolute right-4 top-1/2 -translate-y-1/2 z-10 w-11 h-11 rounded-full flex items-center justify-center shadow-xl opacity-0 group-hover:opacity-100 transition-all duration-300" style="background-color: rgba(255,255,255,0.9);">
                        <svg class="w-5 h-5" style="color:#2C2418;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                    <!-- Dots -->
                    <div class="absolute bottom-5 left-1/2 -translate-x-1/2 z-10 flex gap-2">
                        @foreach($product->images as $i => $dot)
                        <button @click.stop="img = {{ $i }}" :class="img === {{ $i }} ? 'w-8 bg-white shadow-lg' : 'w-3 bg-white/60'" class="h-3 rounded-full transition-all duration-300"></button>
                        @endforeach
                    </div>
                    @endif
                </div>

                <!-- Thumbnails -->
                @if($product->images->count() > 1)
                <div class="flex gap-3 overflow-x-auto scrollbar-hide">
                    @foreach($product->images as $i => $imgItem)
                    <button @click="img = {{ $i }}" :class="img === {{ $i }} ? 'ring-2 ring-offset-2' : 'opacity-60 hover:opacity-100'" class="w-20 h-20 rounded-xl overflow-hidden shrink-0 transition-all" :style="img === {{ $i }} ? 'ring-color:#B7925C' : ''">
                        <img src="{{ str_starts_with($imgItem->url, '/storage/') ? '/public' . $imgItem->url : $imgItem->url }}" alt="" class="w-full h-full object-cover">
                    </button>
                    @endforeach
                </div>
                @endif
            </div>


            <!-- RIGHT: Product Info (45%) - Sticky -->
            <div class="lg:col-span-5 lg:sticky lg:top-[90px] lg:self-start space-y-5">
                <!-- Category Badge -->
                @if($product->category)
                <div><span class="inline-block px-3 py-1 text-[10px] font-bold uppercase tracking-[0.2em] rounded-full border" style="color:#B7925C; border-color:#B7925C;">{{ $product->category->name }}</span></div>
                @endif

                <!-- Product Name -->
                <h1 class="font-display text-3xl md:text-[2.5rem] font-bold leading-tight" style="color:#2C2418;">{{ $product->name }}</h1>

                <!-- Rating + Reviews Count -->
                <div class="flex items-center gap-3 flex-wrap">
                    <div class="flex items-center gap-0.5">
                        @for($s = 1; $s <= 5; $s++)
                        <svg class="w-5 h-5" style="{{ $s <= round($avgRating) ? 'color:#f59e0b; fill:#f59e0b;' : 'color:#e5e7eb; fill:#e5e7eb;' }}" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        @endfor
                    </div>
                    <span class="text-sm font-bold" style="color:#2C2418;">{{ number_format($avgRating, 1) }}</span>
                    <span class="text-xs" style="color:#6b7280;">({{ $reviewCount }} reviews)</span>
                    <span class="text-xs font-bold uppercase px-2.5 py-1 rounded-full" style="{{ $product->stock > 0 ? 'background-color:#dcfce7; color:#166534;' : 'background-color:#fef2f2; color:#dc2626;' }}">{{ $product->stock > 0 ? '✓ In Stock' : '✗ Sold Out' }}</span>
                </div>

                <!-- Short Description -->
                @if($product->short_description)
                <p class="text-sm leading-relaxed" style="color:#6b5442;">{{ $product->short_description }}</p>
                @endif

                <!-- Price Section -->
                <div class="rounded-2xl p-5" style="background-color: rgba(183,146,92,0.06); border: 1px solid rgba(183,146,92,0.2);">
                    <div class="flex items-baseline gap-3 flex-wrap">
                        <span class="text-4xl font-bold" style="color:#2C2418;">₹{{ number_format($product->selling_price) }}</span>
                        @if($product->discount_percent > 0)
                        <span class="text-lg line-through" style="color:#999;">₹{{ number_format($product->mrp) }}</span>
                        <span class="px-3 py-1 text-xs font-bold rounded-full" style="background-color:#dcfce7; color:#166534;">You save ₹{{ number_format($product->mrp - $product->selling_price) }}</span>
                        @endif
                    </div>
                    <p class="text-[11px] mt-2" style="color:#8c7560;">Inclusive of all taxes • Free shipping above ₹{{ config('shivara.free_shipping_threshold', 499) }}</p>
                </div>


                <!-- Pack/Variant Selector -->
                @if($product->variants->count())
                <div>
                    <p class="text-sm font-bold mb-3" style="color:#2C2418;">Choose Your Pack</p>
                    <div class="flex gap-3 overflow-x-auto scrollbar-hide pb-2">
                        @foreach($product->variants as $i => $variant)
                        <button type="button" @click="selectedPack = {{ $i }}" class="shrink-0 w-40 rounded-xl overflow-hidden text-center transition-all cursor-pointer border" :style="selectedPack === {{ $i }} ? 'border-color:#B7925C; box-shadow: 0 0 0 2px rgba(183,146,92,0.2)' : 'border-color:#e5e7eb'">
                            @if($variant->mrp > $variant->selling_price)
                            <div class="relative"><span class="absolute -top-0 left-1/2 -translate-x-1/2 text-[9px] font-bold text-white px-2.5 py-0.5 rounded-b-lg z-10" style="background-color:#16a34a;">Save ₹{{ number_format($variant->mrp - $variant->selling_price) }}</span></div>
                            @endif
                            <div class="p-4 pt-6" style="background-color:#FFFDF8;">
                                <p class="text-2xl font-bold" style="color:#2C2418;">₹{{ number_format($variant->selling_price) }}</p>
                                <p class="text-xs line-through mt-0.5" style="color:#999;">₹{{ number_format($variant->mrp) }}</p>
                            </div>
                            <div class="p-2.5 text-center text-white" style="background-color:#1a1a1a;">
                                <p class="text-xs font-bold tracking-wide">{{ $variant->name }}</p>
                                @if($variant->weight_display)
                                <p class="text-[10px] mt-0.5" style="color:#aaa;">{{ $variant->weight_display }}</p>
                                @elseif($variant->weight)
                                <p class="text-[10px] mt-0.5" style="color:#aaa;">{{ $variant->weight >= 1000 ? number_format($variant->weight/1000, 1) . ' kg' : intval($variant->weight) . ' g' }}</p>
                                @endif
                            </div>
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Quantity + Add to Cart -->
                @if($product->stock > 0)
                <form method="POST" action="{{ route('cart.add') }}" x-data="{ adding: false }" @submit.prevent="
                    adding = true;
                    let formData = new FormData($el);
                    fetch('{{ route('cart.add') }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' }, body: formData
                    }).then(r => r.json()).then(d => { adding = false; if (d.success) { window.dispatchEvent(new CustomEvent('open-cart')); window.dispatchEvent(new CustomEvent('cart-updated')); } else { window.location.reload(); }
                    }).catch(() => { adding = false; $el.submit(); });">
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" x-bind:value="qty">
                    @if($product->variants->count())
                    <input type="hidden" name="variant_id" x-bind:value="selectedPack >= 0 ? [{{ $product->variants->pluck('id')->implode(',') }}][selectedPack] : ''">
                    @endif

                    <!-- Quantity -->
                    <div class="flex items-center gap-4 mb-4">
                        <span class="text-sm font-semibold" style="color:#2C2418;">Quantity</span>
                        <div class="flex items-center rounded-full border" style="border-color:#e5e7eb;">
                            <button type="button" @click="qty = Math.max(1, qty - 1)" class="w-10 h-10 flex items-center justify-center text-lg font-bold hover:bg-gray-50 rounded-l-full transition" style="color:#2C2418;">−</button>
                            <span class="w-10 h-10 flex items-center justify-center text-sm font-bold" style="color:#2C2418;" x-text="qty"></span>
                            <button type="button" @click="qty = Math.min({{ $product->stock }}, qty + 1)" class="w-10 h-10 flex items-center justify-center text-lg font-bold hover:bg-gray-50 rounded-r-full transition" style="color:#2C2418;">+</button>
                        </div>
                    </div>


                    <!-- Buttons -->
                    <div class="space-y-3">
                        <button type="submit" :disabled="adding" class="w-full px-8 py-4 text-white font-bold text-sm uppercase tracking-wider rounded-full hover:shadow-2xl transition-all flex items-center justify-center gap-2 disabled:opacity-60" style="background-color:#2C2418;">
                            <svg x-show="!adding" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            <svg x-show="adding" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            <span x-text="adding ? 'Adding...' : 'Add to Cart'"></span>
                        </button>
                        <a href="{{ route('checkout.index') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="w-full px-8 py-4 font-bold text-sm uppercase tracking-wider rounded-full hover:shadow-xl transition-all flex items-center justify-center gap-2 border-2" style="color:#B7925C; border-color:#B7925C;">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            Buy Now
                        </a>
                    </div>

                    <!-- Return Policy -->
                    <div class="flex items-center justify-center gap-2 pt-2">
                        <svg class="w-4 h-4" style="color:#B7925C;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span class="text-xs font-medium" style="color:#6b5442;">Easy Returns — 7-day hassle-free</span>
                    </div>
                </form>
                @else
                <div class="w-full px-8 py-4 bg-gray-100 text-gray-500 font-bold text-sm uppercase tracking-wider rounded-full text-center">Currently Unavailable</div>
                @endif
            </div>
        </div>
    </div>
</section>


<!-- Active Offers Strip -->
@if($activeCoupons->count())
<section class="py-8 scroll-reveal" style="background-color:#2C2418;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="flex items-center gap-6 overflow-x-auto scrollbar-hide">
            <span class="shrink-0 text-xs font-bold uppercase tracking-wider" style="color:#B7925C;">Active Offers</span>
            @foreach($activeCoupons as $coupon)
            <div class="shrink-0 flex items-center gap-3 px-5 py-3 rounded-full border border-dashed" style="border-color: rgba(183,146,92,0.5);">
                <span class="text-xs font-bold text-white">{{ $coupon->description ?? ($coupon->type == 'percentage' ? $coupon->value.'% OFF' : '₹'.$coupon->value.' OFF') }}</span>
                <span class="px-3 py-1 text-[10px] font-bold rounded-full" style="background-color:#B7925C; color:#fff;">{{ $coupon->code }}</span>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Key Benefits Section -->
@if($product->benefits)
<section class="py-16 scroll-reveal" style="background-color:#FFFDF8;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-10">
            <span class="text-[11px] font-bold uppercase tracking-[0.3em]" style="color:#B7925C;">Why You'll Love It</span>
            <h2 class="font-display text-3xl md:text-4xl font-bold mt-2" style="color:#2C2418;">Key Benefits</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
            @foreach(array_filter(array_slice(preg_split('/[\n,]+/', $product->benefits), 0, 8)) as $i => $benefit)
            <div class="text-center p-6 rounded-2xl border transition-all hover:shadow-lg hover:-translate-y-1" style="background-color:#fff; border-color: rgba(183,146,92,0.15);">
                <div class="w-12 h-12 mx-auto mb-3 rounded-full flex items-center justify-center" style="background-color: rgba(183,146,92,0.1);">
                    <svg class="w-6 h-6" style="color:#B7925C;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/></svg>
                </div>
                <p class="text-sm font-medium" style="color:#2C2418;">{{ trim($benefit) }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif


<!-- Ingredients Spotlight -->
@if($product->ingredients)
<section class="py-16 scroll-reveal" style="background-color:#fff;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-10">
            <span class="text-[11px] font-bold uppercase tracking-[0.3em]" style="color:#B7925C;">Powered by Nature</span>
            <h2 class="font-display text-3xl md:text-4xl font-bold mt-2" style="color:#2C2418;">Key Ingredients</h2>
        </div>
        <div class="flex gap-8 overflow-x-auto scrollbar-hide pb-4 justify-center flex-wrap">
            @foreach(array_slice(explode(',', $product->ingredients), 0, 8) as $ingredient)
            <div class="shrink-0 text-center">
                <div class="w-14 h-14 mx-auto rounded-full flex items-center justify-center mb-3 border" style="background-color: rgba(183,146,92,0.05); border-color: rgba(183,146,92,0.25);">
                    <span class="text-lg">🌿</span>
                </div>
                <p class="text-sm font-semibold" style="color:#2C2418;">{{ trim($ingredient) }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Description & How to Use -->
<section class="py-16 scroll-reveal" style="background-color:#FFFDF8;">
    <div class="max-w-4xl mx-auto px-4 sm:px-6">
        <div class="grid md:grid-cols-2 gap-8">
            @if($product->description)
            <div class="p-8 rounded-3xl" style="background-color:#fff; border: 1px solid rgba(183,146,92,0.15);">
                <h3 class="text-lg font-bold mb-4 flex items-center gap-2" style="color:#2C2418;">
                    <span class="w-8 h-8 rounded-full flex items-center justify-center" style="background-color: rgba(183,146,92,0.1);"><svg class="w-4 h-4" style="color:#B7925C;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></span>
                    About This Product
                </h3>
                <div class="text-sm leading-relaxed" style="color:#6b5442;">{!! nl2br(e($product->description)) !!}</div>
            </div>
            @endif
            @if($product->how_to_use)
            <div class="p-8 rounded-3xl" style="background-color:#fff; border: 1px solid rgba(183,146,92,0.15);">
                <h3 class="text-lg font-bold mb-4 flex items-center gap-2" style="color:#2C2418;">
                    <span class="w-8 h-8 rounded-full flex items-center justify-center" style="background-color: rgba(183,146,92,0.1);"><svg class="w-4 h-4" style="color:#B7925C;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg></span>
                    How to Use
                </h3>
                <div class="text-sm leading-relaxed" style="color:#6b5442;">{!! nl2br(e($product->how_to_use)) !!}</div>
            </div>
            @endif
        </div>
    </div>
</section>


<!-- Product Banners -->
@if($product->banners && count($product->banners))
<section class="py-8 scroll-reveal" x-data="{ pb: 0 }" x-init="setInterval(() => pb = (pb + 1) % {{ count($product->banners) }}, 5000)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="relative rounded-3xl overflow-hidden aspect-[3/1]">
            @foreach($product->banners as $i => $bannerUrl)
            <div x-show="pb === {{ $i }}" x-transition.opacity class="absolute inset-0"><img src="{{ str_starts_with($bannerUrl, '/storage/') ? '/public' . $bannerUrl : $bannerUrl }}" alt="Banner" class="w-full h-full object-cover"></div>
            @endforeach
        </div>
        @if(count($product->banners) > 1)
        <div class="flex justify-center gap-2 mt-4">@foreach($product->banners as $i => $b)<button @click="pb={{ $i }}" :class="pb==={{ $i }}?'w-8':'w-3'" class="h-2.5 rounded-full transition-all" :style="pb==={{ $i }}?'background-color:#B7925C':'background-color:#ddd'"></button>@endforeach</div>
        @endif
    </div>
</section>
@endif

<!-- The Shivara Promise (8 points - 2 rows of 4) -->
<section class="py-16 scroll-reveal" style="background-color:#2C2418;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <h3 class="font-display text-2xl md:text-3xl font-bold text-center mb-12" style="color:#FFFDF8;">The Shivara Promise</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-x-8 gap-y-10">
            <div class="text-center">
                <div class="w-14 h-14 mx-auto mb-3 rounded-full flex items-center justify-center" style="background-color: rgba(183,146,92,0.15); border: 1px solid rgba(183,146,92,0.3);"><svg class="w-6 h-6" style="color:#B7925C;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg></div>
                <h4 class="text-sm font-bold" style="color:#FFFDF8;">100% Natural</h4>
                <p class="text-[11px] mt-1" style="color: rgba(255,253,248,0.5);">No chemicals or preservatives</p>
            </div>
            <div class="text-center">
                <div class="w-14 h-14 mx-auto mb-3 rounded-full flex items-center justify-center" style="background-color: rgba(183,146,92,0.15); border: 1px solid rgba(183,146,92,0.3);"><svg class="w-6 h-6" style="color:#B7925C;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg></div>
                <h4 class="text-sm font-bold" style="color:#FFFDF8;">GMP Certified</h4>
                <p class="text-[11px] mt-1" style="color: rgba(255,253,248,0.5);">Audited facilities</p>
            </div>
            <div class="text-center">
                <div class="w-14 h-14 mx-auto mb-3 rounded-full flex items-center justify-center" style="background-color: rgba(183,146,92,0.15); border: 1px solid rgba(183,146,92,0.3);"><svg class="w-6 h-6" style="color:#B7925C;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg></div>
                <h4 class="text-sm font-bold" style="color:#FFFDF8;">Lab Tested</h4>
                <p class="text-[11px] mt-1" style="color: rgba(255,253,248,0.5);">Purity verified by experts</p>
            </div>
            <div class="text-center">
                <div class="w-14 h-14 mx-auto mb-3 rounded-full flex items-center justify-center" style="background-color: rgba(183,146,92,0.15); border: 1px solid rgba(183,146,92,0.3);"><svg class="w-6 h-6" style="color:#B7925C;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg></div>
                <h4 class="text-sm font-bold" style="color:#FFFDF8;">5000+ Happy Customers</h4>
                <p class="text-[11px] mt-1" style="color: rgba(255,253,248,0.5);">Trusted across India</p>
            </div>
            <div class="text-center">
                <div class="w-14 h-14 mx-auto mb-3 rounded-full flex items-center justify-center" style="background-color: rgba(183,146,92,0.15); border: 1px solid rgba(183,146,92,0.3);"><svg class="w-6 h-6" style="color:#B7925C;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg></div>
                <h4 class="text-[11px] font-bold uppercase tracking-wider" style="color:#FFFDF8;">Single-Origin</h4>
                <p class="text-[11px] mt-1" style="color: rgba(255,253,248,0.5);">Heritage farms, Rajasthan</p>
            </div>
            <div class="text-center">
                <div class="w-14 h-14 mx-auto mb-3 rounded-full flex items-center justify-center" style="background-color: rgba(183,146,92,0.15); border: 1px solid rgba(183,146,92,0.3);"><svg class="w-6 h-6" style="color:#B7925C;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg></div>
                <h4 class="text-[11px] font-bold uppercase tracking-wider" style="color:#FFFDF8;">GMP Certified</h4>
                <p class="text-[11px] mt-1" style="color: rgba(255,253,248,0.5);">Audited facilities</p>
            </div>
            <div class="text-center">
                <div class="w-14 h-14 mx-auto mb-3 rounded-full flex items-center justify-center" style="background-color: rgba(183,146,92,0.15); border: 1px solid rgba(183,146,92,0.3);"><svg class="w-6 h-6" style="color:#B7925C;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg></div>
                <h4 class="text-[11px] font-bold uppercase tracking-wider" style="color:#FFFDF8;">Free Shipping</h4>
                <p class="text-[11px] mt-1" style="color: rgba(255,253,248,0.5);">All orders, pan-India</p>
            </div>
            <div class="text-center">
                <div class="w-14 h-14 mx-auto mb-3 rounded-full flex items-center justify-center" style="background-color: rgba(183,146,92,0.15); border: 1px solid rgba(183,146,92,0.3);"><svg class="w-6 h-6" style="color:#B7925C;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg></div>
                <h4 class="text-[11px] font-bold uppercase tracking-wider" style="color:#FFFDF8;">Easy Returns</h4>
                <p class="text-[11px] mt-1" style="color: rgba(255,253,248,0.5);">7-day hassle-free</p>
            </div>
        </div>
    </div>
</section>

<!-- Customer Reviews -->
<section class="py-16 scroll-reveal" style="background-color:#fff;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-10">
            <span class="text-[11px] font-bold uppercase tracking-[0.3em]" style="color:#B7925C;">Social Proof</span>
            <h2 class="font-display text-3xl md:text-4xl font-bold mt-2" style="color:#2C2418;">What Customers Say</h2>
        </div>

        <!-- Rating Summary Bar -->
        <div class="flex flex-col md:flex-row items-center justify-center gap-8 mb-10 p-6 rounded-2xl" style="background-color:#FFFDF8; border: 1px solid rgba(183,146,92,0.15);">
            <div class="text-center">
                <p class="text-5xl font-bold" style="color:#2C2418;">{{ number_format($avgRating, 1) }}</p>
                <div class="flex items-center gap-0.5 mt-1 justify-center">
                    @for($s = 1; $s <= 5; $s++)
                    <svg class="w-4 h-4 {{ $s <= round($avgRating) ? 'fill-amber-400 text-amber-400' : 'fill-gray-200 text-gray-200' }}" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    @endfor
                </div>
                <p class="text-xs mt-1" style="color:#8c7560;">{{ $totalReviews }} reviews</p>
            </div>
            <div class="flex-1 max-w-xs space-y-1.5 w-full">
                @foreach($ratingCounts as $star => $count)
                <div class="flex items-center gap-2">
                    <span class="text-xs w-4 text-right font-bold" style="color:#2C2418;">{{ $star }}</span>
                    <svg class="w-3.5 h-3.5 fill-amber-400 text-amber-400" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <div class="flex-1 h-2 rounded-full overflow-hidden" style="background-color:#f3f4f6;"><div class="h-full rounded-full" style="width: {{ $totalReviews > 0 ? ($count/$totalReviews)*100 : 0 }}%; background-color:#B7925C;"></div></div>
                    <span class="text-[10px] w-6" style="color:#8c7560;">{{ $count }}</span>
                </div>
                @endforeach
            </div>
        </div>


        <!-- Customer Photos -->
        @if($reviewImages->count())
        <div class="mb-10">
            <h4 class="text-sm font-bold mb-4" style="color:#2C2418;">Customer Photos</h4>
            <div class="flex gap-3 overflow-x-auto scrollbar-hide pb-2">
                @foreach($reviewImages as $rImg)
                <div class="shrink-0 w-24 h-24 rounded-xl overflow-hidden border cursor-pointer hover:opacity-80 transition" style="border-color:#e5e7eb;">
                    <img src="{{ str_starts_with($rImg, '/storage/') ? '/public' . $rImg : $rImg }}" alt="Customer photo" class="w-full h-full object-cover" loading="lazy">
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Write Review -->
        @auth
        <div class="mb-10 p-6 rounded-2xl" style="background-color:#FFFDF8; border: 1px solid rgba(183,146,92,0.15);">
            <h4 class="text-sm font-bold mb-4" style="color:#2C2418;">Share Your Experience</h4>
            <form method="POST" action="{{ route('products.review', $product->slug) }}" enctype="multipart/form-data" class="space-y-4" x-data="{ rating: 0 }">
                @csrf
                <div class="flex items-center gap-1">
                    @for($s = 1; $s <= 5; $s++)
                    <button type="button" @click="rating = {{ $s }}" :class="{{ $s }} <= rating ? 'text-amber-400' : 'text-gray-300'" class="transition hover:scale-110">
                        <svg class="w-7 h-7 fill-current" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    </button>
                    @endfor
                </div>
                <input type="hidden" name="rating" x-bind:value="rating">
                <textarea name="comment" rows="3" placeholder="Tell others about your experience..." class="w-full px-4 py-3 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-gold-200" style="border-color:#e5e7eb;" required></textarea>
                <input type="file" name="review_images[]" multiple accept="image/*" class="text-xs">
                <button type="submit" class="px-6 py-2.5 text-white text-xs font-bold uppercase rounded-full hover:opacity-90 transition" style="background-color:#2C2418;">Submit Review</button>
            </form>
        </div>
        @else
        <div class="mb-10 p-4 rounded-xl text-center" style="background-color:#FFFDF8; border: 1px solid rgba(183,146,92,0.15);">
            <p class="text-sm" style="color:#6b5442;"><a href="{{ route('login') }}" class="font-bold hover:underline" style="color:#B7925C;">Log in</a> to write a review</p>
        </div>
        @endauth

        <!-- Reviews List -->
        @if($approvedReviews->count())
        <div class="grid md:grid-cols-2 gap-5">
            @foreach($approvedReviews->take(6) as $review)
            <div class="p-5 rounded-2xl border" style="border-color: rgba(183,146,92,0.1); background-color:#FFFDF8;">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-[10px] font-bold text-white" style="background-color:#2C2418;">{{ substr($review->user->name ?? 'C', 0, 1) }}</div>
                        <div>
                            <p class="text-sm font-bold" style="color:#2C2418;">{{ $review->user->name ?? 'Customer' }}</p>
                            <div class="flex items-center gap-0.5">
                                @for($s = 1; $s <= 5; $s++)<svg class="w-3 h-3 {{ $s <= $review->rating ? 'fill-amber-400 text-amber-400' : 'fill-gray-200 text-gray-200' }}" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>@endfor
                            </div>
                        </div>
                    </div>
                    <span class="text-[10px]" style="color:#8c7560;">{{ $review->created_at->diffForHumans() }}</span>
                </div>
                <p class="text-sm leading-relaxed" style="color:#6b5442;">{{ $review->comment }}</p>
                @if($review->images && count($review->images))
                <div class="flex gap-2 mt-3">@foreach(array_slice($review->images, 0, 3) as $revImg)<div class="w-14 h-14 rounded-lg overflow-hidden border" style="border-color:#e5e7eb;"><img src="{{ str_starts_with($revImg, '/storage/') ? '/public' . $revImg : $revImg }}" class="w-full h-full object-cover" loading="lazy"></div>@endforeach</div>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <p class="text-sm text-center py-8" style="color:#8c7560;">No reviews yet. Be the first to share your experience!</p>
        @endif
    </div>
</section>


<!-- Related Products -->
@if($relatedProducts->count())
<section class="py-16 scroll-reveal" style="background-color:#FFFDF8;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-10">
            <span class="text-[11px] font-bold uppercase tracking-[0.3em]" style="color:#B7925C;">Complete Your Routine</span>
            <h2 class="font-display text-3xl font-bold mt-2" style="color:#2C2418;">You May Also Like</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-5">
            @foreach($relatedProducts as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
@endif


<!-- Image Lightbox -->
<div x-show="lightbox" x-cloak x-transition.opacity class="fixed inset-0 z-[200] flex items-center justify-center p-4" style="background-color: rgba(0,0,0,0.92);" @click.self="lightbox = false" @keydown.escape.window="lightbox = false">
    <button @click="lightbox = false" class="absolute top-4 right-4 w-11 h-11 rounded-full flex items-center justify-center text-white z-10" style="background-color: rgba(255,255,255,0.1);">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    @if($product->images->count() > 1)
    <button @click="img = (img - 1 + {{ $product->images->count() }}) % {{ $product->images->count() }}" class="absolute left-4 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full flex items-center justify-center text-white" style="background-color: rgba(255,255,255,0.1);"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></button>
    <button @click="img = (img + 1) % {{ $product->images->count() }}" class="absolute right-4 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full flex items-center justify-center text-white" style="background-color: rgba(255,255,255,0.1);"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></button>
    @endif
    <div class="max-w-2xl w-full">
        @if($product->images->count())
        @foreach($product->images as $i => $lbImg)
        <img x-show="img === {{ $i }}" x-transition.opacity src="{{ str_starts_with($lbImg->url, '/storage/') ? '/public' . $lbImg->url : $lbImg->url }}" alt="{{ $product->name }}" class="w-full max-h-[80vh] object-contain rounded-2xl mx-auto">
        @endforeach
        @endif
    </div>
</div>

</div>{{-- end main x-data --}}
@endsection
