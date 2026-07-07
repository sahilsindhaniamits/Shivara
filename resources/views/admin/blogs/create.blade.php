@extends('layouts.admin')
@section('title', 'New Blog Post - Admin')
@section('page_title', 'New Blog Post')

@section('content')
<div class="max-w-4xl">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Create Blog Post</h1>
        <a href="{{ route('admin.blogs.index') }}" class="px-4 py-2 border border-gray-200 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-50 transition">Back</a>
    </div>

    <form method="POST" action="{{ route('admin.blogs.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        @if($errors->any())
        <div class="px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
            <p class="font-semibold mb-1">Please fix:</p>
            <ul class="list-disc pl-5 space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4">
            <h2 class="text-base font-bold text-gray-900 pb-3 border-b border-gray-100">Post Content</h2>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Title *</label>
                <input type="text" name="title" value="{{ old('title') }}" required placeholder="e.g. 10 Ayurvedic Herbs for Better Digestion" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Excerpt <span class="text-gray-400">(short summary for cards)</span></label>
                <textarea name="excerpt" rows="2" placeholder="Brief 1-2 sentence summary..." class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">{{ old('excerpt') }}</textarea>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Content *</label>
                <div id="quillEditor"></div>
                <textarea name="content" id="blogContent" class="hidden">{{ old('content') }}</textarea>
            </div>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Category</label>
                    <input type="text" name="category" value="{{ old('category') }}" placeholder="e.g. Ayurveda, Health Tips, Recipes" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Tags <span class="text-gray-400">(comma-separated)</span></label>
                    <input type="text" name="tags" value="{{ old('tags') }}" placeholder="ayurveda, health, herbs" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Featured Image</label>
                <input type="file" name="featured_image" accept="image/*" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm">
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4">
            <h2 class="text-base font-bold text-gray-900 pb-3 border-b border-gray-100">SEO Settings</h2>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Meta Title <span class="text-gray-400">(for Google)</span></label>
                <input type="text" name="meta_title" value="{{ old('meta_title') }}" placeholder="Leave blank to use post title" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Meta Description <span class="text-gray-400">(155 chars max)</span></label>
                <textarea name="meta_description" rows="2" maxlength="160" placeholder="Brief description for search engine results..." class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">{{ old('meta_description') }}</textarea>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2.5 cursor-pointer">
                <input type="checkbox" name="is_published" value="1" checked class="w-5 h-5 rounded border-gray-300 text-orange-500 focus:ring-orange-200">
                <span class="text-sm font-medium text-gray-700">Publish immediately</span>
            </label>
            <div class="flex gap-3">
                <a href="{{ route('admin.blogs.index') }}" class="px-5 py-3 border border-gray-200 text-gray-600 font-medium text-sm rounded-xl hover:bg-gray-50 transition">Cancel</a>
                <button type="submit" class="px-8 py-3 text-white font-bold text-sm rounded-xl hover:opacity-90 transition shadow-sm" style="background-color:#c06d22">Publish Post</button>
            </div>
        </div>
    </form>
</div>

<!-- Quill.js Rich Text Editor (Free - No API Key) -->
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<style>.ql-editor { min-height: 300px; font-size: 14px; line-height: 1.7; } .ql-toolbar { border-radius: 12px 12px 0 0; border-color: #e5e7eb; } .ql-container { border-radius: 0 0 12px 12px; border-color: #e5e7eb; }</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var quill = new Quill('#quillEditor', {
        theme: 'snow',
        placeholder: 'Write your blog content here...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, 4, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'align': [] }],
                ['link', 'image', 'blockquote'],
                ['clean']
            ]
        }
    });

    // Load existing content if any (for edit page or validation redirect)
    var existing = document.getElementById('blogContent').value;
    if (existing && existing.trim() !== '') quill.root.innerHTML = existing;

    // Sync Quill content to hidden textarea on ANY form submission
    var forms = document.querySelectorAll('form');
    forms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            var content = quill.root.innerHTML;
            if (content === '<p><br></p>' || content.trim() === '') content = '';
            document.getElementById('blogContent').value = content;
        });
    });
});
</script>
@endsection
