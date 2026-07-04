<!-- Side Cart Drawer -->
<div x-data="sideCart()" x-on:open-cart.window="open = true" x-cloak>
    <!-- Backdrop -->
    <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         @click="open = false" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-[100]"></div>

    <!-- Cart Panel -->
    <div x-show="open" x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
         class="fixed top-0 right-0 bottom-0 w-full max-w-[400px] bg-white z-[101] flex flex-col shadow-2xl">

        <!-- Header -->
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between bg-white">
            <div class="flex items-center gap-2.5">
                <svg class="w-5 h-5 text-espresso-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <h2 class="text-base font-bold text-espresso-700">Your Cart</h2>
                <span class="text-[10px] bg-espresso-100 text-espresso-600 font-bold px-2 py-0.5 rounded-full" x-text="totalItems + ' items'"></span>
            </div>
            <button @click="open = false" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-400 hover:text-gray-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Unified Progress Bar -->
        <div class="px-5 py-3 bg-gray-50 border-b border-gray-100">

            @if(config('shivara.free_gift_enabled'))
            <!-- Combined progress: Free Shipping (₹999) → Free Gift (₹1499) -->
            <div class="relative">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs font-semibold text-espresso-600" x-text="progressMessage"></p>
                </div>
                <!-- Bar -->
                <div class="h-2.5 bg-gray-200 rounded-full overflow-hidden relative">
                    <div class="h-full rounded-full transition-all duration-700 ease-out"
                         :class="subtotal >= {{ config('shivara.free_gift_threshold') }} ? 'bg-gradient-to-r from-green-400 to-purple-500' : subtotal >= {{ config('shivara.free_shipping_threshold') }} ? 'bg-gradient-to-r from-green-400 to-green-500' : 'bg-gradient-to-r from-gold-400 to-gold-500'"
                         :style="'width:' + Math.min(100, (subtotal / {{ config('shivara.free_gift_threshold') }}) * 100) + '%'">
                    </div>
                    <!-- Milestone markers -->
                    <div class="absolute top-1/2 -translate-y-1/2 w-3 h-3 rounded-full border-2 border-white shadow-sm transition-colors duration-300"
                         :class="subtotal >= {{ config('shivara.free_shipping_threshold') }} ? 'bg-green-500' : 'bg-gray-300'"
                         style="left: {{ (config('shivara.free_shipping_threshold') / config('shivara.free_gift_threshold')) * 100 }}%"></div>
                    <div class="absolute top-1/2 -translate-y-1/2 right-0 w-3 h-3 rounded-full border-2 border-white shadow-sm transition-colors duration-300"
                         :class="subtotal >= {{ config('shivara.free_gift_threshold') }} ? 'bg-purple-500' : 'bg-gray-300'"></div>
                </div>
                <!-- Labels -->
                <div class="flex items-center justify-between mt-1.5">
                    <span class="text-[9px] font-semibold uppercase tracking-wider" :class="subtotal >= {{ config('shivara.free_shipping_threshold') }} ? 'text-green-600' : 'text-gray-400'">🚚 Free Ship</span>
                    <span class="text-[9px] font-semibold uppercase tracking-wider" :class="subtotal >= {{ config('shivara.free_gift_threshold') }} ? 'text-purple-600' : 'text-gray-400'">🎁 Free Gift</span>
                </div>
            </div>
            @else
            <!-- Simple: Free Shipping only -->
            <div>
                <p class="text-xs font-semibold text-espresso-600 mb-2" x-text="subtotal >= {{ config('shivara.free_shipping_threshold') }} ? '🎉 Free delivery unlocked!' : '🚚 Free delivery on orders above ₹{{ config('shivara.free_shipping_threshold') }}'"></p>
                <div class="h-2.5 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-700 ease-out"
                         :class="subtotal >= {{ config('shivara.free_shipping_threshold') }} ? 'bg-green-500' : 'bg-gold-500'"
                         :style="'width:' + Math.min(100, (subtotal / {{ config('shivara.free_shipping_threshold') }}) * 100) + '%'"></div>
                </div>
                <p class="text-[10px] text-gray-400 mt-1 text-right">₹{{ config('shivara.free_shipping_threshold') }}</p>
            </div>
            @endif
        </div>


        <!-- Cart Items -->
        <div class="flex-1 overflow-y-auto">
            <!-- Empty State -->
            <template x-if="items.length === 0">
                <div class="flex flex-col items-center justify-center h-full text-center px-6 py-16">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </div>
                    <p class="text-sm font-semibold text-espresso-700">Cart is empty</p>
                    <p class="text-xs text-gray-400 mt-1">Add items to get started</p>
                    <a href="{{ route('products.index') }}" @click="open = false" class="mt-4 px-5 py-2 bg-espresso-700 text-white text-xs font-bold rounded-lg hover:bg-espresso-600 transition uppercase tracking-wider">Shop Now</a>
                </div>
            </template>

            <!-- Items List -->
            <div class="px-4 py-3 space-y-2">
                <template x-for="item in items" :key="item.id">
                    <div class="flex gap-3 p-3 bg-white rounded-xl border border-gray-100 hover:border-gray-200 transition">
                        <div class="w-14 h-14 bg-gray-50 rounded-lg overflow-hidden shrink-0">
                            <img :src="item.image" :alt="item.name" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <p class="text-xs font-semibold text-espresso-700 line-clamp-2 leading-tight" x-text="item.name"></p>
                                <button @click="removeItem(item.id)" class="text-gray-300 hover:text-red-400 transition shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                            <div class="flex items-center justify-between mt-2">
                                <div class="flex items-center border border-gray-200 rounded-md">
                                    <button @click="updateQty(item.id, item.quantity - 1)" class="w-6 h-6 flex items-center justify-center text-gray-500 hover:text-espresso-700 text-xs font-bold">−</button>
                                    <span class="w-6 h-6 flex items-center justify-center text-[11px] font-bold text-espresso-700" x-text="item.quantity"></span>
                                    <button @click="updateQty(item.id, item.quantity + 1)" class="w-6 h-6 flex items-center justify-center text-gray-500 hover:text-espresso-700 text-xs font-bold">+</button>
                                </div>
                                <p class="text-sm font-bold text-espresso-700">₹<span x-text="(item.price * item.quantity).toLocaleString()"></span></p>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Free Gift (when unlocked) -->
                @if(config('shivara.free_gift_enabled'))
                <template x-if="subtotal >= {{ config('shivara.free_gift_threshold') }}">
                    <div class="flex gap-3 p-3 bg-purple-50 rounded-xl border border-purple-200 relative">
                        <span class="absolute -top-1.5 -right-1.5 bg-purple-500 text-white text-[8px] font-bold px-1.5 py-0.5 rounded-md uppercase">Free</span>
                        <div class="w-14 h-14 bg-white rounded-lg overflow-hidden shrink-0 border border-purple-100">
                            <img src="{{ config('shivara.free_gift_image') }}" alt="Free Gift" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-semibold text-purple-700">🎁 {{ config('shivara.free_gift_name') }}</p>
                            <p class="text-[10px] text-purple-500 mt-0.5">Complimentary with your order</p>
                            <p class="text-xs font-bold text-green-600 mt-1">FREE <span class="text-gray-400 line-through text-[10px]">₹299</span></p>
                        </div>
                    </div>
                </template>
                @endif
            </div>

            <!-- People Also Bought -->
            <template x-if="items.length > 0">
                <div class="px-4 py-4 border-t border-gray-100 mt-2">
                    <p class="text-xs font-bold text-espresso-700 uppercase tracking-wider mb-3">People also bought</p>
                    <div class="space-y-2">
                        @php $recommended = \App\Models\Product::active()->featured()->with('primaryImage')->take(3)->get(); @endphp
                        @foreach($recommended as $rec)
                        <div class="flex items-center gap-3 p-2.5 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                            <div class="w-11 h-11 bg-white rounded-lg overflow-hidden shrink-0 border border-gray-100">
                                <img src="{{ $rec->primaryImage?->url ?? 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=100&h=100&fit=crop' }}" alt="{{ $rec->name }}" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[11px] font-semibold text-espresso-700 line-clamp-1">{{ $rec->name }}</p>
                                <p class="text-[11px] text-espresso-500 font-bold">₹{{ number_format($rec->selling_price) }} <span class="text-gray-400 line-through text-[9px]">₹{{ number_format($rec->mrp) }}</span></p>
                            </div>
                            <form method="POST" action="{{ route('cart.add') }}">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $rec->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="px-2.5 py-1.5 bg-espresso-700 text-white text-[9px] font-bold rounded-md hover:bg-espresso-600 transition uppercase">Add</button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                </div>
            </template>
        </div>


        <!-- Footer -->
        <div class="border-t border-gray-200 px-5 py-4 bg-white space-y-3">
            <!-- Coupon -->
            <form method="POST" action="{{ route('cart.coupon') }}" class="flex gap-2">
                @csrf
                <input type="text" name="code" placeholder="Coupon code" class="flex-1 px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs focus:outline-none focus:ring-1 focus:ring-gold-300 placeholder:text-gray-400">
                <button type="submit" class="px-3 py-2 bg-espresso-700 text-white text-[10px] font-bold uppercase rounded-lg hover:bg-espresso-600 transition tracking-wider">Apply</button>
            </form>

            <!-- Totals -->
            <div class="space-y-1 text-xs">
                <div class="flex justify-between text-gray-500"><span>Subtotal</span><span class="font-semibold text-espresso-700">₹<span x-text="subtotal.toLocaleString()"></span></span></div>
                <div class="flex justify-between text-gray-500">
                    <span>Shipping</span>
                    <span :class="subtotal >= {{ config('shivara.free_shipping_threshold') }} ? 'text-green-600 font-bold' : 'text-espresso-600 font-semibold'" x-text="subtotal >= {{ config('shivara.free_shipping_threshold') }} ? 'FREE' : '₹{{ config('shivara.standard_rate') }}'"></span>
                </div>
                <div class="flex justify-between text-sm font-bold text-espresso-700 pt-1 border-t border-gray-100"><span>Total</span><span>₹<span x-text="total.toLocaleString()"></span></span></div>
            </div>

            <!-- Checkout -->
            <a href="{{ route('checkout.index') }}" class="block w-full px-6 py-3.5 bg-gradient-to-r from-gold-500 to-gold-600 text-white font-bold text-xs uppercase tracking-widest rounded-xl hover:from-gold-600 hover:to-gold-700 transition shadow-lg text-center">
                Checkout • ₹<span x-text="total.toLocaleString()"></span>
            </a>
            <button @click="open = false" class="block w-full text-center text-xs text-gray-400 hover:text-espresso-600 font-medium transition py-0.5">
                ← Continue Shopping
            </button>
        </div>
    </div>
