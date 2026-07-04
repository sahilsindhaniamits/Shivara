<!-- Side Cart Drawer -->
<div x-data="sideCart()" x-on:open-cart.window="open = true" x-cloak>
    <!-- Backdrop -->
    <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         @click="open = false" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[100]"></div>

    <!-- Cart Panel -->
    <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
         class="fixed top-0 right-0 bottom-0 w-full max-w-md bg-white z-[101] flex flex-col shadow-2xl">

        <!-- Header -->
        <div class="px-5 py-4 border-b border-gold-100 flex items-center justify-between" style="background: #FBF7F0;">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-gold-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <h2 class="text-lg font-bold text-espresso-700">Your Cart</h2>
                <span class="text-xs bg-gold-100 text-gold-700 font-bold px-2 py-0.5 rounded-full" x-text="totalItems + ' items'"></span>
            </div>
            <button @click="open = false" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-espresso-100 text-espresso-400 hover:text-espresso-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Progress Bars -->
        <div class="px-5 py-3 space-y-3 border-b border-gold-100/50" style="background: #FFFDF8;">
            <!-- Free Shipping Progress -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-espresso-500">
                        <template x-if="subtotal >= {{ config('shivara.free_shipping_threshold') }}">
                            <span class="text-green-600">🎉 Free Shipping Unlocked!</span>
                        </template>
                        <template x-if="subtotal < {{ config('shivara.free_shipping_threshold') }}">
                            <span>Add ₹<span x-text="({{ config('shivara.free_shipping_threshold') }} - subtotal).toLocaleString()"></span> for FREE shipping</span>
                        </template>
                    </span>
                    <span class="text-[10px] text-espresso-400">₹{{ config('shivara.free_shipping_threshold') }}</span>
                </div>
                <div class="h-2 bg-espresso-100 rounded-full overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-700 ease-out" :class="subtotal >= {{ config('shivara.free_shipping_threshold') }} ? 'bg-green-500' : 'bg-gold-500'" :style="'width:' + Math.min(100, (subtotal / {{ config('shivara.free_shipping_threshold') }}) * 100) + '%'"></div>
                </div>
            </div>

            @if(config('shivara.free_gift_enabled'))
            <!-- Free Gift Progress -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-espresso-500">
                        <template x-if="subtotal >= {{ config('shivara.free_gift_threshold') }}">
                            <span class="text-purple-600">🎁 Free Gift Added!</span>
                        </template>
                        <template x-if="subtotal < {{ config('shivara.free_gift_threshold') }}">
                            <span>Add ₹<span x-text="({{ config('shivara.free_gift_threshold') }} - subtotal).toLocaleString()"></span> for FREE gift</span>
                        </template>
                    </span>
                    <span class="text-[10px] text-espresso-400">₹{{ config('shivara.free_gift_threshold') }}</span>
                </div>
                <div class="h-2 bg-espresso-100 rounded-full overflow-hidden">
                    <div class="h-full bg-purple-500 rounded-full transition-all duration-700 ease-out" :style="'width:' + Math.min(100, (subtotal / {{ config('shivara.free_gift_threshold') }}) * 100) + '%'"></div>
                </div>
            </div>
            @endif
        </div>

        <!-- Cart Items -->
        <div class="flex-1 overflow-y-auto px-5 py-4 space-y-4">
            <template x-if="items.length === 0">
                <div class="flex flex-col items-center justify-center h-full text-center py-12">
                    <div class="w-20 h-20 bg-cream-200 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-espresso-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </div>
                    <p class="text-espresso-600 font-semibold">Your cart is empty</p>
                    <p class="text-espresso-400 text-sm mt-1">Add products to get started</p>
                    <a href="{{ route('products.index') }}" @click="open = false" class="mt-4 px-6 py-2.5 bg-espresso-700 text-cream-50 text-sm font-semibold rounded-xl hover:bg-espresso-600 transition">Browse Products</a>
                </div>
            </template>

            <template x-for="item in items" :key="item.id">
                <div class="flex gap-3 p-3 bg-cream-50 rounded-xl border border-gold-100/50">
                    <div class="w-16 h-16 bg-white rounded-lg overflow-hidden shrink-0 border border-gold-100/30">
                        <img :src="item.image || 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=100&h=100&fit=crop'" :alt="item.name" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-espresso-700 line-clamp-1" x-text="item.name"></p>
                        <p class="text-xs text-espresso-400 mt-0.5" x-show="item.variant" x-text="item.variant"></p>
                        <div class="flex items-center justify-between mt-2">
                            <div class="flex items-center bg-white rounded-lg border border-gold-100">
                                <button @click="updateQty(item.id, item.quantity - 1)" class="w-7 h-7 flex items-center justify-center text-espresso-500 hover:text-espresso-700 text-sm font-bold">−</button>
                                <span class="w-7 h-7 flex items-center justify-center text-xs font-bold text-espresso-700" x-text="item.quantity"></span>
                                <button @click="updateQty(item.id, item.quantity + 1)" class="w-7 h-7 flex items-center justify-center text-espresso-500 hover:text-espresso-700 text-sm font-bold">+</button>
                            </div>
                            <p class="text-sm font-bold text-espresso-700">₹<span x-text="(item.price * item.quantity).toLocaleString()"></span></p>
                        </div>
                    </div>
                    <button @click="removeItem(item.id)" class="self-start p-1 text-espresso-300 hover:text-red-500 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </template>

            <!-- Free Gift Item (shown when threshold met) -->
            @if(config('shivara.free_gift_enabled'))
            <template x-if="subtotal >= {{ config('shivara.free_gift_threshold') }}">
                <div class="flex gap-3 p-3 bg-purple-50 rounded-xl border border-purple-200 relative overflow-hidden">
                    <div class="absolute top-0 right-0 bg-purple-500 text-white text-[8px] font-bold px-2 py-0.5 rounded-bl-lg uppercase">Free</div>
                    <div class="w-16 h-16 bg-white rounded-lg overflow-hidden shrink-0 border border-purple-200">
                        <img src="{{ config('shivara.free_gift_image') }}" alt="Free Gift" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-purple-700">🎁 {{ config('shivara.free_gift_name') }}</p>
                        <p class="text-xs text-purple-500 mt-0.5">Free with your order!</p>
                        <p class="text-sm font-bold text-green-600 mt-1">FREE <span class="text-purple-400 line-through text-xs">₹299</span></p>
                    </div>
                </div>
            </template>
            @endif
        </div>

        <!-- Footer -->
        <div class="border-t border-gold-100 px-5 py-4 space-y-3" style="background: #FBF7F0;">
            <!-- Coupon -->
            <div class="flex gap-2">
                <input type="text" placeholder="Coupon code" class="flex-1 px-3 py-2.5 bg-white border border-gold-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold-300 placeholder:text-espresso-300 text-espresso-700">
                <button class="px-4 py-2.5 bg-espresso-700 text-cream-50 text-xs font-bold uppercase rounded-lg hover:bg-espresso-600 transition">Apply</button>
            </div>

            <!-- Totals -->
            <div class="space-y-1.5 text-sm">
                <div class="flex justify-between text-espresso-500"><span>Subtotal</span><span class="font-semibold text-espresso-700">₹<span x-text="subtotal.toLocaleString()"></span></span></div>
                <div class="flex justify-between text-espresso-500">
                    <span>Shipping</span>
                    <span :class="subtotal >= {{ config('shivara.free_shipping_threshold') }} ? 'text-green-600 font-semibold' : 'text-espresso-700 font-semibold'" x-text="subtotal >= {{ config('shivara.free_shipping_threshold') }} ? 'FREE' : '₹{{ config('shivara.standard_rate') }}'"></span>
                </div>
                @if(config('shivara.free_gift_enabled'))
                <template x-if="subtotal >= {{ config('shivara.free_gift_threshold') }}">
                    <div class="flex justify-between text-purple-600"><span>🎁 Free Gift</span><span class="font-semibold">Added!</span></div>
                </template>
                @endif
                <hr class="border-gold-200">
                <div class="flex justify-between text-lg font-bold text-espresso-700"><span>Total</span><span>₹<span x-text="total.toLocaleString()"></span></span></div>
            </div>

            <!-- Checkout Button -->
            <a href="{{ route('cart.index') }}" class="block w-full px-8 py-4 bg-gold-500 text-white font-bold text-sm uppercase tracking-wider rounded-xl hover:bg-gold-600 transition shadow-lg shadow-gold-500/20 text-center">
                Proceed to Checkout →
            </a>
            <button @click="open = false" class="block w-full text-center text-sm text-espresso-500 hover:text-espresso-700 font-medium transition py-1">
                ← Continue Shopping
            </button>
        </div>
    </div>
</div>

<script>
function sideCart() {
    return {
        open: false,
        items: @json($__env->yieldContent('cart_items', '[]') ?: '[]'),
        get totalItems() { return this.items.reduce((sum, i) => sum + i.quantity, 0); },
        get subtotal() { return this.items.reduce((sum, i) => sum + (i.price * i.quantity), 0); },
        get total() {
            let shipping = this.subtotal >= {{ config('shivara.free_shipping_threshold') }} ? 0 : {{ config('shivara.standard_rate') }};
            return this.subtotal + shipping;
        },
        updateQty(id, qty) {
            if (qty <= 0) { this.removeItem(id); return; }
            let item = this.items.find(i => i.id === id);
            if (item) item.quantity = Math.min(10, qty);
            this.syncCart();
        },
        removeItem(id) {
            this.items = this.items.filter(i => i.id !== id);
            this.syncCart();
        },
        syncCart() {
            // Submit to server
            fetch('{{ route("cart.update") }}', {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: JSON.stringify({ items: this.items })
            }).catch(() => {});
        },
        init() { this.loadCart(); },
        loadCart() {
            fetch('/cart/data')
                .then(r => r.json())
                .then(data => { this.items = data.items || []; })
                .catch(() => {});
        }
    }
}
</script>
