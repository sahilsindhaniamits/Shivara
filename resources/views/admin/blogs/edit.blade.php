@extends('layouts.admin')
@section('title', 'Edit Blog Post - Admin')
@section('page_title', 'Edit Blog Post')

@section('content')
<div class="max-w-4xl">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit: {{ Str::limit($blog->title, 40) }}</h1>
        <a href="{{ route('admin.blogs.index') }}" class="px-4 py-2 border border-gray-200 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-50 transition">Back</a>
    </div>

    <form method="POST" action="{{ route('admin.blogs.update', $blog) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf @method('PUT')

        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4">
            <h2 class="text-base font-bold text-gray-900 pb-3 border-b border-gray-100">Post Content</h2>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Title *</label>
                <input type="text" name="title" value="{{ old('title', $blog->title) }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Excerpt</label>
                <textarea name="excerpt" rows="2" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">{{ old('excerpt', $blog->excerpt) }}</textarea>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Content *</label>
                <textarea name="content" id="blogContent" rows="12" required class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">{{ old('content', $blog->content) }}</textarea>
            </div>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Category</label>
                    <input type="text" name="category" value="{{ old('category', $blog->category) }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Tags</label>
                    <input type="text" name="tags" value="{{ old('tags', $blog->tags) }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Featured Image</label>
                @if($blog->featured_image)
                <div class="mb-2"><img src="{{ str_starts_with($blog->featured_image, '/storage/') ? '/public' . $blog->featured_image : $blog->featured_image }}" class="w-32 h-20 object-cover rounded-lg border"></div>
                @endif
                <input type="file" name="featured_image" accept="image/*" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm">
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4">
            <h2 class="text-base font-bold text-gray-900 pb-3 border-b border-gray-100">SEO Settings</h2>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Meta Title</label>
                <input type="text" name="meta_title" value="{{ old('meta_title', $blog->meta_title) }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Meta Description</label>
                <textarea name="meta_description" rows="2" maxlength="160" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition">{{ old('meta_description', $blog->meta_description) }}</textarea>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2.5 cursor-pointer">
                <input type="checkbox" name="is_published" value="1" {{ $blog->is_published ? 'checked' : '' }} class="w-5 h-5 rounded border-gray-300 text-orange-500 focus:ring-orange-200">
                <span class="text-sm font-medium text-gray-700">Published</span>
            </label>
            <div class="flex gap-3">
                <a href="{{ route('admin.blogs.index') }}" class="px-5 py-3 border border-gray-200 text-gray-600 font-medium text-sm rounded-xl hover:bg-gray-50 transition">Cancel</a>
                <button type="submit" class="px-8 py-3 text-white font-bold text-sm rounded-xl hover:opacity-90 transition shadow-sm" style="background-color:#c06d22">Update Post</button>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
tinymce.init({
    selector: '#blogContent',
    height: 400,
    menubar: true,
    plugins: 'lists link image table code fullscreen preview wordcount',
    toolbar: 'undo redo | blocks | bold italic underline strikethrough | alignleft aligncenter alignright | bullist numlist | link image | table | code fullscreen',
    block_formats: 'Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3; Heading 4=h4; Quote=blockquote',
    content_style: 'body { font-family: DM Sans, system-ui, sans-serif; font-size: 14px; line-height: 1.6; }',
    branding: false,
    promotion: false,
});
</script>
@endsection
