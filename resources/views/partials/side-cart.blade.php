@php
    $fgEnabled = \App\Models\Setting::get('free_gift_enabled', 'false') === 'true';
    $fgThreshold = (float) \App\Models\Setting::get('free_gift_threshold', config('shivara.free_gift_threshold', 1499));
    $fgProductId = \App\Models\Setting::get('free_gift_product_id');
    $fgProduct = $fgProductId ? \App\Models\Product::with('primaryImage')->find($fgProductId) : null;
    // Only truly enabled if both setting is true AND a gift product exists
    $fgEnabled = $fgEnabled && $fgProduct;
    $fsThreshold = config('shivara.free_shipping_threshold', 999);
    $shipRate = config('shivara.standard_rate', 50);
    $recs = \App\Models\Product::active()->featured()->with('primaryImage')->take(3)->get();
@endphp

<!-- Side Cart -->
<div x-data="sideCart()" @open-cart.window="open = true" x-cloak>
    <div x-show="open" x-transition.opacity @click="open = false" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-[100]"></div>
    <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="fixed top-0 right-0 bottom-0 w-full max-w-[400px] bg-white z-[101] flex flex-col shadow-2xl">

        <!-- Header -->
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-espresso-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <h2 class="text-base font-bold text-espresso-700">Your Cart</h2>
                <span class="text-[10px] bg-espresso-100 text-espresso-600 font-bold px-2 py-0.5 rounded-full" x-text="totalItems + ' items'"></span>
            </div>
            <button @click="open = false" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>

        <!-- Progress Bar (only show if free gift is enabled) -->
        @if($fgEnabled)
        <div class="px-5 py-3 bg-gray-50 border-b border-gray-100">
            <p class="text-xs font-semibold text-espresso-600 mb-2" x-text="progressMsg"></p>
            <div class="h-2.5 bg-gray-200 rounded-full overflow-hidden relative">
                <div class="h-full rounded-full transition-all duration-700" :class="barClass" :style="'width:' + barWidth + '%'"></div>
                <div class="absolute top-1/2 -translate-y-1/2 w-3 h-3 rounded-full border-2 border-white shadow-sm" :class="subtotal >= {{ $fsThreshold }} ? 'bg-green-500' : 'bg-gray-300'" style="left: {{ ($fsThreshold / $fgThreshold) * 100 }}%"></div>
            </div>
            <div class="flex justify-between mt-1"><span class="text-[9px]" :class="subtotal >= {{ $fsThreshold }} ? 'text-green-600 font-semibold' : 'text-gray-400'">🚚 ₹{{ $fsThreshold }}</span><span class="text-[9px]" :class="subtotal >= {{ $fgThreshold }} ? 'text-purple-600 font-semibold' : 'text-gray-400'">🎁 ₹{{ number_format($fgThreshold) }}</span></div>
        </div>
        @else
        {{-- Simple free shipping progress when no free gift --}}
        <div class="px-5 py-3 bg-gray-50 border-b border-gray-100" x-show="items.length > 0">
            <p class="text-xs font-semibold text-espresso-600 mb-2" x-text="subtotal >= {{ $fsThreshold }} ? '🎉 Free shipping unlocked!' : '🚚 Add ₹' + ({{ $fsThreshold }} - subtotal) + ' more for free shipping'"></p>
            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                <div class="h-full bg-green-500 rounded-full transition-all duration-700" :style="'width:' + Math.min(100, (subtotal / {{ $fsThreshold }}) * 100) + '%'"></div>
            </div>
        </div>
        @endif

        <!-- Items -->
        <div class="flex-1 overflow-y-auto">
            <template x-if="items.length === 0">
                <div class="flex flex-col items-center justify-center h-full text-center px-6">
                    <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mb-3"><svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg></div>
                    <p class="text-sm font-semibold text-espresso-700">Cart is empty</p>
                    <a href="{{ route('products.index') }}" @click="open=false" class="mt-3 px-5 py-2 bg-espresso-700 text-white text-xs font-bold rounded-lg">Shop Now</a>
                </div>
            </template>

            <div class="px-4 py-3 space-y-2">
                <template x-for="item in items" :key="item.id">
                    <div class="flex gap-3 p-2.5 bg-white rounded-xl border border-gray-100">
                        <div class="w-14 h-14 bg-gray-50 rounded-lg overflow-hidden shrink-0"><img :src="item.image" class="w-full h-full object-cover"></div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-1">
                                <div class="min-w-0">
                                    <p class="text-[11px] font-semibold text-espresso-700 line-clamp-2" x-text="item.name"></p>
                                    <p x-show="item.variant" class="text-[10px] text-gold-600 font-medium mt-0.5" x-text="item.variant"></p>
                                </div>
                                <button @click="removeItem(item.id)" class="text-gray-300 hover:text-red-400 shrink-0"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                            </div>
                            <div class="flex items-center justify-between mt-2">
                                <div class="flex items-center border border-gray-200 rounded">
                                    <button @click="updateQty(item.id, item.quantity-1)" class="w-6 h-6 text-xs font-bold text-gray-500 flex items-center justify-center">−</button>
                                    <span class="w-6 h-6 text-[11px] font-bold text-espresso-700 flex items-center justify-center" x-text="item.quantity"></span>
                                    <button @click="updateQty(item.id, item.quantity+1)" class="w-6 h-6 text-xs font-bold text-gray-500 flex items-center justify-center">+</button>
                                </div>
                                <p class="text-xs font-bold text-espresso-700">₹<span x-text="(item.price*item.quantity).toLocaleString()"></span></p>
                            </div>
                        </div>
                    </div>
                </template>

                @if($fgEnabled && $fgProduct)
                <template x-if="subtotal >= {{ $fgThreshold }}">
                    <div class="flex gap-3 p-2.5 bg-purple-50 rounded-xl border border-purple-200 relative">
                        <span class="absolute -top-1 -right-1 bg-purple-500 text-white text-[7px] font-bold px-1.5 py-0.5 rounded uppercase">Free</span>
                        <div class="w-14 h-14 bg-white rounded-lg overflow-hidden shrink-0"><img src="{{ $fgProduct->primary_image_url ?? 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=100&h=100&fit=crop' }}" class="w-full h-full object-cover"></div>
                        <div><p class="text-[11px] font-semibold text-purple-700">🎁 {{ $fgProduct->name }}</p><p class="text-[10px] text-green-600 font-bold mt-1">FREE <span class="text-gray-400 line-through">₹{{ number_format($fgProduct->selling_price) }}</span></p></div>
                    </div>
                </template>
                @endif
            </div>

            <!-- People Also Bought -->
            <template x-if="items.length > 0">
                <div class="px-4 py-3 border-t border-gray-100">
                    <p class="text-[10px] font-bold text-espresso-600 uppercase tracking-wider mb-2">People also bought</p>
                    @foreach($recs as $rec)
                    <div class="flex items-center gap-2.5 p-2 rounded-lg hover:bg-gray-50">
                        <div class="w-10 h-10 bg-gray-50 rounded-lg overflow-hidden shrink-0"><img src="{{ $rec->primary_image_url ?? 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=80&h=80&fit=crop' }}" class="w-full h-full object-cover"></div>
                        <div class="flex-1 min-w-0"><p class="text-[10px] font-semibold text-espresso-700 line-clamp-1">{{ $rec->name }}</p><p class="text-[10px] font-bold text-espresso-600">₹{{ number_format($rec->selling_price) }}</p></div>
                        <form method="POST" action="{{ route('cart.add') }}">@csrf<input type="hidden" name="product_id" value="{{ $rec->id }}"><input type="hidden" name="quantity" value="1"><button class="px-2 py-1 bg-gold-500 text-white text-[8px] font-bold rounded uppercase">Add</button></form>
                    </div>
                    @endforeach
                </div>
            </template>
        </div>

        <!-- Footer -->
        <div class="border-t border-gray-200 px-5 py-4 bg-white space-y-3">

            <!-- Coupon AJAX -->
            <div>
                <div class="flex gap-2">
                    <input type="text" x-model="couponCode" @keyup.enter="applyCoupon()" placeholder="Coupon code" class="flex-1 px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs focus:outline-none focus:ring-1 focus:ring-gold-300 placeholder:text-gray-400 uppercase">
                    <button @click="applyCoupon()" :disabled="applying" class="px-3 py-2 bg-espresso-700 text-white text-[10px] font-bold uppercase rounded-lg hover:bg-espresso-600 transition disabled:opacity-50">
                        <span x-text="applying ? '...' : 'Apply'"></span>
                    </button>
                </div>
                <p x-show="couponMsg" x-text="couponMsg" :class="couponError ? 'text-red-500' : 'text-green-600'" class="text-[10px] font-semibold mt-1.5" x-cloak></p>
            </div>

            <!-- Totals -->
            <div class="space-y-1 text-xs">
                <div class="flex justify-between text-gray-500"><span>Subtotal</span><span class="font-bold text-espresso-700">₹<span x-text="subtotal.toLocaleString()"></span></span></div>
                <div class="flex justify-between text-gray-500"><span>Shipping</span><span :class="(items.length === 0 || subtotal >= {{ $fsThreshold }}) ? 'text-green-600 font-bold' : 'font-bold text-espresso-600'" x-text="items.length === 0 ? '-' : (subtotal >= {{ $fsThreshold }} ? 'FREE' : '₹{{ $shipRate }}')"></span></div>
                <template x-if="coupon"><div class="flex justify-between text-green-600"><span>Coupon (<span x-text="coupon.code"></span>)</span><span class="font-bold">-₹<span x-text="couponDiscount.toLocaleString()"></span></span></div></template>
                <div class="flex justify-between text-base font-bold text-espresso-700 pt-2 border-t border-gray-100"><span>Total</span><span>₹<span x-text="total.toLocaleString()"></span></span></div>
            </div>

            <button @click="openRazorpay()" :disabled="items.length === 0" class="block w-full py-3.5 bg-gradient-to-r from-gold-500 to-gold-600 text-white font-bold text-xs uppercase tracking-widest rounded-xl text-center shadow-lg hover:from-gold-600 hover:to-gold-700 transition disabled:opacity-50 disabled:cursor-not-allowed">Pay Now • ₹<span x-text="total.toLocaleString()"></span></button>
            <a href="{{ route('checkout.index') }}" class="block w-full text-center text-[11px] text-gray-400 hover:text-espresso-600 transition mt-1">Or checkout with COD →</a>
            <button @click="open = false" class="block w-full text-center text-[11px] text-gray-400 hover:text-espresso-600 transition">← Continue Shopping</button>
        </div>
    </div>