</div>

<script>
function sideCart() {
    return {
        open: false,
        items: [],
        get totalItems() { return this.items.reduce((s, i) => s + i.quantity, 0); },
        get subtotal() { return this.items.reduce((s, i) => s + (i.price * i.quantity), 0); },
        get total() {
            let ship = this.subtotal >= {{ config('shivara.free_shipping_threshold') }} ? 0 : {{ config('shivara.standard_rate') }};
            return this.subtotal + ship;
        },
        get progressMessage() {
            if (this.subtotal >= {{ config('shivara.free_gift_threshold') }}) return '🎉 All rewards unlocked!';
            if (this.subtotal >= {{ config('shivara.free_shipping_threshold') }}) return '🎁 Shop ₹' + ({{ config('shivara.free_gift_threshold') }} - this.subtotal).toLocaleString() + ' more for a free gift';
            return '🚚 Add ₹' + ({{ config('shivara.free_shipping_threshold') }} - this.subtotal).toLocaleString() + ' for free delivery';
        },
        updateQty(id, qty) {
            if (qty <= 0) { this.removeItem(id); return; }
            let item = this.items.find(i => i.id === id);
            if (item) { item.quantity = Math.min(10, qty); }
        },
        removeItem(id) { this.items = this.items.filter(i => i.id !== id); },
        init() { this.loadCart(); },
        loadCart() {
            fetch('/cart/data', { headers: { 'Accept': 'application/json' } })
                .then(r => r.json())
                .then(data => { if (data.items) this.items = data.items; })
                .catch(() => {});
        }
    }
}
</script>

            @if(config('shivara.free_gift_enabled'))
            <div class="relative">
                <p class="text-xs font-semibold text-espresso-600 mb-2" x-text="progressMessage"></p>
                <div class="h-2 bg-gray-200 rounded-full overflow-hidden relative">
                    <div class="h-full rounded-full transition-all duration-700 ease-out"
                         :class="subtotal >= {{ config('shivara.free_gift_threshold') }} ? 'bg-gradient-to-r from-green-400 to-purple-500' : subtotal >= {{ config('shivara.free_shipping_threshold') }} ? 'bg-green-500' : 'bg-gold-500'"
                         :style="'width:' + Math.min(100, (subtotal / {{ config('shivara.free_gift_threshold') }}) * 100) + '%'"></div>
                    <div class="absolute top-1/2 -translate-y-1/2 w-2.5 h-2.5 rounded-full border-2 border-white shadow-sm" :class="subtotal >= {{ config('shivara.free_shipping_threshold') }} ? 'bg-green-500' : 'bg-gray-300'" style="left: {{ (config('shivara.free_shipping_threshold') / config('shivara.free_gift_threshold')) * 100 }}%"></div>
                </div>
                <div class="flex justify-between mt-1"><span class="text-[9px] text-gray-400">🚚 ₹{{ config('shivara.free_shipping_threshold') }}</span><span class="text-[9px] text-gray-400">🎁 ₹{{ config('shivara.free_gift_threshold') }}</span></div>
            </div>
            @else
            <div>
                <p class="text-xs font-semibold text-espresso-600 mb-2" x-text="subtotal >= {{ config('shivara.free_shipping_threshold') }} ? '🎉 Free delivery unlocked!' : '🚚 Add ₹' + ({{ config('shivara.free_shipping_threshold') }} - subtotal).toLocaleString() + ' for free delivery'"></p>
                <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full bg-green-500 rounded-full transition-all duration-700" :style="'width:' + Math.min(100, (subtotal / {{ config('shivara.free_shipping_threshold') }}) * 100) + '%'"></div>
                </div>
            </div>
            @endif
        </div>

        <!-- Items -->
        <div class="flex-1 overflow-y-auto">
            <template x-if="items.length === 0">
                <div class="flex flex-col items-center justify-center h-full text-center px-6 py-16">
                    <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mb-3"><svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg></div>
                    <p class="text-sm font-semibold text-espresso-700">Cart is empty</p>
                    <a href="{{ route('products.index') }}" @click="open = false" class="mt-3 px-5 py-2 bg-espresso-700 text-white text-xs font-bold rounded-lg hover:bg-espresso-600 transition">Shop Now</a>
                </div>
            </template>

            <div class="px-4 py-3 space-y-2">
                <template x-for="item in items" :key="item.id">
                    <div class="flex gap-3 p-2.5 bg-white rounded-xl border border-gray-100">
                        <div class="w-14 h-14 bg-gray-50 rounded-lg overflow-hidden shrink-0"><img :src="item.image" :alt="item.name" class="w-full h-full object-cover"></div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-1">
                                <p class="text-[11px] font-semibold text-espresso-700 line-clamp-2 leading-tight" x-text="item.name"></p>
                                <button @click="removeItem(item.id)" class="text-gray-300 hover:text-red-400 transition shrink-0 mt-0.5"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                            </div>
                            <div class="flex items-center justify-between mt-1.5">
                                <div class="flex items-center border border-gray-200 rounded">
                                    <button @click="updateQty(item.id, item.quantity - 1)" class="w-6 h-5 flex items-center justify-center text-gray-500 text-[10px] font-bold">−</button>
                                    <span class="w-5 h-5 flex items-center justify-center text-[10px] font-bold text-espresso-700" x-text="item.quantity"></span>
                                    <button @click="updateQty(item.id, item.quantity + 1)" class="w-6 h-5 flex items-center justify-center text-gray-500 text-[10px] font-bold">+</button>
                                </div>
                                <p class="text-xs font-bold text-espresso-700">₹<span x-text="(item.price * item.quantity).toLocaleString()"></span></p>
                            </div>
                        </div>
                    </div>
                </template>

                @if(config('shivara.free_gift_enabled'))
                <template x-if="subtotal >= {{ config('shivara.free_gift_threshold') }}">
                    <div class="flex gap-3 p-2.5 bg-purple-50 rounded-xl border border-purple-200 relative">
                        <span class="absolute -top-1 -right-1 bg-purple-500 text-white text-[7px] font-bold px-1.5 py-0.5 rounded uppercase">Free</span>
                        <div class="w-14 h-14 bg-white rounded-lg overflow-hidden shrink-0"><img src="{{ config('shivara.free_gift_image') }}" class="w-full h-full object-cover"></div>
                        <div><p class="text-[11px] font-semibold text-purple-700">🎁 {{ config('shivara.free_gift_name') }}</p><p class="text-[10px] text-green-600 font-bold mt-1">FREE</p></div>
                    </div>
                </template>
                @endif
            </div>

            <!-- People Also Bought -->
            <template x-if="items.length > 0">
                <div class="px-4 py-3 border-t border-gray-100">
                    <p class="text-[10px] font-bold text-espresso-600 uppercase tracking-wider mb-2">People also bought</p>
                    @php $recs = \App\Models\Product::active()->featured()->with('primaryImage')->take(3)->get(); @endphp
                    <div class="space-y-2">
                        @foreach($recs as $rec)
                        <div class="flex items-center gap-2.5 p-2 rounded-lg hover:bg-gray-50 transition">
                            <div class="w-10 h-10 bg-gray-50 rounded-lg overflow-hidden shrink-0 border border-gray-100"><img src="{{ $rec->primaryImage?->url ?? 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=80&h=80&fit=crop' }}" class="w-full h-full object-cover"></div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[10px] font-semibold text-espresso-700 line-clamp-1">{{ $rec->name }}</p>
                                <p class="text-[10px] font-bold text-espresso-600">₹{{ number_format($rec->selling_price) }}</p>
                            </div>
                            <form method="POST" action="{{ route('cart.add') }}">@csrf<input type="hidden" name="product_id" value="{{ $rec->id }}"><input type="hidden" name="quantity" value="1"><button class="px-2 py-1 bg-gold-500 text-white text-[8px] font-bold rounded uppercase hover:bg-gold-600 transition">Add</button></form>
                        </div>
                        @endforeach
                    </div>
                </div>
            </template>
        </div>

        <!-- Footer -->
        <div class="border-t border-gray-200 px-5 py-4 bg-white space-y-3">
            <div class="space-y-1 text-xs">
                <div class="flex justify-between text-gray-500"><span>Subtotal</span><span class="font-bold text-espresso-700">₹<span x-text="subtotal.toLocaleString()"></span></span></div>
                <div class="flex justify-between text-gray-500"><span>Shipping</span><span :class="subtotal >= {{ config('shivara.free_shipping_threshold') }} ? 'text-green-600 font-bold' : 'font-bold text-espresso-600'" x-text="subtotal >= {{ config('shivara.free_shipping_threshold') }} ? 'FREE' : '₹{{ config("shivara.standard_rate") }}'"></span></div>
                <div class="flex justify-between text-base font-bold text-espresso-700 pt-2 border-t border-gray-100"><span>Total</span><span>₹<span x-text="total.toLocaleString()"></span></span></div>
            </div>
            <a href="{{ route('checkout.index') }}" class="block w-full py-3.5 bg-gradient-to-r from-gold-500 to-gold-600 text-white font-bold text-xs uppercase tracking-widest rounded-xl text-center shadow-lg hover:from-gold-600 hover:to-gold-700 transition">
                Checkout • ₹<span x-text="total.toLocaleString()"></span>
            </a>
            <button @click="open = false" class="block w-full text-center text-[11px] text-gray-400 hover:text-espresso-600 transition">← Continue Shopping</button>
        </div>
    </div>
