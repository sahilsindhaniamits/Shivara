@extends('layouts.app')
@section('title', $product->meta_title ?? $product->name . ' - Shivara')
@section('meta_description', $product->meta_description ?? $product->short_description)

@section('content')
@php
    $avgRating = 0;
    $reviewCount = 0;
    $approvedReviews = $product->reviews()->where('is_approved', true)->with('user')->latest()->get();
    $totalReviews = $approvedReviews->count();
    if($totalReviews > 0) { $avgRating = $approvedReviews->avg('rating'); $reviewCount = $totalReviews; }
    $ratingCounts = [5=>0,4=>0,3=>0,2=>0,1=>0];
    foreach($approvedReviews as $r) { if(isset($ratingCounts[$r->rating])) $ratingCounts[$r->rating]++; }
    $reviewImages = $approvedReviews->pluck('images')->filter()->flatten()->take(12)->values();
    $productTestimonials = \App\Models\VideoTestimonial::active()->where('product_id', $product->id)->orderBy('sort_order')->get();
@endphp

<div x-data="{ qty: 1, selectedPack: -1, lightbox: false, lbImg: 0, activeTab: 'description' }" @open-lightbox.window="lbImg = $event.detail.idx; lightbox = true">


<!-- Main Product Section -->
<section style="background: linear-gradient(180deg, #FFFDF8 0%, #FFF9ED 100%);">
    <div class="w-full px-4 sm:px-6 lg:px-10 xl:px-16 pt-6 pb-16">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs text-espresso-400 mb-6">
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

        <div class="flex flex-col lg:flex-row gap-4 lg:gap-6">
            <!-- IMAGE AREA -->
            <div class="lg:w-[60%]">
                @if($product->images->count() > 1)
                <!-- MOBILE: Swipe slider + auto-slide + arrows + tap to lightbox -->
                <div class="lg:hidden relative" id="prodSlider">
                    <div class="overflow-hidden rounded-xl relative">
                        <div id="prodSliderTrack" class="flex" style="transition:transform 0.3s ease">
                            @foreach($product->images as $i => $image)
                            @php $imgSrc = str_starts_with($image->url, '/storage/') ? '/public' . $image->url : $image->url; @endphp
                            <div class="w-full shrink-0 prod-slide-item" data-idx="{{ $i }}">
                                <img src="{{ $imgSrc }}" alt="{{ $product->name }}" class="w-full object-cover" style="aspect-ratio:4/5;" loading="{{ $i === 0 ? 'eager' : 'lazy' }}">
                            </div>
                            @endforeach
                        </div>
                        @if($product->discount_percent > 0)
                        <span class="absolute top-3 left-3 px-3 py-1.5 text-white text-[10px] font-bold rounded-full shadow-lg z-10" style="background-color:#c06d22;">{{ $product->discount_percent }}% OFF</span>
                        @endif
                        <!-- Arrow buttons -->
                        <button id="prodSliderPrev" class="absolute left-2 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full flex items-center justify-center z-10 shadow-md" style="background:rgba(255,255,255,0.9);display:none;"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg></button>
                        <button id="prodSliderNext" class="absolute right-2 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full flex items-center justify-center z-10 shadow-md" style="background:rgba(255,255,255,0.9);"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg></button>
                    </div>
                    <div id="prodSliderDots" class="flex justify-center gap-1.5 mt-3"></div>
                </div>
                <script>
                document.addEventListener('DOMContentLoaded', function(){
                    var track = document.getElementById('prodSliderTrack');
                    var dotsC = document.getElementById('prodSliderDots');
                    var prevBtn = document.getElementById('prodSliderPrev');
                    var nextBtn = document.getElementById('prodSliderNext');
                    if(!track) return;
                    var total = {{ $product->images->count() }};
                    var cur = 0, startX = 0, curX = 0, dragging = false, hasMoved = false, autoTimer;

                    // Build dots
                    for(var d=0;d<total;d++){
                        var dot = document.createElement('button');
                        dot.dataset.i = d;
                        dot.className = 'h-2 rounded-full transition-all duration-300 ' + (d===0?'w-6':'w-2');
                        dot.style.backgroundColor = d===0?'#2C2418':'#d1d5db';
                        dotsC.appendChild(dot);
                    }
                    dotsC.addEventListener('click',function(e){var b=e.target.closest('[data-i]');if(b)slideTo(parseInt(b.dataset.i));});

                    function slideTo(i){
                        cur = Math.max(0, Math.min(total-1, i));
                        track.style.transition = 'transform 0.3s ease';
                        track.style.transform = 'translateX(-'+(cur*100)+'%)';
                        updateUI();
                        resetAuto();
                    }
                    function updateUI(){
                        dotsC.querySelectorAll('button').forEach(function(b,idx){
                            b.className='h-2 rounded-full transition-all duration-300 '+(idx===cur?'w-6':'w-2');
                            b.style.backgroundColor=idx===cur?'#2C2418':'#d1d5db';
                        });
                        prevBtn.style.display = cur>0?'flex':'none';
                        nextBtn.style.display = cur<total-1?'flex':'none';
                    }

                    // Arrows
                    prevBtn.addEventListener('click',function(e){e.stopPropagation();slideTo(cur-1);});
                    nextBtn.addEventListener('click',function(e){e.stopPropagation();slideTo(cur+1);});

                    // Touch swipe
                    track.addEventListener('touchstart',function(e){
                        startX=e.touches[0].clientX; curX=startX; dragging=true; hasMoved=false;
                        track.style.transition='none'; clearInterval(autoTimer);
                    },{passive:true});
                    track.addEventListener('touchmove',function(e){
                        if(!dragging)return;
                        curX=e.touches[0].clientX;
                        if(Math.abs(curX-startX)>5) hasMoved=true;
                        var pct=-(cur*100)+((curX-startX)/track.offsetWidth)*100;
                        track.style.transform='translateX('+pct+'%)';
                    },{passive:true});
                    track.addEventListener('touchend',function(){
                        if(!dragging)return; dragging=false;
                        var diff=curX-startX;
                        if(diff<-25)slideTo(cur+1); else if(diff>25)slideTo(cur-1); else slideTo(cur);
                    });

                    // Tap to open lightbox (only if not swiped)
                    track.addEventListener('click',function(e){
                        if(hasMoved) return;
                        var slide=e.target.closest('.prod-slide-item');
                        if(slide){
                            var idx=parseInt(slide.dataset.idx);
                            // Dispatch to Alpine lightbox
                            var main=document.querySelector('[x-data]');
                            if(main && main.__x){main.__x.$data.lbImg=idx;main.__x.$data.lightbox=true;}
                            else{window.dispatchEvent(new CustomEvent('open-lightbox',{detail:{idx:idx}}));}
                        }
                    });

                    // Auto-slide every 4s
                    function resetAuto(){clearInterval(autoTimer);autoTimer=setInterval(function(){slideTo(cur>=total-1?0:cur+1);},4000);}
                    resetAuto(); updateUI();
                });
                </script>

                <!-- DESKTOP: 2-column — left sticky, right scrolls (bluorng style) -->
                <div class="hidden lg:flex gap-3 items-start">
                    <!-- Left: First image — STICKY -->
                    <div class="w-1/2 sticky top-[115px] self-start">
                        @php $firstImg = $product->images->first(); $firstSrc = str_starts_with($firstImg->url, '/storage/') ? '/public' . $firstImg->url : $firstImg->url; @endphp
                        <div class="relative rounded-xl overflow-hidden cursor-pointer" style="background-color:#f8f5f0;" @click="lbImg = 0; lightbox = true">
                            <img src="{{ $firstSrc }}" alt="{{ $product->name }}" class="w-full rounded-xl object-cover" style="aspect-ratio: 3/4;" loading="eager">
                            @if($product->discount_percent > 0)
                            <span class="absolute top-3 left-3 px-3 py-1.5 text-white text-[10px] font-bold rounded-full shadow-lg" style="background-color:#c06d22;">{{ $product->discount_percent }}% OFF</span>
                            @endif
                        </div>
                    </div>
                    <!-- Right: Remaining images — SCROLL naturally -->
                    <div class="w-1/2 space-y-3">
                        @foreach($product->images->slice(1) as $i => $image)
                        @php $imgSrc = str_starts_with($image->url, '/storage/') ? '/public' . $image->url : $image->url; @endphp
                        <div class="relative rounded-xl overflow-hidden cursor-pointer" style="background-color:#f8f5f0;" @click="lbImg = {{ $i + 1 }}; lightbox = true">
                            <img src="{{ $imgSrc }}" alt="{{ $product->name }}" class="w-full rounded-xl object-cover" style="aspect-ratio: 3/4;" loading="lazy">
                        </div>
                        @endforeach
                    </div>
                </div>
                @elseif($product->images->count() == 1)
                @php $onlyImg = $product->images->first(); $onlySrc = str_starts_with($onlyImg->url, '/storage/') ? '/public' . $onlyImg->url : $onlyImg->url; @endphp
                <div class="relative rounded-xl overflow-hidden cursor-pointer" style="background-color:#f8f5f0;" @click="lbImg = 0; lightbox = true">
                    <img src="{{ $onlySrc }}" alt="{{ $product->name }}" class="w-full object-cover" style="aspect-ratio: 3/4;" loading="eager">
                    @if($product->discount_percent > 0)
                    <span class="absolute top-3 left-3 px-3 py-1.5 text-white text-[10px] font-bold rounded-full shadow-lg" style="background-color:#c06d22;">{{ $product->discount_percent }}% OFF</span>
                    @endif
                </div>
                @else
                <div class="rounded-xl overflow-hidden" style="background-color:#f8f5f0;">
                    <img src="https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=800&h=1000&fit=crop" alt="{{ $product->name }}" class="w-full object-cover" style="aspect-ratio: 3/4;">
                </div>
                @endif
            </div>


            <!-- RIGHT: Sticky Product Info (stays fixed while left images scroll) -->
            <div class="lg:w-[40%]">
                <div class="lg:sticky lg:top-[115px] space-y-5">
                <!-- Category Badge -->
                @if($product->category)
                <div><span class="inline-block px-3 py-1 text-[10px] font-bold uppercase tracking-[0.2em] rounded-full border" style="color:#B7925C; border-color:#B7925C;">{{ $product->category->name }}</span></div>
                @endif

                <!-- Product Name -->
                <h1 class="font-display text-2xl md:text-[2.2rem] font-bold leading-tight" style="color:#2C2418;">{{ $product->name }}</h1>

                <!-- Rating -->
                <div class="flex items-center gap-3 flex-wrap">
                    <div class="flex items-center gap-0.5">
                        @for($s = 1; $s <= 5; $s++)
                        <svg class="w-4 h-4" style="{{ $s <= round($avgRating) ? 'color:#f59e0b; fill:#f59e0b;' : 'color:#e5e7eb; fill:#e5e7eb;' }}" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        @endfor
                    </div>
                    <span class="text-sm font-bold" style="color:#2C2418;">{{ number_format($avgRating, 1) }}</span>
                    <span class="text-xs" style="color:#6b7280;">({{ $reviewCount }} reviews)</span>
                    <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded-full" style="{{ $product->in_stock ? 'background-color:#dcfce7; color:#166534;' : 'background-color:#fef2f2; color:#dc2626;' }}">{{ $product->in_stock ? '✓ In Stock' : '✗ Sold Out' }}</span>
                </div>

                <!-- Short Description -->
                @if($product->short_description)
                <p class="text-sm leading-relaxed" style="color:#6b5442;">{{ $product->short_description }}</p>
                @endif

                <!-- Price -->
                <div class="rounded-xl p-4" style="background-color: rgba(183,146,92,0.06); border: 1px solid rgba(183,146,92,0.2);">
                    <div class="flex items-baseline gap-3 flex-wrap">
                        <span class="text-3xl font-bold" style="color:#2C2418;">₹{{ number_format($product->selling_price) }}</span>
                        @if($product->discount_percent > 0)
                        <span class="text-base line-through" style="color:#999;">₹{{ number_format($product->mrp) }}</span>
                        <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-full" style="background-color:#dcfce7; color:#166534;">Save ₹{{ number_format($product->mrp - $product->selling_price) }}</span>
                        @endif
                    </div>
                    <p class="text-[10px] mt-1.5" style="color:#8c7560;">Inclusive of all taxes • Free shipping above ₹{{ config('shivara.free_shipping_threshold', 999) }}</p>
                </div>


                <!-- Pack/Variant Selector -->
                @if($product->variants->count())
                <div>
                    <p class="text-sm font-bold mb-2" style="color:#2C2418;">Choose Your Pack</p>
                    <div class="flex gap-2.5 overflow-x-auto scrollbar-hide pb-2">
                        @foreach($product->variants as $i => $variant)
                        <button type="button" @click="selectedPack = {{ $i }}" class="shrink-0 w-36 rounded-xl overflow-hidden text-center transition-all cursor-pointer border" :style="selectedPack === {{ $i }} ? 'border-color:#B7925C; box-shadow: 0 0 0 2px rgba(183,146,92,0.2)' : 'border-color:#e5e7eb'">
                            @if($variant->mrp > $variant->selling_price)
                            <div class="relative"><span class="absolute -top-0 left-1/2 -translate-x-1/2 text-[8px] font-bold text-white px-2 py-0.5 rounded-b-md z-10" style="background-color:#16a34a;">Save ₹{{ number_format($variant->mrp - $variant->selling_price) }}</span></div>
                            @endif
                            <div class="p-3 pt-5" style="background-color:#FFFDF8;">
                                <p class="text-xl font-bold" style="color:#2C2418;">₹{{ number_format($variant->selling_price) }}</p>
                                <p class="text-[10px] line-through" style="color:#999;">₹{{ number_format($variant->mrp) }}</p>
                            </div>
                            <div class="p-2 text-center text-white" style="background-color:#1a1a1a;">
                                <p class="text-[10px] font-bold tracking-wide">{{ $variant->name }}</p>
                                @if($variant->weight_display)
                                <p class="text-[9px] mt-0.5" style="color:#aaa;">{{ $variant->weight_display }}</p>
                                @endif
                            </div>
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Quantity + Add to Cart -->
                @if($product->in_stock)
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

                    <div class="flex items-center gap-4 mb-4">
                        <span class="text-sm font-semibold" style="color:#2C2418;">Qty</span>
                        <div class="flex items-center rounded-full border" style="border-color:#e5e7eb;">
                            <button type="button" @click="qty = Math.max(1, qty - 1)" class="w-9 h-9 flex items-center justify-center text-lg font-bold hover:bg-gray-50 rounded-l-full transition">−</button>
                            <span class="w-8 h-9 flex items-center justify-center text-sm font-bold" x-text="qty"></span>
                            <button type="button" @click="qty = Math.min({{ $product->stock ?? 999 }}, qty + 1)" class="w-9 h-9 flex items-center justify-center text-lg font-bold hover:bg-gray-50 rounded-r-full transition">+</button>
                        </div>
                    </div>

                    <div class="space-y-2.5">
                        <button type="submit" :disabled="adding" class="w-full py-3.5 text-white font-bold text-sm uppercase tracking-wider rounded-full hover:shadow-2xl transition-all flex items-center justify-center gap-2 disabled:opacity-60" style="background-color:#2C2418;">
                            <svg x-show="!adding" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            <span x-text="adding ? 'Adding...' : 'Add to Cart'"></span>
                        </button>
                        <button type="submit" class="w-full py-3.5 font-bold text-sm uppercase tracking-wider rounded-full hover:shadow-xl transition-all flex items-center justify-center gap-2 border-2" style="color:#B7925C; border-color:#B7925C;">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            Buy Now
                        </button>
                    </div>
                </form>
                @else
                <div class="w-full py-3.5 bg-gray-100 text-gray-500 font-bold text-sm uppercase tracking-wider rounded-full text-center">Currently Unavailable</div>
                @endif


                <!-- Tabs: Description, How to Use, Benefits, Ingredients, Shipping -->
                @php
                    $tabs = [];
                    if($product->description) $tabs['description'] = ['label' => 'Description', 'content' => $product->description];
                    if($product->how_to_use) $tabs['how_to_use'] = ['label' => 'How to Use', 'content' => $product->how_to_use];
                    if($product->benefits) $tabs['benefits'] = ['label' => 'Key Benefits', 'content' => $product->benefits];
                    if($product->ingredients) $tabs['ingredients'] = ['label' => 'Ingredients', 'content' => $product->ingredients];
                    $firstTab = array_key_first($tabs);
                @endphp
                @if(count($tabs))
                <div class="pt-3 border-t" style="border-color: rgba(183,146,92,0.15);" x-init="activeTab = '{{ $firstTab }}'">
                    <!-- Tab Headers -->
                    <div class="flex gap-1 overflow-x-auto scrollbar-hide border-b" style="border-color: rgba(183,146,92,0.15);">
                        @foreach($tabs as $key => $tab)
                        <button type="button" @click="activeTab = '{{ $key }}'" :class="activeTab === '{{ $key }}' ? 'border-b-2 font-bold' : 'text-gray-400 hover:text-gray-600'" :style="activeTab === '{{ $key }}' ? 'color:#2C2418; border-color:#B7925C' : ''" class="px-3 py-2 text-xs uppercase tracking-wide whitespace-nowrap transition shrink-0">{{ $tab['label'] }}</button>
                        @endforeach
                    </div>
                    <!-- Tab Content -->
                    <div class="py-3">
                        @foreach($tabs as $key => $tab)
                        <div x-show="activeTab === '{{ $key }}'" x-cloak x-data="{ expanded: false }">
                            <div class="text-[15px] leading-relaxed" style="color:#6b5442;" :class="!expanded ? 'line-clamp-4' : ''">
                                {!! nl2br(e($tab['content'])) !!}
                            </div>
                            <button type="button" @click="expanded = !expanded" class="mt-2 text-xs font-bold transition" style="color:#B7925C;" x-text="expanded ? '← Show Less' : 'Read More →'"></button>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Trust Points -->
                <div class="grid grid-cols-4 gap-3 pt-1">
                    <div class="text-center">
                        <div class="w-10 h-10 mx-auto mb-1.5 rounded-full flex items-center justify-center" style="background-color: rgba(183,146,92,0.1); border: 1px solid rgba(183,146,92,0.25);"><svg class="w-[18px] h-[18px]" style="color:#B7925C;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg></div>
                        <p class="text-[10px] font-bold uppercase leading-tight" style="color:#2C2418;">100% Natural</p>
                    </div>
                    <div class="text-center">
                        <div class="w-10 h-10 mx-auto mb-1.5 rounded-full flex items-center justify-center" style="background-color: rgba(183,146,92,0.1); border: 1px solid rgba(183,146,92,0.25);"><svg class="w-[18px] h-[18px]" style="color:#B7925C;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg></div>
                        <p class="text-[10px] font-bold uppercase leading-tight" style="color:#2C2418;">GMP Certified</p>
                    </div>
                    <div class="text-center">
                        <div class="w-10 h-10 mx-auto mb-1.5 rounded-full flex items-center justify-center" style="background-color: rgba(183,146,92,0.1); border: 1px solid rgba(183,146,92,0.25);"><svg class="w-[18px] h-[18px]" style="color:#B7925C;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg></div>
                        <p class="text-[10px] font-bold uppercase leading-tight" style="color:#2C2418;">Free Shipping</p>
                    </div>
                    <div class="text-center">
                        <div class="w-10 h-10 mx-auto mb-1.5 rounded-full flex items-center justify-center" style="background-color: rgba(183,146,92,0.1); border: 1px solid rgba(183,146,92,0.25);"><svg class="w-[18px] h-[18px]" style="color:#B7925C;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg></div>
                        <p class="text-[10px] font-bold uppercase leading-tight" style="color:#2C2418;">{{ $product->return_policy ?: 'Easy Returns' }}</p>
                    </div>
                </div>

            </div><!-- end sticky inner -->
            </div><!-- end right column -->
        </div><!-- end flex -->
    </div>
