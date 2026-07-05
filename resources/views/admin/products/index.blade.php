@extends('layouts.admin')
@section('title', 'Products - Admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Products</h1>
            <p class="text-sm text-gray-500">Manage your product catalog</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="px-4 py-2.5 text-white" style="background-color:#c06d22 text-sm font-semibold rounded-xl hover:opacity-90 transition flex items-center gap-1.5">
            + Add Product
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-2xl border border-gray-200">
        <form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-col sm:flex-row items-center gap-4">
            <div class="relative flex-1 w-full">
                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products by name, SKU..." class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200">
            </div>
            <select name="category" class="px-3 py-2.5 border border-gray-200 rounded-xl text-sm">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <select name="status" class="px-3 py-2.5 border border-gray-200 rounded-xl text-sm">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
            </select>
            <button type="submit" class="px-4 py-2.5 bg-gray-100 text-gray-700 text-sm rounded-xl hover:bg-gray-200 transition">Filter</button>
        </form>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50">
                        <th class="text-left text-xs font-medium text-gray-500 uppercase px-6 py-3">Product</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase px-6 py-3">SKU</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase px-6 py-3">Category</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase px-6 py-3">Price</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase px-6 py-3">Stock</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase px-6 py-3">Status</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                    @if($product->primaryImage)
                                    <img src="{{ $product->primaryImage->url }}" alt="" class="w-full h-full object-cover rounded-lg">
                                    @else
                                    <span class="text-lg">🌿</span>
                                    @endif
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ Str::limit($product->name, 35) }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 font-mono">{{ $product->sku ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $product->category->name ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-semibold text-gray-900">₹{{ number_format($product->selling_price) }}</span>
                            <span class="text-xs text-gray-400 line-through ml-1">₹{{ number_format($product->mrp) }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium {{ $product->stock <= 0 ? 'text-red-600' : ($product->stock <= $product->low_stock_alert ? 'text-orange-600' : 'text-green-600') }}">
                            {{ $product->stock }}
                        </td>
                        <td class="px-6 py-4">
                            @if($product->stock <= 0)
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">Out of Stock</span>
                            @elseif($product->stock <= $product->low_stock_alert)
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-orange-100 text-orange-700">Low Stock</span>
                            @else
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">Active</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.products.edit', $product) }}" class="px-2.5 py-1.5 bg-blue-50 text-blue-600 text-xs font-medium rounded-lg hover:bg-blue-100 transition">Edit</a>
                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete this product?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-2.5 py-1.5 bg-red-50 text-red-600 text-xs font-medium rounded-lg hover:bg-red-100 transition">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-6 py-12 text-center text-gray-400">No products found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">{{ $products->links() }}</div>
    </div>
</div>
@endsection
