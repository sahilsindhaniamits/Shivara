@extends('layouts.app')
@section('title', 'Products - Shivara')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-brand-600 transition">Home</a>
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-900 font-medium">Products</span>
        @if(request('category'))
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-brand-600 font-medium capitalize">{{ request('category') }}</span>
        @endif
    </nav>

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar Filters (Desktop) -->
        <aside class="hidden lg:block w-60 shrink-0">
            <div class="sticky top-24 space-y-8">
                <!-- Categories -->
                <div>
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Categories</h3>
                    <div class="space-y-1">
                        <a href="{{ route('products.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-sm transition {{ !request('category') ? 'bg-brand-50 text-brand-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
                            <span>All Products</span>
                            @if(!request('category'))<span class="w-1.5 h-1.5 bg-brand-500 rounded-full"></span>@endif
                        </a>
                        @foreach($categories as $cat)
                        <a href="{{ route('products.index', ['category' => $cat->slug]) }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-sm transition {{ request('category') == $cat->slug ? 'bg-brand-50 text-brand-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
                            <span>{{ $cat->name }}</span>
                            @if(request('category') == $cat->slug)<span class="w-1.5 h-1.5 bg-brand-500 rounded-full"></span>@endif
                        </a>
                        @endforeach
                    </div>
                </div>

                <!-- Price Range -->
                <div>
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Price Range</h3>
                    <form method="GET" action="{{ route('products.index') }}" class="space-y-3">
                        @if(request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
                        @if(request('sort'))<input type="hidden" name="sort" value="{{ request('sort') }}">@endif
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" name="min_price" placeholder="₹ Min" value="{{ request('min_price') }}" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:border-brand-300">
                            <input type="number" name="max_price" placeholder="₹ Max" value="{{ request('max_price') }}" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:border-brand-300">
                        </div>
                        <button type="submit" class="w-full px-4 py-2.5 bg-gray-900 text-white text-xs font-semibold uppercase tracking-wider rounded-lg hover:bg-gray-800 transition">Apply Filter</button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 min-w-0">
            <!-- Mobile Filter + Sort Bar -->
            <div class="flex items-center justify-between gap-4 mb-6 p-4 bg-white rounded-xl border border-gray-100 shadow-sm">
                <p class="text-sm text-gray-500">
                    <span class="font-semibold text-gray-900">{{ $products->total() }}</span> products
                </p>
                <div class="flex items-center gap-3">
                    <!-- Mobile Filter Toggle -->
                    <div x-data="{ open: false }" class="lg:hidden relative">
                        <button @click="open = !open" class="flex items-center gap-1.5 px-3 py-2 border border-gray-200 rounded-lg text-sm text-gray-700 hover:border-gray-300 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                            Filter
                        </button>
                        <!-- Mobile filter dropdown -->
                        <div x-show="open" @click.outside="open = false" x-cloak class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-2xl border border-gray-100 p-4 z-50">
                            <h4 class="font-semibold text-sm mb-3">Categories</h4>
                            <div class="space-y-1 max-h-48 overflow-y-auto">
                                <a href="{{ route('products.index') }}" class="block px-3 py-2 rounded-lg text-sm {{ !request('category') ? 'bg-brand-50 text-brand-700 font-medium' : 'text-gray-600' }}">All</a>
                                @foreach($categories as $cat)
                                <a href="{{ route('products.index', ['category' => $cat->slug]) }}" class="block px-3 py-2 rounded-lg text-sm {{ request('category') == $cat->slug ? 'bg-brand-50 text-brand-700 font-medium' : 'text-gray-600' }}">{{ $cat->name }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Sort -->
                    <form method="GET" action="{{ route('products.index') }}">
                        @if(request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
                        @if(request('search'))<input type="hidden" name="search" value="{{ request('search') }}">@endif
                        <select name="sort" onchange="this.form.submit()" class="px-3 py-2 border border-gray-200 rounded-lg text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-brand-200 cursor-pointer">
                            <option value="featured" {{ request('sort') == 'featured' ? 'selected' : '' }}>Featured</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low → High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High → Low</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                        </select>
                    </form>
                </div>
            </div>

            @if(request('search'))
            <div class="mb-5 flex items-center gap-2 text-sm">
                <span class="text-gray-500">Results for:</span>
                <span class="font-semibold text-gray-900">"{{ request('search') }}"</span>
                <a href="{{ route('products.index') }}" class="ml-2 text-brand-600 hover:underline text-xs">Clear</a>
            </div>
            @endif

            <!-- Product Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-5">
                @forelse($products as $product)
                    @include('partials.product-card', ['product' => $product])
                @empty
                    <div class="col-span-full py-20 text-center">
                        <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        <p class="text-gray-500 text-lg">No products found.</p>
                        <a href="{{ route('products.index') }}" class="text-brand-600 text-sm font-medium mt-2 inline-block hover:underline">Clear filters →</a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-10">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