</section>


<!-- Scroll trigger marker for sticky bottom bar -->
<div id="stickyBarTrigger"></div>

<!-- SECTION: Product Banners -->
@if($product->banners && count($product->banners))
<section x-data="{ pb: 0 }" x-init="setInterval(() => pb = (pb + 1) % {{ count($product->banners) }}, 5000)">
    <div class="relative overflow-hidden" style="aspect-ratio: 2/1;">
        @foreach($product->banners as $i => $bannerUrl)
        <div x-show="pb === {{ $i }}" x-transition.opacity class="absolute inset-0"><img src="{{ str_starts_with($bannerUrl, '/storage/') ? '/public' . $bannerUrl : $bannerUrl }}" alt="Banner" class="w-full h-full object-cover"></div>
        @endforeach
    </div>
    @if(count($product->banners) > 1)
    <div class="flex justify-center gap-2 mt-4">@foreach($product->banners as $i => $b)<button @click="pb={{ $i }}" :class="pb==={{ $i }}?'w-8':'w-3'" class="h-2.5 rounded-full transition-all" :style="pb==={{ $i }}?'background-color:#B7925C':'background-color:#ddd'"></button>@endforeach</div>
    @endif
</section>
@endif

<!-- SECTION: Related Products (You May Also Like) -->
@if($relatedProducts->count())
<section class="py-14" style="background-color:#FFFDF8;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-8">
            <span class="text-[11px] font-bold uppercase tracking-[0.3em]" style="color:#B7925C;">Complete Your Routine</span>
            <h2 class="font-display text-2xl md:text-3xl font-bold mt-2" style="color:#2C2418;">You May Also Like</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-5">
            @foreach($relatedProducts as $rp)
                @include('partials.product-card', ['product' => $rp])
            @endforeach
        </div>
    </div>
