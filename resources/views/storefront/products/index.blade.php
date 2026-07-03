@extends('layouts.app')
@section('title', 'Products - Shivara')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <nav class="text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}">Home</a> / <span class="text-primary font-medium">All Products</span>
    </nav>

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar Filters -->
        <aside class="hidden lg:block w-64 shrink-0">
            <div class="sticky top-40 space-y-6">
                <div>
                    <h3 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
                        <i data-lucide="filter" class="w-4 h-4"></i> Categories
                    </h3>
                    <div class="space-y-2">
                        <a href="{{ route('products.index') }}" class="block w-full text-left px-3 py-2 rounded-lg text-sm transition {{ !request('category') ? 'bg-primary text-white font-medium' : 'text-gray-600 hover:bg-accent' }}">All</a>
                        @foreach($categories as $cat)
                        <a href="{{ route('products.index', ['category' => $cat->slug]) }}" class="block w-full text-left px-3 py-2 rounded-lg text-sm transition {{ request('category') == $cat->slug ? 'bg-primary text-white font-medium' : 'text-gray-600 hover:bg-accent' }}">{{ $cat->name }}</a>
                        @endforeach
                    </div>
                </div>

                <div>
                    <h3 class="font-semibold text-gray-800 mb-3">Price Range</h3>
                    <form method="GET" action="{{ route('products.index') }}" class="space-y-3">
                        @if(request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
                        <div class="flex items-center gap-2">
                            <input type="number" name="min_price" placeholder="Min" value="{{ request('min_price') }}" class="w-full px-3 py-2 border border-border rounded-lg text-sm">
                            <span class="text-gray-400">-</span>
                            <input type="number" name="max_price" placeholder="Max" value="{{ request('max_price') }}" class="w-full px-3 py-2 border border-border rounded-lg text-sm">
                        </div>
                        <button type="submit" class="w-full px-4 py-2 border border-secondary text-secondary text-sm hover:bg-secondary hover:text-white transition rounded-lg">Apply</button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Products -->
        <div class="flex-1">
            <div class="flex items-center justify-between mb-6 bg-white p-4 rounded-xl border border-border">
                <p class="text-sm text-gray-500">Showing <span class="font-semibold text-gray-800">{{ $products->total() }}</span> products</p>
                <form method="GET" action="{{ route('products.index') }}">
                    @if(request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
                    @if(request('search'))<input type="hidden" name="search" value="{{ request('search') }}">@endif
                    <select name="sort" onchange="this.form.submit()" class="px-3 py-2 border border-border rounded-lg text-sm text-gray-700 focus:outline-none">
                        <option value="featured" {{ request('sort') == 'featured' ? 'selected' : '' }}>Featured</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                    </select>
                </form>
            </div>

            @if(request('search'))
            <div class="mb-4 text-sm text-gray-600">Search results for: <strong>"{{ request('search') }}"</strong></div>
            @endif

            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-6">
                @forelse($products as $product)
                    @include('partials.product-card', ['product' => $product])
                @empty
                    <div class="col-span-3 text-center py-16">
                        <p class="text-muted text-lg">No products found.</p>
                        <a href="{{ route('products.index') }}" class="text-primary text-sm mt-2 inline-block">Clear filters</a>
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
