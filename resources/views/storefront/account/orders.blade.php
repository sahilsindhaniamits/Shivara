@extends('layouts.app')
@section('title', 'My Orders - Shivara')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    <div class="flex flex-col md:flex-row gap-8">
        @include('storefront.account._sidebar')

        <div class="flex-1 min-w-0">
            <h1 class="text-2xl font-display font-bold text-gray-900 mb-6">My Orders</h1>

            <div class="space-y-4">
                @forelse($orders as $order)
                <a href="{{ route('account.orders.show', $order->order_number) }}" class="block bg-white p-5 rounded-2xl border border-gray-100 hover:border-brand-200 hover:shadow-sm transition">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-bold text-gray-900">{{ $order->order_number }}</p>
                            <p class="text-xs text-gray-500 mt-1">Placed on {{ $order->created_at->format('M d, Y \a\t h:i A') }}</p>
                            <div class="flex items-center gap-2 mt-3">
                                @foreach($order->items->take(3) as $item)
                                <div class="w-10 h-10 bg-gray-50 rounded-lg border border-gray-100 flex items-center justify-center overflow-hidden">
                                    @if($item->product && $item->product->primaryImage)
                                    <img src="{{ str_starts_with($item->product->primaryImage->url, '/storage/') ? '/public' . $item->product->primaryImage->url : $item->product->primaryImage->url }}" class="w-full h-full object-cover">
                                    @else
                                    <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    @endif
                                </div>
                                @endforeach
                                @if($order->items->count() > 3)
                                <span class="text-xs text-gray-400">+{{ $order->items->count() - 3 }} more</span>
                                @endif
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-base font-bold text-gray-900">₹{{ number_format($order->total_amount) }}</p>
                            @php $colors = ['pending'=>'yellow','confirmed'=>'blue','processing'=>'indigo','shipped'=>'purple','delivered'=>'green','cancelled'=>'red']; @endphp
                            <span class="inline-block mt-2 text-[10px] font-bold uppercase px-2.5 py-1 rounded-full bg-{{ $colors[$order->status] ?? 'gray' }}-100 text-{{ $colors[$order->status] ?? 'gray' }}-700">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                        </div>
                    </div>
                </a>
                @empty
                <div class="text-center py-20">
                    <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <p class="text-gray-500 text-lg">No orders yet</p>
                    <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 mt-4 px-6 py-3 bg-dark text-white font-semibold rounded-full hover:bg-dark-light transition">Start Shopping</a>
                </div>
                @endforelse
            </div>

            <div class="mt-8">{{ $orders->links() }}</div>
        </div>
    </div>
</div>
@endsection