</section>
@endif


<!-- SECTION: The Shivara Promise -->
<section class="py-8 sm:py-10" style="background-color:#2C2418;">
    <div class="max-w-5xl mx-auto px-4 sm:px-6">
        <h3 class="font-display text-xl sm:text-2xl font-bold text-center mb-5" style="color:#FFFDF8;">The Shivara Promise</h3>
        <div class="grid grid-cols-4 gap-3">
            <div class="text-center">
                <div class="w-12 h-12 mx-auto mb-2 rounded-full flex items-center justify-center" style="background-color: rgba(183,146,92,0.15); border: 1px solid rgba(183,146,92,0.3);"><svg class="w-5 h-5" style="color:#B7925C;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg></div>
                <h4 class="text-xs font-bold" style="color:#FFFDF8;">100% Natural</h4>
                <p class="text-[10px] mt-0.5" style="color: rgba(255,253,248,0.5);">No chemicals or preservatives</p>
            </div>
            <div class="text-center">
                <div class="w-12 h-12 mx-auto mb-2 rounded-full flex items-center justify-center" style="background-color: rgba(183,146,92,0.15); border: 1px solid rgba(183,146,92,0.3);"><svg class="w-5 h-5" style="color:#B7925C;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg></div>
                <h4 class="text-xs font-bold" style="color:#FFFDF8;">GMP Certified</h4>
                <p class="text-[10px] mt-0.5" style="color: rgba(255,253,248,0.5);">Audited facilities</p>
            </div>
            <div class="text-center">
                <div class="w-12 h-12 mx-auto mb-2 rounded-full flex items-center justify-center" style="background-color: rgba(183,146,92,0.15); border: 1px solid rgba(183,146,92,0.3);"><svg class="w-5 h-5" style="color:#B7925C;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg></div>
                <h4 class="text-xs font-bold" style="color:#FFFDF8;">Lab Tested</h4>
                <p class="text-[10px] mt-0.5" style="color: rgba(255,253,248,0.5);">Purity verified by experts</p>
            </div>
            <div class="text-center">
                <div class="w-12 h-12 mx-auto mb-2 rounded-full flex items-center justify-center" style="background-color: rgba(183,146,92,0.15); border: 1px solid rgba(183,146,92,0.3);"><svg class="w-5 h-5" style="color:#B7925C;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg></div>
                <h4 class="text-xs font-bold" style="color:#FFFDF8;">5000+ Happy Customers</h4>
                <p class="text-[10px] mt-0.5" style="color: rgba(255,253,248,0.5);">Trusted across India</p>
            </div>
        </div>
    </div>
