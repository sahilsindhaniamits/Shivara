@extends('layouts.admin')
@section('title', 'Order ' . $order->order_number . ' - Admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Order {{ $order->order_number }}</h1>
            <p class="text-sm text-gray-500">Placed {{ $order->created_at->format('M d, Y H:i') }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.orders.invoice', $order) }}" target="_blank" class="px-4 py-2 text-sm font-semibold rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Invoice
            </a>
            <a href="{{ route('admin.orders.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Back</a>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Order Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Items -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <h2 class="font-bold text-gray-900 mb-4">Order Items</h2>
                <div class="space-y-4">
                    @foreach($order->items as $item)
                    <div class="flex items-center gap-4 py-3 border-b border-gray-50 last:border-0">
                        <div class="w-12 h-12 bg-gray-100 rounded-lg overflow-hidden shrink-0">
                            @if($item->product && $item->product->primaryImage && $item->product->primaryImage->url)
                            <img src="{{ str_starts_with($item->product->primaryImage->url, '/storage/') ? '/public' . $item->product->primaryImage->url : $item->product->primaryImage->url }}" class="w-full h-full object-cover" alt="">
                            @elseif($item->product)
                            @php $firstImg = $item->product->images()->first(); @endphp
                            @if($firstImg)
                            <img src="{{ str_starts_with($firstImg->url, '/storage/') ? '/public' . $firstImg->url : $firstImg->url }}" class="w-full h-full object-cover" alt="">
                            @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
                            @endif
                            @else
                            <div class="w-full h-full flex items-center justify-center text-lg">🌿</div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800">{{ $item->product_name }}</p>
                            @if($item->variant_name)<p class="text-xs text-gray-500">{{ $item->variant_name }}</p>@endif
                            <p class="text-xs text-gray-400">Qty: {{ $item->quantity }}</p>
                        </div>
                        <p class="text-sm font-semibold">₹{{ number_format($item->total_price) }}</p>
                    </div>
                    @endforeach
                </div>
                <hr class="my-4">
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between"><span class="text-gray-600">Subtotal</span><span>₹{{ number_format($order->subtotal) }}</span></div>
                    @if($order->discount > 0)<div class="flex justify-between text-green-600"><span>Discount</span><span>-₹{{ number_format($order->discount) }}</span></div>@endif
                    <div class="flex justify-between"><span class="text-gray-600">Shipping</span><span>₹{{ number_format($order->shipping_charge) }}</span></div>
                    <hr>
                    <div class="flex justify-between font-bold text-lg"><span>Total</span><span class="text-primary">₹{{ number_format($order->total_amount) }}</span></div>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <h2 class="font-bold text-gray-900 mb-4">Shipping Address</h2>
                @if($order->address)
                <p class="text-sm text-gray-700">{{ $order->address->full_name }}</p>
                <p class="text-sm text-gray-600">{{ $order->address->address_line1 }}</p>
                @if($order->address->address_line2)<p class="text-sm text-gray-600">{{ $order->address->address_line2 }}</p>@endif
                <p class="text-sm text-gray-600">{{ $order->address->city }}, {{ $order->address->state }} - {{ $order->address->pincode }}</p>
                <p class="text-sm text-gray-600 mt-1">Phone: {{ $order->address->phone }}</p>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Update Status -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <h3 class="font-bold text-gray-900 mb-4">Update Status</h3>
                <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}">
                    @csrf @method('PATCH')
                    <select name="status" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm mb-3">
                        @foreach(['pending','confirmed','processing','shipped','out_for_delivery','delivered','cancelled'] as $status)
                        <option value="{{ $status }}" {{ $order->status == $status ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="w-full px-4 py-2.5 text-white text-sm font-medium rounded-xl hover:opacity-90 transition" style="background-color:#c06d22">Update</button>
                </form>
            </div>

            <!-- Info -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-3">
                <h3 class="font-bold text-gray-900 mb-2">Order Info</h3>
                <div class="flex justify-between text-sm"><span class="text-gray-500">Payment Method</span><span class="font-medium">{{ strtoupper($order->payment_method) }}</span></div>
                <div class="flex justify-between text-sm"><span class="text-gray-500">Payment Status</span><span class="font-medium {{ $order->payment_status == 'paid' ? 'text-green-600' : 'text-yellow-600' }}">{{ ucfirst($order->payment_status) }}</span></div>
                <div class="flex justify-between text-sm"><span class="text-gray-500">Shipping</span><span class="font-medium">{{ ucfirst($order->shipping_method) }}</span></div>
                @if($order->coupon_code)<div class="flex justify-between text-sm"><span class="text-gray-500">Coupon</span><span class="font-medium text-primary">{{ $order->coupon_code }}</span></div>@endif
                <div class="flex justify-between text-sm"><span class="text-gray-500">Customer</span><span class="font-medium">{{ $order->user->name }}</span></div>
            </div>
        </div>
    </div>
</div>
@endsection
