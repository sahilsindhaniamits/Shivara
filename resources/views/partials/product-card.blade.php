<a href="{{ route('products.show', $product->slug) }}">
    <div class="product-card bg-white border border-border overflow-hidden group relative">
        <!-- Image -->
        <div class="relative aspect-[4/5] overflow-hidden bg-accent">
            @if($product->primaryImage && $product->primaryImage->url)
                <img src="{{ $product->primaryImage->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
            @else
                <div class="w-full h-full flex items-center justify-center bg-accent">
                    <span class="text-6xl opacity-30">🌿</span>
                </div>
            @endif

            @if($product->discount_percent > 0)
            <div class="absolute top-3 left-3">
                <span class="bg-[#8B4513] text-white text-[10px] font-medium px-2.5 py-1 rounded-full">-{{ $product->discount_percent }}%</span>
            </div>
            @endif

            @if($product->stock > 0)
            <form method="POST" action="{{ route('cart.add') }}" class="absolute bottom-0 left-0 right-0 bg-secondary/90 backdrop-blur-sm text-white py-3 text-xs uppercase tracking-widest font-medium translate-y-full group-hover:translate-y-0 transition-transform duration-300 text-center">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" onclick="event.stopPropagation()">Add to Cart</button>
            </form>
            @else
            <div class="absolute inset-0 bg-white/60 flex items-center justify-center">
                <span class="text-xs uppercase tracking-widest text-secondary/60 font-medium">Sold Out</span>
            </div>
            @endif
        </div>

        <!-- Content -->
        <div class="p-4 pt-5">
            @if($product->category)
            <p class="text-[10px] font-medium uppercase tracking-[0.2em] text-primary mb-2">{{ $product->category->name }}</p>
            @endif
            <h3 class="font-serif text-base text-secondary line-clamp-1 group-hover:text-primary transition-colors duration-300">{{ $product->name }}</h3>
            <p class="text-xs text-muted mt-1 line-clamp-1">Natural Ayurvedic formulation</p>
            <div class="flex items-center gap-2 mt-3">
                <span class="text-base font-medium text-secondary">₹{{ number_format($product->selling_price) }}</span>
                @if($product->discount_percent > 0)
                <span class="text-sm text-muted line-through">₹{{ number_format($product->mrp) }}</span>
                @endif
            </div>
        </div>
    </div>
</a>