</section>


<!-- SECTION: Customer Reviews (60/40 layout — image left, content right) -->
<section class="py-14" style="background-color:#FFFDF8;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <!-- Header: 60/40 — Rating summary image left, breakdown right -->
        <div class="grid md:grid-cols-5 gap-8 mb-10 p-6 rounded-2xl" style="background-color:#fff; border: 1px solid rgba(183,146,92,0.15);">
            <div class="md:col-span-3 flex flex-col items-center justify-center text-center">
                <span class="text-[11px] font-bold uppercase tracking-[0.3em] mb-2" style="color:#B7925C;">Customer Love</span>
                <p class="text-5xl font-bold" style="color:#2C2418;">{{ number_format($avgRating, 1) }}</p>
                <div class="flex items-center gap-0.5 mt-2 justify-center">
                    @for($s = 1; $s <= 5; $s++)
                    <svg class="w-5 h-5 {{ $s <= round($avgRating) ? 'fill-amber-400 text-amber-400' : 'fill-gray-200 text-gray-200' }}" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    @endfor
                </div>
                <p class="text-xs mt-2" style="color:#8c7560;">Based on {{ $totalReviews }} reviews</p>
            </div>
            <div class="md:col-span-2 space-y-1.5">
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
        <div class="mb-8">
            <h4 class="text-sm font-bold mb-3" style="color:#2C2418;">Customer Photos</h4>
            <div class="flex gap-3 overflow-x-auto scrollbar-hide pb-2">
                @foreach($reviewImages as $rImg)
                <div class="shrink-0 w-20 h-20 rounded-xl overflow-hidden border cursor-pointer hover:opacity-80 transition" style="border-color:#e5e7eb;">
                    <img src="{{ str_starts_with($rImg, '/storage/') ? '/public' . $rImg : $rImg }}" alt="Customer photo" class="w-full h-full object-cover" loading="lazy">
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Write Review -->
        @auth
        <div class="mb-8 p-5 rounded-2xl" style="background-color:#fff; border: 1px solid rgba(183,146,92,0.15);">
            <h4 class="text-sm font-bold mb-3" style="color:#2C2418;">Share Your Experience</h4>
            <form method="POST" action="{{ route('products.review', $product->slug) }}" enctype="multipart/form-data" class="space-y-3" x-data="{ rating: 0 }">
                @csrf
                <div class="flex items-center gap-1">
                    @for($s = 1; $s <= 5; $s++)
                    <button type="button" @click="rating = {{ $s }}" :class="{{ $s }} <= rating ? 'text-amber-400' : 'text-gray-300'" class="transition hover:scale-110">
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    </button>
                    @endfor
                </div>
                <input type="hidden" name="rating" x-bind:value="rating">
                <textarea name="comment" rows="3" placeholder="Tell others about your experience..." class="w-full px-4 py-3 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-gold-200" style="border-color:#e5e7eb;" required></textarea>
                <input type="file" name="review_images[]" multiple accept="image/*" class="text-xs">
                <button type="submit" class="px-5 py-2 text-white text-xs font-bold uppercase rounded-full hover:opacity-90 transition" style="background-color:#2C2418;">Submit Review</button>
            </form>
        </div>
        @else
        <div class="mb-8 p-4 rounded-xl text-center" style="background-color:#fff; border: 1px solid rgba(183,146,92,0.15);">
            <p class="text-sm" style="color:#6b5442;"><a href="{{ route('login') }}" class="font-bold hover:underline" style="color:#B7925C;">Log in</a> to write a review</p>
        </div>
        @endauth

        <!-- Reviews List -->
        @if($approvedReviews->count())
        <div class="grid md:grid-cols-2 gap-4">
            @foreach($approvedReviews->take(6) as $review)
            <div class="p-5 rounded-2xl border" style="border-color: rgba(183,146,92,0.1); background-color:#fff;">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-[10px] font-bold text-white" style="background-color:#2C2418;">{{ substr($review->user->name ?? 'C', 0, 1) }}</div>
                        <div>
                            <p class="text-sm font-bold" style="color:#2C2418;">{{ $review->user->name ?? 'Customer' }}</p>
                            <div class="flex items-center gap-0.5">@for($s = 1; $s <= 5; $s++)<svg class="w-3 h-3 {{ $s <= $review->rating ? 'fill-amber-400 text-amber-400' : 'fill-gray-200 text-gray-200' }}" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>@endfor</div>
                        </div>
                    </div>
                    <span class="text-[10px]" style="color:#8c7560;">{{ $review->created_at->diffForHumans() }}</span>
                </div>
                <p class="text-sm leading-relaxed" style="color:#6b5442;">{{ $review->comment }}</p>
                @if($review->images && count($review->images))
                <div class="flex gap-2 mt-3">@foreach(array_slice($review->images, 0, 3) as $revImg)<div class="w-12 h-12 rounded-lg overflow-hidden border" style="border-color:#e5e7eb;"><img src="{{ str_starts_with($revImg, '/storage/') ? '/public' . $revImg : $revImg }}" class="w-full h-full object-cover" loading="lazy"></div>@endforeach</div>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <p class="text-sm text-center py-8" style="color:#8c7560;">No reviews yet. Be the first to share your experience!</p>
        @endif
    </div>
