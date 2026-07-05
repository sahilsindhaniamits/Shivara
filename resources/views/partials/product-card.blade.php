<div class="product-card group bg-white rounded-2xl border border-gold-100/50 overflow-hidden">
    <a href="{{ route('products.show', $product->slug) }}" class="block">
        <div class="relative aspect-square overflow-hidden bg-cream-100">
            @if($product->primaryImage && $product->primaryImage->url)
                <img src="{{ str_starts_with($product->primaryImage->url, '/storage/') ? '/public' . $product->primaryImage->url : $product->primaryImage->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy">
            @else
                <img src="https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=400&h=400&fit=crop&q=80" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy">
            @endif
            @if($product->discount_percent > 0)
            <span class="absolute top-3 left-3 bg-gradient-to-r from-red-500 to-red-600 text-white text-[10px] font-bold px-2.5 py-1 rounded-lg shadow-sm">-{{ $product->discount_percent }}%</span>
            @endif
            @if($product->stock <= 0)
            <div class="absolute inset-0 bg-cream-50/80 backdrop-blur-[1px] flex items-center justify-center">
                <span class="text-xs font-bold uppercase tracking-widest text-espresso-500 bg-white px-4 py-2 rounded-full border border-espresso-200">Sold Out</span>
            </div>
            @endif
            @if($product->stock > 0)
            <div class="absolute bottom-0 left-0 right-0 p-3 translate-y-full group-hover:translate-y-0 transition-transform duration-400">
                <form method="POST" action="{{ route('cart.add') }}">@csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="w-full bg-espresso-700 hover:bg-espresso-600 text-cream-50 text-xs font-bold uppercase tracking-wider py-3 rounded-xl transition shadow-lg">
                        Add to Cart
                    </button>
                </form>
            </div>
            @endif
        </div>
        <div class="p-4">
            @if($product->category)
            <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-gold-500 mb-1.5">{{ $product->category->name }}</p>
            @endif
            <h3 class="text-sm font-semibold text-espresso-700 line-clamp-2 group-hover:text-gold-600 transition leading-snug min-h-[2.5rem]">{{ $product->name }}</h3>
            <div class="flex items-baseline gap-2 mt-2.5">
                <span class="text-base font-bold text-espresso-700">₹{{ number_format($product->selling_price) }}</span>
                @if($product->discount_percent > 0)
                <span class="text-xs text-espresso-300 line-through">₹{{ number_format($product->mrp) }}</span>
                @endif
            </div>
        </div>
    </a>
</div>
