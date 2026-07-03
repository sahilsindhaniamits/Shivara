@extends('layouts.app')
@section('title', $product->meta_title ?? $product->name . ' - Shivara')
@section('meta_description', $product->meta_description ?? $product->short_description)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 md:py-10">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-8">
        <a href="{{ route('home') }}" class="hover:text-brand-600">Home</a>
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('products.index') }}" class="hover:text-brand-600">Products</a>
        @if($product->category)
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="hover:text-brand-600">{{ $product->category->name }}</a>
        @endif
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-900 font-medium truncate max-w-[200px]">{{ $product->name }}</span>
    </nav>

    <div class="grid lg:grid-cols-2 gap-8 lg:gap-14" x-data="{ selectedVariant: null, quantity: 1, selectedImage: 0, activeTab: 'description' }">
        <!-- Image Gallery -->
        <div class="space-y-4">
            <div class="aspect-square rounded-2xl overflow-hidden bg-gray-50 border border-gray-100 relative">
                @if($product->images->count())
                    @foreach($product->images as $i => $image)
                    <img x-show="selectedImage === {{ $i }}" src="{{ $image->url }}" alt="{{ $image->alt ?? $product->name }}" class="w-full h-full object-cover" x-transition>
                    @endforeach
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-brand-50 to-brand-100">
                        <svg class="w-32 h-32 text-brand-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="0.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                @endif
                @if($product->discount_percent > 0)
                <span class="absolute top-4 left-4 bg-red-500 text-white text-xs font-bold px-3 py-1.5 rounded-lg">{{ $product->discount_percent }}% OFF</span>
                @endif
            </div>
            @if($product->images->count() > 1)
            <div class="flex gap-3 overflow-x-auto scrollbar-hide pb-1">
                @foreach($product->images as $i => $image)
                <button @click="selectedImage = {{ $i }}" :class="selectedImage === {{ $i }} ? 'ring-2 ring-brand-500 ring-offset-2' : 'ring-1 ring-gray-200'" class="w-20 h-20 rounded-xl overflow-hidden shrink-0 transition">
                    <img src="{{ $image->url }}" alt="" class="w-full h-full object-cover">
                </button>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Product Info -->
        <div class="space-y-6">
            <div>
                @if($product->category)
                <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="text-xs font-bold uppercase tracking-[0.15em] text-brand-600 hover:text-brand-700">{{ $product->category->name }}</a>
                @endif
                <h1 class="text-2xl md:text-3xl font-display font-bold text-gray-900 mt-2 leading-tight">{{ $product->name }}</h1>
                @if($product->short_description)
                <p class="text-gray-500 mt-2 text-sm leading-relaxed">{{ $product->short_description }}</p>
                @endif
            </div>

            <!-- Rating -->
            <div class="flex items-center gap-3">
                @if($product->average_rating > 0)
                <div class="flex items-center gap-1.5 bg-green-50 px-3 py-1.5 rounded-full">
                    <svg class="w-4 h-4 text-green-600 fill-green-600" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <span class="text-sm font-bold text-green-700">{{ number_format($product->average_rating, 1) }}</span>
                </div>
                <span class="text-sm text-gray-400">{{ $product->review_count }} reviews</span>
                <span class="text-gray-200">|</span>
                @endif
                <span class="text-sm font-medium {{ $product->stock > 0 ? 'text-green-600' : 'text-red-500' }}">
                    {{ $product->stock > 0 ? ($product->stock > 10 ? 'In Stock' : 'Only '.$product->stock.' left') : 'Out of Stock' }}
                </span>
            </div>

            <!-- Price -->
            <div class="bg-gradient-to-r from-brand-50 to-transparent p-5 rounded-xl border border-brand-100">
                <div class="flex items-baseline gap-3">
                    <span class="text-3xl font-bold text-gray-900">₹{{ number_format($product->selling_price) }}</span>
                    @if($product->discount_percent > 0)
                    <span class="text-lg text-gray-400 line-through">₹{{ number_format($product->mrp) }}</span>
                    <span class="px-2.5 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-lg">Save ₹{{ number_format($product->mrp - $product->selling_price) }}</span>
                    @endif
                </div>
                <p class="text-xs text-gray-500 mt-1.5">Inclusive of all taxes • Free shipping above ₹{{ config('shivara.free_shipping_threshold') }}</p>
            </div>

            <!-- Variants -->
            @if($product->variants->count())
            <div>
                <h3 class="text-sm font-bold text-gray-800 mb-3">Select Pack Size</h3>
                <div class="flex flex-wrap gap-3">
                    @foreach($product->variants as $i => $variant)
                    <button @click="selectedVariant = {{ $i }}" :class="selectedVariant === {{ $i }} ? 'border-brand-500 bg-brand-50 text-brand-700' : 'border-gray-200 text-gray-700 hover:border-gray-300'" class="px-5 py-3 rounded-xl border-2 text-sm font-medium transition">
                        <span class="block font-semibold">{{ $variant->name }}</span>
                        <span class="text-xs opacity-70">₹{{ number_format($variant->selling_price) }}</span>
                    </button>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Add to Cart -->
            @if($product->stock > 0)
            <form method="POST" action="{{ route('cart.add') }}" class="flex flex-col sm:flex-row gap-3">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                @if($product->variants->count())
                <template x-if="selectedVariant !== null">
                    <input type="hidden" name="variant_id" :value="[{{ $product->variants->pluck('id')->implode(',') }}][selectedVariant]">
                </template>
                @endif

                <!-- Quantity -->
                <div class="flex items-center border-2 border-gray-200 rounded-xl">
                    <button type="button" @click="quantity = Math.max(1, quantity - 1)" class="px-4 py-3 text-gray-500 hover:text-gray-900 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                    </button>
                    <input type="number" name="quantity" x-model="quantity" min="1" max="{{ $product->stock }}" class="w-12 text-center border-0 font-bold text-gray-900 focus:outline-none text-sm">
                    <button type="button" @click="quantity = Math.min({{ $product->stock }}, quantity + 1)" class="px-4 py-3 text-gray-500 hover:text-gray-900 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </button>
                </div>

                <!-- Add Button -->
                <button type="submit" class="flex-1 px-8 py-3.5 bg-dark text-white font-semibold rounded-xl hover:bg-dark-light transition flex items-center justify-center gap-2 shadow-lg shadow-dark/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    Add to Cart
                </button>
            </form>

            @auth
            <form method="POST" action="{{ route('account.wishlist.add') }}">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <button type="submit" class="flex items-center gap-2 text-sm text-gray-500 hover:text-red-500 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    Add to Wishlist
                </button>
            </form>
            @endauth
            @endif

            <!-- Trust Badges -->
            <div class="grid grid-cols-3 gap-3 pt-2">
                <div class="text-center p-3 bg-gray-50 rounded-xl">
                    <svg class="w-5 h-5 text-brand-600 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <p class="text-[10px] font-semibold text-gray-700">Free Delivery</p>
                </div>
                <div class="text-center p-3 bg-gray-50 rounded-xl">
                    <svg class="w-5 h-5 text-brand-600 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <p class="text-[10px] font-semibold text-gray-700">100% Genuine</p>
                </div>
                <div class="text-center p-3 bg-gray-50 rounded-xl">
                    <svg class="w-5 h-5 text-brand-600 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <p class="text-[10px] font-semibold text-gray-700">7-Day Returns</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="mt-16" x-data="{ tab: 'description' }">
        <div class="flex gap-1 border-b border-gray-200 overflow-x-auto scrollbar-hide">
            <button @click="tab = 'description'" :class="tab === 'description' ? 'border-brand-600 text-brand-700 bg-brand-50/50' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-5 py-3.5 text-sm font-semibold border-b-2 -mb-px whitespace-nowrap rounded-t-lg transition">Description</button>
            <button @click="tab = 'ingredients'" :class="tab === 'ingredients' ? 'border-brand-600 text-brand-700 bg-brand-50/50' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-5 py-3.5 text-sm font-semibold border-b-2 -mb-px whitespace-nowrap rounded-t-lg transition">Ingredients</button>
            <button @click="tab = 'usage'" :class="tab === 'usage' ? 'border-brand-600 text-brand-700 bg-brand-50/50' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-5 py-3.5 text-sm font-semibold border-b-2 -mb-px whitespace-nowrap rounded-t-lg transition">How to Use</button>
            <button @click="tab = 'reviews'" :class="tab === 'reviews' ? 'border-brand-600 text-brand-700 bg-brand-50/50' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-5 py-3.5 text-sm font-semibold border-b-2 -mb-px whitespace-nowrap rounded-t-lg transition">Reviews ({{ $product->review_count }})</button>
        </div>

        <div class="py-8 max-w-3xl">
            <!-- Description -->
            <div x-show="tab === 'description'">
                <div class="prose prose-sm prose-gray max-w-none">
                    {!! nl2br(e($product->description)) !!}
                </div>
                @if($product->benefits)
                <h3 class="text-lg font-bold text-gray-900 mt-8 mb-4">Key Benefits</h3>
                <div class="space-y-3">
                    @foreach(explode("\n", $product->benefits) as $benefit)
                    @if(trim($benefit))
                    <div class="flex items-start gap-3">
                        <span class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <span class="text-sm text-gray-700">{{ trim(str_replace(['•', '-'], '', $benefit)) }}</span>
                    </div>
                    @endif
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Ingredients -->
            <div x-show="tab === 'ingredients'" x-cloak>
                @if($product->ingredients)
                <div class="grid sm:grid-cols-2 gap-3">
                    @foreach(explode(',', $product->ingredients) as $ing)
                    @if(trim($ing))
                    <div class="flex items-center gap-3 p-3.5 bg-gray-50 rounded-xl border border-gray-100">
                        <div class="w-8 h-8 bg-brand-100 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700">{{ trim($ing) }}</span>
                    </div>
                    @endif
                    @endforeach
                </div>
                @else
                <p class="text-gray-500">Ingredient information not available.</p>
                @endif
            </div>

            <!-- How to Use -->
            <div x-show="tab === 'usage'" x-cloak>
                @if($product->how_to_use)
                <div class="bg-brand-50 border border-brand-100 rounded-2xl p-6">
                    <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Recommended Usage
                    </h3>
                    <p class="text-gray-700 leading-relaxed">{{ $product->how_to_use }}</p>
                </div>
                @else
                <p class="text-gray-500">Usage instructions not available.</p>
                @endif
            </div>

            <!-- Reviews -->
            <div x-show="tab === 'reviews'" x-cloak>
                @if($product->reviews->count())
                <div class="space-y-6">
                    @foreach($product->reviews as $review)
                    <div class="border-b border-gray-100 pb-6 last:border-0">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-brand-100 rounded-full flex items-center justify-center text-xs font-bold text-brand-700">{{ substr($review->user->name, 0, 1) }}</div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $review->user->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-0.5">
                                @for($s = 1; $s <= 5; $s++)
                                <svg class="w-4 h-4 {{ $s <= $review->rating ? 'text-amber-400 fill-amber-400' : 'text-gray-200 fill-gray-200' }}" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                @endfor
                            </div>
                        </div>
                        @if($review->title)<p class="font-medium text-gray-800 mb-1">{{ $review->title }}</p>@endif
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $review->comment }}</p>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-12">
                    <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    <p class="text-gray-500">No reviews yet. Be the first to review this product!</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count())
    <section class="mt-16 pt-12 border-t border-gray-100">
        <h2 class="font-display text-2xl font-bold text-dark mb-8">You may also like</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-5">
            @foreach($relatedProducts as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </section>
    @endif
</div>
@endsection