</section>


<!-- Image Lightbox with Zoom -->
<div x-show="lightbox" x-cloak x-transition.opacity.duration.300ms class="fixed inset-0 z-[200] flex items-center justify-center" style="background-color: rgba(0,0,0,0.95);" @keydown.escape.window="lightbox = false" @keydown.arrow-left.window="if(lightbox) lbImg = (lbImg - 1 + {{ $product->images->count() }}) % {{ $product->images->count() }}" @keydown.arrow-right.window="if(lightbox) lbImg = (lbImg + 1) % {{ $product->images->count() }}" x-data="{ zoomed: false, scale: 1, posX: 0, posY: 0 }">
    <button @click="lightbox = false" class="absolute top-4 right-4 w-11 h-11 rounded-full flex items-center justify-center text-white z-10 hover:bg-white/20 transition" style="background-color: rgba(255,255,255,0.1);">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <!-- Zoom toggle -->
    <button @click="zoomed = !zoomed; scale = zoomed ? 2.5 : 1; posX = 0; posY = 0;" class="absolute top-4 left-4 w-11 h-11 rounded-full flex items-center justify-center text-white z-10 hover:bg-white/20 transition" style="background-color: rgba(255,255,255,0.1);">
        <svg x-show="!zoomed" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
        <svg x-show="zoomed" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7"/></svg>
    </button>
    <!-- Counter -->
    <div class="absolute top-4 left-1/2 -translate-x-1/2 text-white/60 text-xs font-medium z-10" x-text="(lbImg + 1) + ' / {{ $product->images->count() }}'"></div>
    @if($product->images->count() > 1)
    <button @click="lbImg = (lbImg - 1 + {{ $product->images->count() }}) % {{ $product->images->count() }}; zoomed = false; scale = 1;" class="absolute left-4 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full flex items-center justify-center text-white z-10 hover:bg-white/20 transition" style="background-color: rgba(255,255,255,0.1);"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></button>
    <button @click="lbImg = (lbImg + 1) % {{ $product->images->count() }}; zoomed = false; scale = 1;" class="absolute right-4 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full flex items-center justify-center text-white z-10 hover:bg-white/20 transition" style="background-color: rgba(255,255,255,0.1);"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></button>
    @endif
    <div class="max-w-4xl w-full px-4 overflow-hidden" @click.self="if(!zoomed) lightbox = false">
        @foreach($product->images as $i => $lbImgItem)
        @php $lbSrc = str_starts_with($lbImgItem->url, '/storage/') ? '/public' . $lbImgItem->url : $lbImgItem->url; @endphp
        <div x-show="lbImg === {{ $i }}" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="flex items-center justify-center" @dblclick="zoomed = !zoomed; scale = zoomed ? 2.5 : 1; posX = 0; posY = 0;">
            <img src="{{ $lbSrc }}" alt="{{ $product->name }}" class="max-h-[85vh] max-w-full object-contain rounded-xl mx-auto cursor-zoom-in transition-transform duration-300" :class="zoomed ? 'cursor-zoom-out' : 'cursor-zoom-in'" :style="'transform: scale(' + scale + ') translate(' + posX + 'px, ' + posY + 'px)'" @mousemove="if(zoomed) { let rect = $el.getBoundingClientRect(); posX = ((event.clientX - rect.left) / rect.width - 0.5) * -80; posY = ((event.clientY - rect.top) / rect.height - 0.5) * -80; }" @mouseleave="posX = 0; posY = 0">
        </div>
        @endforeach
    </div>
    <!-- Thumbnail strip at bottom -->
    @if($product->images->count() > 1)
    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-10">
        @foreach($product->images as $i => $thumb)
        @php $thumbSrc = str_starts_with($thumb->url, '/storage/') ? '/public' . $thumb->url : $thumb->url; @endphp
        <button @click="lbImg = {{ $i }}; zoomed = false; scale = 1;" class="w-12 h-12 rounded-lg overflow-hidden border-2 transition-all" :class="lbImg === {{ $i }} ? 'border-white opacity-100 scale-110' : 'border-transparent opacity-50 hover:opacity-80'">
            <img src="{{ $thumbSrc }}" class="w-full h-full object-cover">
        </button>
        @endforeach
    </div>
    @endif
