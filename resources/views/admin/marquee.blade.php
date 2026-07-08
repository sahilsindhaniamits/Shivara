@extends('layouts.admin')
@section('title', 'Announcement Bar - Admin')
@section('page_title', 'Announcement Bar')

@section('content')
<div class="max-w-3xl space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Announcement Marquee</h1>
        <p class="text-sm text-gray-500 mt-1">Edit the scrolling announcement bar at the top of the website.</p>
    </div>

    <!-- Preview -->
    <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm">
        <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Preview:</p>
        <div class="text-white overflow-hidden rounded-xl" style="background-color: {{ \App\Models\Setting::get('marquee_bg_color', 'rgb(183, 146, 92)') }};">
            <div class="flex py-2.5">
                <div class="animate-marquee flex items-center gap-8 whitespace-nowrap text-[11px] tracking-[0.2em] uppercase font-medium">
                    @php $previewItems = array_filter(array_map('trim', explode(',', \App\Models\Setting::get('marquee_text', 'Free Shipping Pan-India,10% Off — Code: WOW10')))); @endphp
                    @foreach($previewItems as $pi)
                    <span>{{ $pi }}</span><span class="opacity-60">✦</span>
                    @endforeach
                    @foreach($previewItems as $pi)
                    <span>{{ $pi }}</span><span class="opacity-60">✦</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.marquee.update') }}" class="space-y-6">
        @csrf

        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-5">
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Announcement Text</label>
                <textarea name="marquee_text" rows="4" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition" placeholder="Free Shipping Pan-India,10% Off — Code: WOW10,100% Natural Ayurvedic">{{ \App\Models\Setting::get('marquee_text', 'Free Shipping Pan-India,10% Off — Code: WOW10,100% Natural Ayurvedic,GMP Certified Lab Tested') }}</textarea>
                <p class="text-[10px] text-gray-400 mt-1.5">Separate each announcement with a <strong>comma (,)</strong>. Each item will scroll with a ✦ separator.</p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Background Color</label>
                <div class="flex items-center gap-3">
                    <input type="color" name="marquee_bg_color_picker" value="#B7925C" onchange="document.getElementById('bgColorText').value=this.value" class="w-10 h-10 rounded-lg border border-gray-200 cursor-pointer">
                    <input type="text" name="marquee_bg_color" id="bgColorText" value="{{ \App\Models\Setting::get('marquee_bg_color', 'rgb(183, 146, 92)') }}" class="flex-1 px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition" placeholder="#B7925C or rgb(183, 146, 92)">
                </div>
                <p class="text-[10px] text-gray-400 mt-1.5">Use hex (#B7925C) or rgb format. This is the background color of the top bar.</p>
            </div>
        </div>

        <button type="submit" class="px-8 py-3 text-white font-bold text-sm rounded-xl hover:opacity-90 transition shadow-sm" style="background-color:#c06d22">Save Changes</button>
    </form>
</div>
@endsection
