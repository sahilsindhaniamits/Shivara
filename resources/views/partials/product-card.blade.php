<div class="product-card group bg-white rounded-2xl border border-gray-100 overflow-hidden">
    <a href="{{ route('products.show', $product->slug) }}" class="block">
        <!-- Image -->
        <div class="relative aspect-square overflow-hidden bg-gray-50">
            @if($product->primaryImage && $product->primaryImage->url)
                <img src="{{ $product->primaryImage->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
            @else
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-brand-50 to-brand-100">
                    <svg class="w-16 h-16 text-brand-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
            @endif

            <!-- Badges -->
            @if($product->discount_percent > 0)
            <div class="absolute top-3 left-3">
                <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded-md">-{{ $product->discount_percent }}%</span>
            </div>
            @endif

            @if($product->stock <= 0)
            <div class="absolute inset-0 bg-white/70 backdrop-blur-[2px] flex items-center justify-center">
                <span class="text-xs font-bold uppercase tracking-widest text-gray-500 bg-white px-4 py-2 rounded-full border">Sold Out</span>
            </div>
            @endif

            <!-- Quick Add -->
            @if($product->stock > 0)
            <div class="absolute bottom-0 left-0 right-0 p-3 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                <form method="POST" action="{{ route('cart.add') }}">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="w-full bg-dark hover:bg-dark-light text-white text-xs font-semibold uppercase tracking-wider py-3 rounded-xl transition shadow-lg">
                        Add to Cart
                    </button>
                </form>
            </div>
            @endif
        </div>

        <!-- Content -->
        <div class="p-4">
            @if($product->category)
            <p class="text-[10px] font-semibold uppercase tracking-[0.15em] text-brand-600 mb-1.5">{{ $product->category->name }}</p>
            @endif
            <h3 class="text-sm font-semibold text-gray-900 line-clamp-2 group-hover:text-brand-700 transition leading-snug">{{ $product->name }}</h3>
            <div class="flex items-baseline gap-2 mt-2.5">
                <span class="text-base font-bold text-gray-900">₹{{ number_format($product->selling_price) }}</span>
                @if($product->discount_percent > 0)
                <span class="text-sm text-gray-400 line-through">₹{{ number_format($product->mrp) }}</span>
                @endif
            </div>
            @if($product->average_rating > 0)
            <div class="flex items-center gap-1 mt-2">
                <svg class="w-3.5 h-3.5 text-amber-400 fill-amber-400" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                <span class="text-xs text-gray-500">{{ number_format($product->average_rating, 1) }}</span>
                <span class="text-xs text-gray-300">({{ $product->review_count }})</span>
            </div>
            @endif
        </div>
    </a>
</div>
