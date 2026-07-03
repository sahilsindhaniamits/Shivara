@extends('layouts.app')
@section('title', 'Order ' . $order->order_number . ' - Shivara')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    <div class="flex flex-col md:flex-row gap-8">
        @include('storefront.account._sidebar')

        <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <a href="{{ route('account.orders') }}" class="text-sm text-brand-600 hover:underline mb-1 inline-block">← Back to Orders</a>
                    <h1 class="text-xl font-display font-bold text-gray-900">Order {{ $order->order_number }}</h1>
                    <p class="text-sm text-gray-500">Placed {{ $order->created_at->format('M d, Y \a\t h:i A') }}</p>
                </div>
                @php $colors = ['pending'=>'yellow','confirmed'=>'blue','processing'=>'indigo','shipped'=>'purple','delivered'=>'green','cancelled'=>'red']; @endphp
                <span class="text-xs font-bold uppercase px-3 py-1.5 rounded-full bg-{{ $colors[$order->status] ?? 'gray' }}-100 text-{{ $colors[$order->status] ?? 'gray' }}-700">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
            </div>

            <!-- Order Timeline -->
            @if($order->timeline->count())
            <div class="bg-white p-5 rounded-2xl border border-gray-100 mb-6">
                <h3 class="font-bold text-sm text-gray-900 mb-4">Order Timeline</h3>
                <div class="space-y-3">
                    @foreach($order->timeline as $event)
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 bg-brand-500 rounded-full mt-1.5 shrink-0"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $event->message }}</p>
                            <p class="text-xs text-gray-400">{{ $event->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Items -->
            <div class="bg-white rounded-2xl border border-gray-100 mb-6">
                <div class="p-5 border-b border-gray-100">
                    <h3 class="font-bold text-sm text-gray-900">Items ({{ $order->items->count() }})</h3>
                </div>
                @foreach($order->items as $item)
                <div class="p-5 border-b border-gray-50 last:border-0 flex items-center gap-4">
                    <div class="w-14 h-14 bg-gray-50 rounded-xl flex items-center justify-center shrink-0 overflow-hidden">
                        @if($item->product && $item->product->primaryImage)
                        <img src="{{ $item->product->primaryImage->url }}" class="w-full h-full object-cover">
                        @else
                        <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        @endif
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-900">{{ $item->product_name }}</p>
                        @if($item->variant_name)<p class="text-xs text-gray-500">{{ $item->variant_name }}</p>@endif
                        <p class="text-xs text-gray-400 mt-0.5">Qty: {{ $item->quantity }} × ₹{{ number_format($item->price) }}</p>
                    </div>
                    <p class="text-sm font-bold text-gray-900">₹{{ number_format($item->total_price) }}</p>
                </div>
                @endforeach
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <!-- Address -->
                <div class="bg-white p-5 rounded-2xl border border-gray-100">
                    <h3 class="font-bold text-sm text-gray-900 mb-3">Delivery Address</h3>
                    @if($order->address)
                    <p class="text-sm text-gray-700">{{ $order->address->full_name }}</p>
                    <p class="text-sm text-gray-500">{{ $order->address->address_line1 }}</p>
                    @if($order->address->address_line2)<p class="text-sm text-gray-500">{{ $order->address->address_line2 }}</p>@endif
                    <p class="text-sm text-gray-500">{{ $order->address->city }}, {{ $order->address->state }} - {{ $order->address->pincode }}</p>
                    <p class="text-sm text-gray-500 mt-1">Phone: {{ $order->address->phone }}</p>
                    @endif
                </div>

                <!-- Payment Summary -->
                <div class="bg-white p-5 rounded-2xl border border-gray-100">
                    <h3 class="font-bold text-sm text-gray-900 mb-3">Payment Summary</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between"><span class="text-gray-500">Subtotal</span><span>₹{{ number_format($order->subtotal) }}</span></div>
                        @if($order->discount > 0)<div class="flex justify-between text-green-600"><span>Discount</span><span>-₹{{ number_format($order->discount) }}</span></div>@endif
                        <div class="flex justify-between"><span class="text-gray-500">Shipping</span><span>₹{{ number_format($order->shipping_charge) }}</span></div>
                        <hr class="border-gray-100">
                        <div class="flex justify-between font-bold text-base"><span>Total</span><span>₹{{ number_format($order->total_amount) }}</span></div>
                        <div class="flex justify-between text-xs text-gray-400"><span>Payment</span><span>{{ strtoupper($order->payment_method) }} • {{ ucfirst($order->payment_status) }}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
