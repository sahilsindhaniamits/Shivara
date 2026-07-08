@extends('layouts.admin')
@section('title', 'Announcement Bar - Admin')
@section('page_title', 'Announcement Bar')

@section('content')
<div class="max-w-3xl space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Announcement Marquee</h1>
        <p class="text-sm text-gray-500 mt-1">Edit the scrolling announcement bar at the top of the website.</p>
    </div>

    <form method="POST" action="{{ route('admin.marquee.update') }}" class="space-y-6">
        @csrf

        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-5">
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Announcement Text</label>
                <textarea name="marquee_text" rows="4" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-300 transition" placeholder="Free Shipping Pan-India,10% Off — Code: WOW10,100% Natural Ayurvedic">{{ \App\Models\Setting::get('marquee_text', 'Free Shipping Pan-India,10% Off — Code: WOW10,100% Natural Ayurvedic,GMP Certified Lab Tested') }}</textarea>
                <p class="text-[10px] text-gray-400 mt-1.5">Separate each announcement with a <strong>comma (,)</strong>. Each item will scroll with a ✦ separator.</p>
            </div>
        </div>

        <button type="submit" class="px-8 py-3 text-white font-bold text-sm rounded-xl hover:opacity-90 transition shadow-sm" style="background-color:#c06d22">Save Changes</button>
    </form>
</div>
@endsection