</div>

<script>
function sideCart() {
    return {
        open: false, items: [], coupon: null, couponCode: '', couponMsg: '', couponError: false, applying: false,
        get totalItems() { return this.items.reduce((s, i) => s + i.quantity, 0); },
        get subtotal() { return this.items.reduce((s, i) => s + (i.price * i.quantity), 0); },
        get couponDiscount() {
            if (!this.coupon) return 0;
            let d = this.coupon.type === 'percentage' ? this.subtotal * (this.coupon.value / 100) : this.coupon.value;
            if (this.coupon.max_discount && d > this.coupon.max_discount) d = this.coupon.max_discount;
            return Math.min(d, this.subtotal);
        },
        get total() {
            if (this.items.length === 0) return 0;
            let ship = this.subtotal >= {{ $fsThreshold }} ? 0 : {{ $shipRate }};
            return this.subtotal - this.couponDiscount + ship;
        },
        get progressMsg() {
            if (this.items.length === 0) return '🚚 Add ₹{{ $fsThreshold }} for free delivery';
            @if($fgEnabled)
            if (this.subtotal >= {{ $fgThreshold }}) return '🎉 All rewards unlocked!';
            if (this.subtotal >= {{ $fsThreshold }}) return '🎁 Add ₹' + ({{ $fgThreshold }} - this.subtotal).toLocaleString() + ' more for a FREE gift!';
            @else
            if (this.subtotal >= {{ $fsThreshold }}) return '🎉 Free delivery unlocked!';
            @endif
            return '🚚 Add ₹' + ({{ $fsThreshold }} - this.subtotal).toLocaleString() + ' for free delivery';
        },
        get barWidth() {
            let max = {{ $fgEnabled ? $fgThreshold : $fsThreshold }};
            return Math.min(100, (this.subtotal / max) * 100);
        },
        get barClass() {
            @if($fgEnabled)
            if (this.subtotal >= {{ $fgThreshold }}) return 'bg-gradient-to-r from-green-400 to-purple-500';
            if (this.subtotal >= {{ $fsThreshold }}) return 'bg-green-500';
            @else
            if (this.subtotal >= {{ $fsThreshold }}) return 'bg-green-500';
            @endif
            return 'bg-gold-500';
        },
        updateQty(id, qty) {
            if (qty <= 0) { this.removeItem(id); return; }
            let item = this.items.find(x => x.id === id);
            if (item) item.quantity = Math.min(10, qty);
            fetch('/cart/update', { method: 'PATCH', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }, body: JSON.stringify({ item_id: id, quantity: qty }) });
        },
        removeItem(id) {
            this.items = this.items.filter(i => i.id !== id);
            fetch('/cart/remove', { method: 'DELETE', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }, body: JSON.stringify({ item_id: id }) });
        },
        applyCoupon() {
            if (!this.couponCode.trim()) return;
            this.applying = true; this.couponMsg = ''; this.couponError = false;
            fetch('/cart/coupon', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }, body: JSON.stringify({ code: this.couponCode }) })
            .then(r => r.json())
            .then(d => {
                this.applying = false;
                if (d.success) { this.coupon = d.coupon; this.couponMsg = '✓ ' + d.message; this.couponError = false; }
                else { this.couponMsg = d.message || 'Invalid coupon'; this.couponError = true; }
            }).catch(() => { this.applying = false; this.couponMsg = 'Error'; this.couponError = true; });
        },
        init() {
            this.loadCart();
            // Listen for cart-updated events (from AJAX add-to-cart)
            window.addEventListener('cart-updated', () => this.loadCart());
        },
        loadCart() {
            fetch('/cart/data', {headers:{'Accept':'application/json'}}).then(r=>r.json()).then(d=>{
                if(d.items) this.items = d.items;
                // Auto-apply coupon if server sends one and user hasn't manually applied one
                if(d.auto_coupon && !this.coupon) {
                    this.coupon = d.auto_coupon;
                    this.couponCode = d.auto_coupon.code;
                    this.couponMsg = '✓ ' + (d.auto_coupon.description || d.auto_coupon.code) + ' auto-applied!';
                    this.couponError = false;
                }
                // If cart is empty, clear coupon
                if(!d.items || d.items.length === 0) {
                    this.coupon = null;
                    this.couponCode = '';
                    this.couponMsg = '';
                }
            }).catch(()=>{});
        },
        openRazorpay() {
            if (this.items.length === 0) return;
            console.log('[Shivara] Creating Razorpay order...');
            fetch('/checkout/razorpay', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                body: JSON.stringify({})
            })
            .then(r => r.json())
            .then(data => {
                console.log('[Shivara] Order response:', data);
                if (!data.success) { alert(data.error || 'Error creating order'); return; }
                console.log('[Shivara] Razorpay object available:', typeof Razorpay);
                var options = {
                    key: data.razorpay_key,
                    amount: data.amount,
                    currency: data.currency,
                    name: data.name,
                    description: data.description,
                    order_id: data.razorpay_order_id,
                    image: '/public/shivaralogo1.png',
                    one_click_checkout: true,
                    show_coupons: true,
                    handler: function(response) {
                        // Payment success - verify on server
                        var form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '/payment/verify';
                        var fields = { _token: document.querySelector('meta[name="csrf-token"]').content, razorpay_order_id: response.razorpay_order_id, razorpay_payment_id: response.razorpay_payment_id, razorpay_signature: response.razorpay_signature };
                        for (var key in fields) { var input = document.createElement('input'); input.type='hidden'; input.name=key; input.value=fields[key]; form.appendChild(input); }
                        document.body.appendChild(form);
                        form.submit();
                    },
                    theme: { color: '#2C2418' },
                    modal: { ondismiss: function() { console.log('[Shivara] Checkout dismissed'); } }
                };
                console.log('[Shivara] Opening Razorpay with options:', options);
                try {
                    var rzp = new Razorpay(options);
                    rzp.on('payment.failed', function(resp) { console.error('[Shivara] Payment failed:', resp); alert('Payment failed. Please try again.'); });
                    rzp.open();
                } catch(e) {
                    console.error('[Shivara] Razorpay open error:', e);
                    alert('Checkout error: ' + e.message);
                }
            })
            .catch(err => { alert('Something went wrong. Please try again.'); console.error('[Shivara] Fetch error:', err); });
        }
    }
}
</script>