</div>
<script>
function sideCart() {
    return {
        open: false, items: [],
        get totalItems() { return this.items.reduce((s, i) => s + i.quantity, 0); },
        get subtotal() { return this.items.reduce((s, i) => s + (i.price * i.quantity), 0); },
        get total() { return this.subtotal + (this.subtotal >= {{ config('shivara.free_shipping_threshold') }} ? 0 : {{ config('shivara.standard_rate') }}); },
        get progressMessage() {
            if (this.subtotal >= {{ config('shivara.free_gift_threshold') }}) return '🎉 All rewards unlocked!';
            if (this.subtotal >= {{ config('shivara.free_shipping_threshold') }}) return '🎁 Add ₹' + ({{ config('shivara.free_gift_threshold') }} - this.subtotal).toLocaleString() + ' for free gift';
            return '🚚 Add ₹' + ({{ config('shivara.free_shipping_threshold') }} - this.subtotal).toLocaleString() + ' for free delivery';
        },
        updateQty(id, qty) {
            if (qty <= 0) { this.removeItem(id); return; }
            let item = this.items.find(x => x.id === id);
            if (item) item.quantity = Math.min(10, qty);
            // Sync with server
            fetch('/cart/update', {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                body: JSON.stringify({ item_id: id, quantity: qty })
            });
        },
        removeItem(id) {
            this.items = this.items.filter(i => i.id !== id);
            // Sync with server
            fetch('/cart/remove', {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                body: JSON.stringify({ item_id: id })
            });
        },
        init() { this.loadCart(); },
        loadCart() { fetch('/cart/data', {headers:{'Accept':'application/json'}}).then(r=>r.json()).then(d=>{if(d.items)this.items=d.items;}).catch(()=>{}); }
    }
}
</script>
