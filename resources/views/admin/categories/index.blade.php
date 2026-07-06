@extends('layouts.admin')
@section('page_title', 'Categories')
@section('title', 'Categories - Admin')

@section('content')
<div class="space-y-5">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Categories</h1>
            <p class="text-sm text-gray-500">{{ $categories->total() }} categories &bull; Organize your product catalog</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="px-5 py-2.5 text-white text-sm font-bold rounded-xl hover:opacity-90 transition flex items-center gap-2 shadow-sm" style="background-color:#c06d22">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Category
        </a>
    </div>

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($categories as $cat)
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition group">
            <!-- Category Image -->
            <div class="h-28 bg-gradient-to-br from-gray-100 to-gray-50 relative overflow-hidden">
                @if($cat->image)
                <img src="{{ str_starts_with($cat->image, '/storage/') ? '/public' . $cat->image : $cat->image }}" alt="{{ $cat->name }}" class="w-full h-full object-cover">
                @else
                <div class="w-full h-full flex items-center justify-center">
                    <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                </div>
                @endif
                <!-- Status Badge -->
                <span class="absolute top-3 right-3 text-[9px] font-bold uppercase px-2 py-0.5 rounded-md {{ $cat->is_active ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-600' }}">
                    {{ $cat->is_active ? 'Active' : 'Inactive' }}
                </span>
                <!-- Sort Order -->
                <span class="absolute top-3 left-3 text-[10px] font-bold bg-black/50 text-white px-2 py-0.5 rounded-md backdrop-blur-sm">
                    #{{ $cat->sort_order }}
                </span>
            </div>
            <!-- Category Info -->
            <div class="p-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-bold text-gray-900">{{ $cat->name }}</h3>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-orange-50 text-orange-700">{{ $cat->products_count }} products</span>
                </div>
                <p class="text-xs text-gray-400 font-mono mb-3">/{{ $cat->slug }}</p>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.categories.edit', $cat) }}" class="flex-1 px-3 py-2 text-center text-xs font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition">Edit</a>
                    <a href="{{ route('products.index', ['category' => $cat->slug]) }}" target="_blank" class="px-3 py-2 text-xs font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition">View</a>
                    @if($cat->products_count == 0)
                    <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" onsubmit="return confirm('Delete {{ addslashes($cat->name) }}?')">
                        @csrf @method('DELETE')
                        <button class="px-3 py-2 text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition">Delete</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-16">
            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
            </div>
            <p class="text-sm font-semibold text-gray-500">No categories yet</p>
            <a href="{{ route('admin.categories.create') }}" class="mt-3 inline-block px-4 py-2 text-white text-xs font-bold rounded-lg" style="background-color:#c06d22">+ Create First Category</a>
        </div>
        @endforelse
    </div>

    @if($categories->hasPages())
    <div class="flex justify-center">{{ $categories->links() }}</div>
    @endif
</div>
@endsection
