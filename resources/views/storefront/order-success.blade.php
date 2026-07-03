@extends('layouts.app')
@section('title', 'Order Confirmed - Shivara')

@section('content')
<div class="max-w-xl mx-auto px-4 py-20 text-center">
    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
        <i data-lucide="check" class="w-10 h-10 text-green-600"></i>
    </div>
    <h1 class="text-2xl font-bold text-gray-900 mb-2">Order Confirmed!</h1>
    <p class="text-gray-500 mb-4">Your order has been placed successfully.</p>
    <div class="bg-white p-6 rounded-2xl border border-border mb-6">
        <p class="text-sm text-gray-500 mb-1">Order Number</p>
        <p class="text-xl font-bold text-primary">{{ $order->order_number }}</p>
        <p class="text-sm text-gray-500 mt-3">Total Amount</p>
        <p class="text-lg font-semibold text-gray-900">₹{{ number_format($order->total_amount) }}</p>
    </div>
    <a href="{{ route('products.index') }}" class="px-8 py-3 bg-primary text-white font-medium rounded-xl hover:bg-primary-dark transition inline-block">Continue Shopping</a>
</div>
@endsection
