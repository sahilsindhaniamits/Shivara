@extends('layouts.admin')
@section('page_title', 'Add Banner')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-xl font-bold text-slate-900 mb-6">Add Banner</h1>
    <form method="POST" action="{{ route('admin.banners.store') }}" enctype="multipart/form-data" class="bg-white p-6 rounded-2xl border border-slate-100 space-y-4">
        @csrf
        <div><label class="block text-sm font-medium text-slate-700 mb-1">Title <span class="text-slate-400">(optional)</span></label><input type="text" name="title" value="{{ old('title') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:bg-white" placeholder="Leave blank for image-only banner"></div>
        <div><label class="block text-sm font-medium text-slate-700 mb-1">Subtitle <span class="text-slate-400">(optional)</span></label><input type="text" name="subtitle" value="{{ old('subtitle') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:bg-white"></div>
        <div><label class="block text-sm font-medium text-slate-700 mb-1">Link URL <span class="text-slate-400">(optional)</span></label><input type="text" name="link" value="{{ old('link') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-200 focus:bg-white" placeholder="https:// or /products"></div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div><label class="block text-sm font-medium text-slate-700 mb-1">Image * <span class="text-slate-400 text-xs">(jpg, png, webp)</span></label><input type="file" name="image" required accept="image/jpeg,image/png,image/webp,image/gif" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm"></div>
            <div><label class="block text-sm font-medium text-slate-700 mb-1">Mobile Image <span class="text-slate-400 text-xs">(optional)</span></label><input type="file" name="mobile_image" accept="image/jpeg,image/png,image/webp,image/gif" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm"></div>
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div><label class="block text-sm font-medium text-slate-700 mb-1">Sort Order</label><input type="number" name="sort_order" value="0" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none"></div>
            <div class="flex items-end pb-1"><label class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1" checked class="rounded text-brand-600"><span class="text-sm text-slate-700">Active</span></label></div>
        </div>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="px-6 py-3 text-white font-semibold rounded-xl hover:opacity-90 transition text-sm" style="background-color:#c06d22">Create</button>
            <a href="{{ route('admin.banners.index') }}" class="px-6 py-3 border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 transition text-sm">Cancel</a>
        </div>
    </form>
</div>
@endsection
