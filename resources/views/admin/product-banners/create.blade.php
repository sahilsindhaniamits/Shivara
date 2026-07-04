@extends('layouts.admin')
@section('page_title', 'Add Product Banner')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-xl font-bold text-slate-900 mb-6">Add Product Banner</h1>
    <form method="POST" action="{{ route('admin.product-banners.store') }}" enctype="multipart/form-data" class="bg-white p-6 rounded-2xl border border-slate-100 space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Title *</label>
            <input type="text" name="title" value="{{ old('title') }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:bg-white" placeholder="e.g. Summer Sale Banner">
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Upload Image * (recommended: 1200×400px)</label>
            <input type="file" name="image" required accept="image/*" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm">
            @error('image')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Link (optional)</label>
            <input type="text" name="link" value="{{ old('link') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:bg-white" placeholder="/products">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Sort Order</label>
                <input type="number" name="sort_order" value="0" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none">
            </div>
            <div class="flex items-end pb-1">
                <label class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1" checked class="rounded text-brand-600"><span class="text-sm text-slate-700">Active</span></label>
            </div>
        </div>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="px-6 py-3 bg-brand-600 text-white font-semibold rounded-xl hover:bg-brand-700 transition text-sm">Add Banner</button>
            <a href="{{ route('admin.product-banners.index') }}" class="px-6 py-3 border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 transition text-sm">Cancel</a>
        </div>
    </form>
</div>
@endsection
