@extends('layouts.admin')
@section('title', 'Create Order - Admin')
@section('page_title', 'Create Manual Order')

@section('content')
<div class="max-w-4xl">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Create Manual Order</h1>
            <p class="text-sm text-gray-500">Add an order manually (phone/WhatsApp orders)</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 border border-gray-200 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-50 transition">Back</a>
    </div>

    <form method="POST" action="{{ route('admin.orders.store') }}" class="space-y-6">
        @csrf

        <!-- Customer Info -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4">
            <h2 class="text-base font-bold text-gray-900 pb-3 border-b border-gray-100">Customer & Shipping</h2>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Full Name *</label>
                    <input type="text" name="full_name" required class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Phone *</label>
                    <input type="text" name="phone" required class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Address *</label>
                    <input type="text" name="address_line1" required placeholder="House no, Street, Area" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">City *</label>
                    <input type="text" name="city" required class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">State *</label>
                    <input type="text" name="state" required class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Pincode *</label>
                    <input type="text" name="pincode" required maxlength="6" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
                </div>
            </div>
        </div>

        <!-- Products -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4" x-data="manualOrder()">
            <h2 class="text-base font-bold text-gray-900 pb-3 border-b border-gray-100">Order Items</h2>

            <template x-for="(item, i) in items" :key="i">
                <div class="grid grid-cols-12 gap-3 items-end">
                    <div class="col-span-5">
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1" x-show="i === 0">Product</label>
                        <select :name="'items['+i+'][product_id]'" x-model="item.product_id" @change="updatePrice(i)" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none" required>
                            <option value="">Select Product</option>
                            @foreach(\App\Models\Product::active()->orderBy('name')->get() as $p)
                            <option value="{{ $p->id }}" data-price="{{ $p->selling_price }}">{{ $p->name }} — ₹{{ number_format($p->selling_price) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1" x-show="i === 0">Qty</label>
                        <input type="number" :name="'items['+i+'][quantity]'" x-model="item.qty" min="1" value="1" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none" required>
                    </div>
                    <div class="col-span-3">
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1" x-show="i === 0">Price (₹)</label>
                        <input type="number" :name="'items['+i+'][price]'" x-model="item.price" step="0.01" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none" required>
                    </div>
                    <div class="col-span-2">
                        <button type="button" @click="items.splice(i, 1)" x-show="items.length > 1" class="px-3 py-2.5 text-red-500 text-xs font-bold bg-red-50 rounded-lg hover:bg-red-100 w-full">Remove</button>
                    </div>
                </div>
            </template>

            <button type="button" @click="items.push({product_id:'', qty:1, price:''})" class="px-4 py-2 text-xs font-bold rounded-lg text-white hover:opacity-90 transition" style="background-color:#16a34a">+ Add Item</button>

            <div class="pt-4 border-t border-gray-100 text-right">
                <p class="text-sm text-gray-500">Subtotal: <span class="font-bold text-gray-900">₹<span x-text="items.reduce((s,i) => s + (parseFloat(i.price||0) * parseInt(i.qty||1)), 0).toLocaleString()"></span></span></p>
            </div>
        </div>

        <!-- Order Settings -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4">
            <h2 class="text-base font-bold text-gray-900 pb-3 border-b border-gray-100">Order Settings</h2>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Payment Method</label>
                    <select name="payment_method" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none">
                        <option value="cod">Cash on Delivery</option>
                        <option value="prepaid">Prepaid (Already Paid)</option>
                        <option value="razorpay">Razorpay</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Shipping Method</label>
                    <select name="shipping_method" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none">
                        <option value="standard">Standard (5-7 days)</option>
                        <option value="express">Express (2-3 days)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Shipping Charge (₹)</label>
                    <input type="number" name="shipping_charge" value="0" step="0.01" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Discount (₹)</label>
                    <input type="number" name="discount" value="0" step="0.01" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Admin Notes (internal)</label>
                <input type="text" name="admin_notes" placeholder="e.g. Phone order, WhatsApp order" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
            </div>
        </div>

            <!-- Shipping & Tracking -->
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4">
                <h2 class="text-base font-bold text-gray-900 pb-3 border-b border-gray-100">Shipping & Tracking</h2>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Courier Name</label>
                        <select name="courier_name" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none">
                            <option value="">Select Courier (optional)</option>
                            @foreach(['Delhivery','DTDC','Blue Dart','Ekart','India Post','Shiprocket','Ecom Express','Shadowfax','XpressBees','Other'] as $c)
                            <option value="{{ $c }}">{{ $c }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">AWB / Tracking Number</label>
                        <input type="text" name="tracking_number" placeholder="e.g. DL1234567890" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Tracking URL <span class="text-gray-400">(optional)</span></label>
                    <input type="url" name="tracking_url" placeholder="https://www.delhivery.com/track/..." class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
                </div>
            </div>

        <div class="flex gap-3">
            <button type="submit" class="px-8 py-3.5 text-white font-bold text-sm rounded-xl hover:opacity-90 transition shadow-lg" style="background-color:#B7925C">Create Order</button>
            <a href="{{ route('admin.orders.index') }}" class="px-6 py-3.5 border border-gray-200 text-gray-600 font-medium text-sm rounded-xl hover:bg-gray-50 transition">Cancel</a>
        </div>
    </form>
</div>

<script>
function manualOrder() {
    return {
        items: [{ product_id: '', qty: 1, price: '' }],
        updatePrice(i) {
            var sel = document.querySelectorAll('select[name="items['+i+'][product_id]"]')[0];
            if (sel && sel.selectedOptions[0]) {
                var price = sel.selectedOptions[0].getAttribute('data-price');
                if (price) this.items[i].price = price;
            }
        }
    }
}
</script>
@endsection
