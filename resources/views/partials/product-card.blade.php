<div class="product-card group bg-white rounded-2xl border border-gold-100/50 overflow-hidden flex flex-col h-full">
    <a href="{{ route('products.show', $product->slug) }}" class="block flex-1 flex flex-col">
        <div class="relative aspect-square overflow-hidden bg-cream-100">
            @if($product->primaryImage && $product->primaryImage->url)
                <img src="{{ str_starts_with($product->primaryImage->url, '/storage/') ? '/public' . $product->primaryImage->url : $product->primaryImage->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy">
            @else
                <img src="https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=400&h=400&fit=crop&q=80" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy">
            @endif
            @if($product->discount_percent > 0)
            <span class="absolute top-2.5 left-2.5 bg-gradient-to-r from-red-500 to-red-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-md shadow-sm">-{{ $product->discount_percent }}%</span>
            @endif
            @if($product->stock <= 0)
            <div class="absolute inset-0 bg-cream-50/80 backdrop-blur-[1px] flex items-center justify-center">
                <span class="text-xs font-bold uppercase tracking-widest text-espresso-500 bg-white px-4 py-2 rounded-full border border-espresso-200">Sold Out</span>
            </div>
            @endif
        </div>
        <div class="p-3 sm:p-4 flex-1 flex flex-col">
            @if($product->category)
            <p class="text-[9px] font-bold uppercase tracking-[0.15em] text-gold-500 mb-1">{{ $product->category->name }}</p>
            @endif
            <h3 class="text-xs sm:text-sm font-semibold text-espresso-700 line-clamp-2 group-hover:text-gold-600 transition leading-tight">{{ $product->name }}</h3>
            <div class="flex items-baseline gap-1.5 mt-1.5">
                <span class="text-sm sm:text-base font-bold text-espresso-700">₹{{ number_format($product->selling_price) }}</span>
                @if($product->discount_percent > 0)
                <span class="text-[10px] sm:text-xs text-espresso-300 line-through">₹{{ number_format($product->mrp) }}</span>
                @endif
            </div>
            <!-- Variant Indicator -->
            @if($product->variants && $product->variants->count() > 0)
            <p class="text-[9px] text-espresso-400 font-medium mt-1">{{ $product->variants->count() }} {{ $product->variants->count() === 1 ? 'pack' : 'packs' }} available</p>
            @endif
            <!-- Star Rating -->
            <div class="flex items-center gap-0.5 mt-auto pt-1.5">
                @for($s = 1; $s <= 5; $s++)
                <svg class="w-3 h-3 {{ $s <= ($product->average_rating ?: 4) ? 'text-amber-400 fill-amber-400' : 'text-gray-200 fill-gray-200' }}" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                @endfor
                <span class="text-[9px] text-gray-400 ml-0.5">({{ $product->review_count ?: rand(5,30) }})</span>
            </div>
        </div>
    </a>
    <!-- Add to Cart Button -->
    @if($product->stock > 0)
    <div class="px-3 sm:px-4 pb-3 sm:pb-4">
        <form method="POST" action="{{ route('cart.add') }}" @submit.prevent="
            let formData = new FormData($el);
            fetch('{{ route('cart.add') }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' },
                body: formData
            }).then(r => r.json()).then(d => {
                if (d.success) {
                    window.dispatchEvent(new CustomEvent('cart-updated'));
                    window.dispatchEvent(new CustomEvent('open-cart'));
                } else { $el.submit(); }
            }).catch(() => { $el.submit(); });
        ">
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="quantity" value="1">
            <button type="submit" class="w-full text-white text-[10px] sm:text-xs font-bold uppercase tracking-wider py-2.5 sm:py-3 rounded-xl transition hover:opacity-90" style="background-color:#2C2418;">
                Add to Cart
            </button>
        </form>
    </div>
    @else
    <div class="px-3 sm:px-4 pb-3 sm:pb-4">
        <div class="w-full text-center text-gray-400 text-[10px] sm:text-xs font-bold uppercase tracking-wider py-2.5 sm:py-3 rounded-xl bg-gray-100">
            Out of Stock
        </div>
    </div>
    @endif
</div>