</div>

<!-- Bottom Sticky Add to Cart Bar (appears when scrolled past product section) -->
@if($product->in_stock)
<div x-data="{ showBar: false }" x-init="
    let trigger = document.getElementById('stickyBarTrigger');
    if(trigger) {
        let observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                showBar = !entry.isIntersecting && entry.boundingClientRect.top < 0;
                var wa = document.getElementById('whatsappFloat');
                if(wa) wa.style.bottom = showBar ? '80px' : '24px';
            });
        }, { threshold: 0 });
        observer.observe(trigger);
    }
" x-show="showBar" x-cloak
   x-transition:enter="transition ease-out duration-300 transform"
   x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
   x-transition:leave="transition ease-in duration-200 transform"
   x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full"
   class="fixed bottom-0 left-0 right-0 z-50 border-t shadow-2xl" style="background-color:#FFFDF8; border-color: rgba(183,146,92,0.2);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 flex items-center justify-between gap-3">
        <div class="flex items-center gap-3 min-w-0">
            @if($product->images->count())
            <img src="{{ str_starts_with($product->images->first()->url, '/storage/') ? '/public' . $product->images->first()->url : $product->images->first()->url }}" class="w-11 h-11 rounded-lg object-cover shrink-0 border" style="border-color:#e5e7eb;" alt="">
            @endif
            <div class="min-w-0">
                <p class="text-sm font-bold truncate" style="color:#2C2418;">{{ $product->name }}</p>
                <div class="flex items-center gap-1.5">
                    <span class="text-sm font-bold" style="color:#2C2418;">₹{{ number_format($product->selling_price) }}</span>
                    @if($product->discount_percent > 0)
                    <span class="text-[10px] px-1.5 py-0.5 font-bold rounded" style="background-color:#2C2418; color:#fff;">-{{ $product->discount_percent }}%</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <div class="flex items-center border rounded-lg" style="border-color:#e5e7eb;">
                <button type="button" @click="qty = Math.max(1, qty - 1)" class="w-8 h-8 flex items-center justify-center text-sm font-bold">−</button>
                <span class="w-7 h-8 flex items-center justify-center text-xs font-bold" x-text="qty"></span>
                <button type="button" @click="qty = Math.min({{ $product->stock ?? 999 }}, qty + 1)" class="w-8 h-8 flex items-center justify-center text-sm font-bold">+</button>
            </div>
            <form method="POST" action="{{ route('cart.add') }}" @submit.prevent="
                let fd = new FormData($el);
                fetch('{{ route('cart.add') }}', { method:'POST', headers:{'X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content,'Accept':'application/json'}, body:fd })
                .then(r=>r.json()).then(d=>{ if(d.success){window.dispatchEvent(new CustomEvent('cart-updated'));window.dispatchEvent(new CustomEvent('open-cart'))} }).catch(()=>{$el.submit()});">
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" x-bind:value="qty">
                @if($product->variants->count())
                <input type="hidden" name="variant_id" x-bind:value="selectedPack >= 0 ? [{{ $product->variants->pluck('id')->implode(',') }}][selectedPack] : ''">
                @endif
                <button type="submit" class="w-10 h-10 flex items-center justify-center rounded-lg text-white" style="background-color:#2C2418;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </button>
            </form>
        </div>
    </div>
</div>
@endif

</div>{{-- end main x-data --}}

{{-- Video Testimonial Modal --}}
@if($productTestimonials->count())
<div x-data="{ open: false, videoUrl: '', product: null, muted: false }"
     x-show="open" x-cloak style="display:none;"
     class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
     @keydown.escape.window="open = false; videoUrl = ''; document.body.style.overflow = '';"
     x-init="window.addEventListener('open-video-modal', (e) => { videoUrl = e.detail.url; product = e.detail.product; muted = false; open = true; document.body.style.overflow = 'hidden'; })">
    <div class="absolute inset-0" style="background-color: rgba(0,0,0,0.8);" @click="open = false; videoUrl = ''; document.body.style.overflow = '';"></div>
    <div class="relative w-full max-w-[360px] mx-auto" @click.stop>
        <button @click="open = false; videoUrl = ''; document.body.style.overflow = '';" class="absolute top-3 right-3 z-30 w-9 h-9 rounded-full flex items-center justify-center" style="background-color: rgba(255,255,255,0.9);"><svg class="w-5 h-5" style="color:#2C2418;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg></button>
        <div class="relative rounded-2xl overflow-hidden shadow-2xl" style="background-color:#000; aspect-ratio: 9/16; max-height: 80vh;">
            <iframe x-ref="pvFrame" class="absolute inset-0 w-full h-full" :src="videoUrl" frameborder="0" allow="autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
    </div>
</div>
@endif

@push('styles')
<style>
.line-clamp-4 { display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden; }
</style>
@endpush
@endsection
