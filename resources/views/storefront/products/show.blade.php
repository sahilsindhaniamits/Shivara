@extends('layouts.app')
@section('title', $product->name . ' - Shivara')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="text-sm text-gray-500 mb-6 flex items-center gap-1">
        <a href="{{ route('home') }}" class="hover:text-primary">Home</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <a href="{{ route('products.index') }}" class="hover:text-primary">Products</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-primary font-medium">{{ $product->name }}</span>
    </nav>

    <div class="grid lg:grid-cols-2 gap-8 lg:gap-12" x-data="{ selectedVariant: 0, quantity: 1, activeTab: 'description', selectedImage: 0 }">
        <!-- Images -->
        <div class="space-y-4">
            <div class="aspect-square bg-accent/50 rounded-2xl overflow-hidden relative border border-border">
                @if($product->images->count())
                    @foreach($product->images as $i => $image)
                    <img x-show="selectedImage === {{ $i }}" src="{{ $image->url }}" alt="{{ $image->alt ?? $product->name }}" class="w-full h-full object-cover">
                    @endforeach
                @else
                    <div class="w-full h-full flex items-center justify-center"><span class="text-[120px]">🌿</span></div>
                @endif
                @if($product->discount_percent > 0)
                <div class="absolute top-4 left-4"><span class="bg-[#8B4513] text-white text-xs font-medium px-3 py-1 rounded-full">{{ $product->discount_percent }}% OFF</span></div>
                @endif
            </div>
            @if($product->images->count() > 1)
            <div class="flex gap-3">
                @foreach($product->images as $i => $image)
                <button @click="selectedImage = {{ $i }}" :class="selectedImage === {{ $i }} ? 'border-primary shadow-md' : 'border-border'" class="w-20 h-20 rounded-xl overflow-hidden border-2 transition">
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
                <p class="text-sm text-secondary font-medium mb-1">{{ $product->category->name }}</p>
                @endif
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">{{ $product->name }}</h1>
                <p class="text-gray-500 mt-2">{{ $product->short_description }}</p>
            </div>

            <!-- Rating -->
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-1 bg-green-50 px-3 py-1 rounded-full">
                    <i data-lucide="star" class="w-4 h-4 text-green-600 fill-green-600"></i>
                    <span class="font-semibold text-green-700">{{ number_format($product->average_rating, 1) }}</span>
                </div>
                <span class="text-sm text-gray-500">{{ $product->review_count }} Reviews</span>
                <span class="text-sm text-gray-400">|</span>
                <span class="text-sm {{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }} font-medium">
                    {{ $product->stock > 0 ? 'In Stock' : 'Out of Stock' }}
                </span>
            </div>

            <!-- Price -->
            <div class="bg-accent/50 p-4 rounded-xl border border-primary/10">
                <div class="flex items-baseline gap-3">
                    <span class="text-3xl font-bold text-primary">₹{{ number_format($product->selling_price) }}</span>
                    @if($product->discount_percent > 0)
                    <span class="text-lg text-gray-400 line-through">₹{{ number_format($product->mrp) }}</span>
                    <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs font-medium rounded-full">Save ₹{{ number_format($product->mrp - $product->selling_price) }}</span>
                    @endif
                </div>
                <p class="text-xs text-gray-500 mt-1">Inclusive of all taxes | Free shipping on orders above ₹{{ config('shivara.free_shipping_threshold') }}</p>
            </div>

            <!-- Variants -->
            @if($product->variants->count())
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Select Pack Size:</h3>
                <div class="flex flex-wrap gap-3">
                    @foreach($product->variants as $i => $variant)
                    <button @click="selectedVariant = {{ $i }}" :class="selectedVariant === {{ $i }} ? 'border-primary bg-primary/5 text-primary' : 'border-border text-gray-600'" class="px-4 py-3 rounded-xl border-2 text-sm font-medium transition hover:border-primary/50">
                        <span class="block">{{ $variant->name }}</span>
                        <span class="text-xs text-gray-400 mt-0.5">₹{{ number_format($variant->selling_price) }}</span>
                    </button>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Add to Cart -->
            @if($product->stock > 0)
            <form method="POST" action="{{ route('cart.add') }}" class="flex flex-col sm:flex-row gap-4">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                @if($product->variants->count())
                <input type="hidden" name="variant_id" x-bind:value="'{{ $product->variants->pluck('id')->implode("','") }}'.split(',')[selectedVariant]">
                @endif

                <div class="flex items-center border border-border rounded-xl">
                    <button type="button" @click="quantity = Math.max(1, quantity - 1)" class="px-4 py-3 text-gray-500 hover:text-primary">
                        <i data-lucide="minus" class="w-4 h-4"></i>
                    </button>
                    <input type="number" name="quantity" x-model="quantity" min="1" max="{{ $product->stock }}" class="w-12 text-center border-0 font-semibold text-gray-800 focus:outline-none">
                    <button type="button" @click="quantity = Math.min({{ $product->stock }}, quantity + 1)" class="px-4 py-3 text-gray-500 hover:text-primary">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                    </button>
                </div>

                <button type="submit" class="flex-1 px-8 py-3 bg-primary text-white font-medium rounded-xl hover:bg-primary-dark transition flex items-center justify-center gap-2">
                    <i data-lucide="shopping-cart" class="w-5 h-5"></i> Add to Cart
                </button>
            </form>
            @endif

            <!-- Trust -->
            <div class="grid grid-cols-3 gap-4">
                <div class="text-center p-3 bg-accent/50 rounded-xl">
                    <i data-lucide="truck" class="w-5 h-5 text-primary mx-auto mb-1"></i>
                    <p class="text-xs font-medium text-gray-700">Free Delivery</p>
                </div>
                <div class="text-center p-3 bg-accent/50 rounded-xl">
                    <i data-lucide="shield" class="w-5 h-5 text-primary mx-auto mb-1"></i>
                    <p class="text-xs font-medium text-gray-700">Genuine Product</p>
                </div>
                <div class="text-center p-3 bg-accent/50 rounded-xl">
                    <i data-lucide="refresh-cw" class="w-5 h-5 text-primary mx-auto mb-1"></i>
                    <p class="text-xs font-medium text-gray-700">7-Day Returns</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="mt-16" x-data="{ activeTab: 'description' }">
        <div class="flex border-b border-border overflow-x-auto">
            <button @click="activeTab = 'description'" :class="activeTab === 'description' ? 'border-primary text-primary' : 'border-transparent text-gray-500'" class="px-6 py-4 text-sm font-medium whitespace-nowrap transition border-b-2 -mb-px">Description</button>
            <button @click="activeTab = 'ingredients'" :class="activeTab === 'ingredients' ? 'border-primary text-primary' : 'border-transparent text-gray-500'" class="px-6 py-4 text-sm font-medium whitespace-nowrap transition border-b-2 -mb-px">Ingredients</button>
            <button @click="activeTab = 'howToUse'" :class="activeTab === 'howToUse' ? 'border-primary text-primary' : 'border-transparent text-gray-500'" class="px-6 py-4 text-sm font-medium whitespace-nowrap transition border-b-2 -mb-px">How to Use</button>
            <button @click="activeTab = 'reviews'" :class="activeTab === 'reviews' ? 'border-primary text-primary' : 'border-transparent text-gray-500'" class="px-6 py-4 text-sm font-medium whitespace-nowrap transition border-b-2 -mb-px">Reviews ({{ $product->review_count }})</button>
        </div>
        <div class="py-8">
            <div x-show="activeTab === 'description'">
                <div class="prose max-w-none text-gray-600 leading-relaxed">{!! nl2br(e($product->description)) !!}</div>
                @if($product->benefits)
                <h3 class="text-lg font-semibold text-gray-800 mt-6 mb-3">Key Benefits:</h3>
                <div class="space-y-2">
                    @foreach(explode("\n", $product->benefits) as $benefit)
                    @if(trim($benefit))
                    <div class="flex items-start gap-2">
                        <i data-lucide="check" class="w-4 h-4 text-primary shrink-0 mt-0.5"></i>
                        <span class="text-gray-600">{{ trim(str_replace('•', '', $benefit)) }}</span>
                    </div>
                    @endif
                    @endforeach
                </div>
                @endif
            </div>
            <div x-show="activeTab === 'ingredients'" x-cloak>
                @if($product->ingredients)
                <div class="grid md:grid-cols-2 gap-4">
                    @foreach(explode(',', $product->ingredients) as $ing)
                    <div class="flex items-center gap-3 p-3 bg-accent/50 rounded-xl">
                        <span class="text-2xl">🌿</span>
                        <span class="text-sm font-medium text-gray-700">{{ trim($ing) }}</span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
            <div x-show="activeTab === 'howToUse'" x-cloak>
                <div class="bg-accent/30 p-6 rounded-2xl">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Recommended Usage:</h3>
                    <p class="text-gray-600">{{ $product->how_to_use }}</p>
                </div>
            </div>
            <div x-show="activeTab === 'reviews'" x-cloak>
                @if($product->reviews->count())
                    @foreach($product->reviews as $review)
                    <div class="border-b border-border py-4">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-sm font-medium">{{ $review->user->name }}</span>
                            <span class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="flex items-center gap-1 mb-2">
                            @for($s = 1; $s <= 5; $s++)
                            <i data-lucide="star" class="w-4 h-4 {{ $s <= $review->rating ? 'text-yellow-400 fill-yellow-400' : 'text-gray-300' }}"></i>
                            @endfor
                        </div>
                        <p class="text-gray-600 text-sm">{{ $review->comment }}</p>
                    </div>
                    @endforeach
                @else
                    <p class="text-center py-12 text-gray-500">No reviews yet. Be the first to review!</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count())
    <section class="mt-16 border-t border-border pt-12">
        <h2 class="font-editorial text-2xl text-secondary mb-8 text-center">You may also like</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($relatedProducts as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </section>
    @endif
</div>
@endsection
