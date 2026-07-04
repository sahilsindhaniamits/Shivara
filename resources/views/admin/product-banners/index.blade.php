@extends('layouts.admin')
@section('page_title', 'Product Banners')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Product Banners</h1>
            <p class="text-sm text-slate-500">These banners appear as a slider on product detail pages (above "Why Choose Shivara").</p>
        </div>
        <a href="{{ route('admin.product-banners.create') }}" class="px-4 py-2.5 bg-brand-600 text-white text-sm font-semibold rounded-xl hover:bg-brand-700 transition">+ Add Banner</a>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($banners as $banner)
        <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
            <div class="aspect-[3/1] bg-slate-100">
                <img src="{{ $banner->image }}" alt="{{ $banner->title }}" class="w-full h-full object-cover">
            </div>
            <div class="p-4 flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-slate-900">{{ $banner->title }}</p>
                    <span class="text-[10px] font-bold uppercase {{ $banner->is_active ? 'text-green-600' : 'text-red-500' }}">{{ $banner->is_active ? 'Active' : 'Inactive' }}</span>
                </div>
                <form method="POST" action="{{ route('admin.product-banners.destroy', $banner) }}" onsubmit="return confirm('Delete?')">
                    @csrf @method('DELETE')
                    <button class="text-xs text-red-500 font-medium hover:underline">Delete</button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12 text-slate-400">
            <p>No product banners yet.</p>
            <p class="text-xs mt-1">Add banners to show a slider on product pages.</p>
        </div>
        @endforelse
    </div>
    <div>{{ $banners->links() }}</div>
</div>
@endsection
