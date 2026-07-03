@extends('layouts.app')
@section('title', 'Wishlist - Shivara')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    <div class="flex flex-col md:flex-row gap-8">
        @include('storefront.account._sidebar')

        <div class="flex-1 min-w-0">
            <h1 class="text-2xl font-display font-bold text-gray-900 mb-6">My Wishlist</h1>

            @if($wishlistItems->count())
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                @foreach($wishlistItems as $item)
                <div class="relative">
                    @include('partials.product-card', ['product' => $item->product])
                    <form method="POST" action="{{ route('account.wishlist.remove', $item) }}" class="absolute top-3 right-3 z-10">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-8 h-8 bg-white rounded-full shadow-md flex items-center justify-center text-red-400 hover:text-red-600 hover:bg-red-50 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
            <div class="mt-8">{{ $wishlistItems->links() }}</div>
            @else
            <div class="text-center py-20">
                <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                <p class="text-gray-500 text-lg">Your wishlist is empty</p>
                <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 mt-4 px-6 py-3 bg-dark text-white font-semibold rounded-full hover:bg-dark-light transition">Browse Products</a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
