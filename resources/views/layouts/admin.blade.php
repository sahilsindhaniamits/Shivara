<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - Shivara')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: {
            colors: { brand: { 50:'#fdf8f0',100:'#f9eddb',200:'#f5e6d0',300:'#e8c98a',400:'#d4a94e',500:'#B7925C',600:'#9a7840',700:'#7a5a30' }, dark: { DEFAULT:'#2C2418',light:'#3d3224' } },
            fontFamily: { sans: ['Inter','system-ui','sans-serif'] }
        }}}
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak]{display:none!important}body{font-family:'Inter',system-ui,sans-serif}</style>
</head>
<body class="bg-slate-50 min-h-screen" x-data="{ sidebar: true, mobileSidebar: false }">
<div class="flex">
    <!-- Sidebar -->
    <aside :class="sidebar ? 'w-64' : 'w-[72px]'" class="fixed inset-y-0 left-0 z-50 transition-all duration-300 hidden lg:block overflow-hidden" style="background-color:#2C2418">
        <div class="p-5 flex items-center gap-3 border-b border-white/10">
            <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 overflow-hidden bg-white">
                <img src="/public/shivaralogo1.png" alt="S" class="w-7 h-7 object-contain" onerror="this.style.display='none';this.parentElement.innerHTML='<span class=\'text-white font-extrabold text-sm\'>S</span>';this.parentElement.style.backgroundColor='#B7925C'">
            </div>
            <span x-show="sidebar" class="text-white font-bold text-sm tracking-wide">Shivara Admin</span>
        </div>
        <nav class="p-3 space-y-0.5 mt-2 overflow-y-auto" style="max-height: calc(100vh - 180px);">
            @php $nav = [
                ['route'=>'admin.dashboard','icon'=>'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6','label'=>'Dashboard'],
                ['route'=>'admin.products.index','icon'=>'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4','label'=>'Products'],
                ['route'=>'admin.categories.index','icon'=>'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z','label'=>'Categories'],
                ['route'=>'admin.orders.index','icon'=>'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z','label'=>'Orders'],
                ['route'=>'admin.coupons.index','icon'=>'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z','label'=>'Coupons'],
                ['route'=>'admin.banners.index','icon'=>'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z','label'=>'Banners'],
                ['route'=>'admin.customers.index','icon'=>'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z','label'=>'Customers'],
                ['route'=>'admin.free-gift.index','icon'=>'M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7','label'=>'Free Gift'],
                ['route'=>'admin.reviews.index','icon'=>'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z','label'=>'Reviews'],
                ['route'=>'admin.testimonials.index','icon'=>'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z','label'=>'Testimonials'],
                ['route'=>'admin.reports.index','icon'=>'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z','label'=>'Reports'],
                ['route'=>'admin.blogs.index','icon'=>'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z','label'=>'Blog'],
                ['route'=>'admin.marquee.index','icon'=>'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z','label'=>'Announcement'],
            ]; @endphp
            @foreach($nav as $item)
            <a href="{{ route($item['route']) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition {{ request()->routeIs(str_replace('.index','',$item['route']).'*') ? 'text-white font-semibold' : 'text-white/60 hover:text-white hover:bg-white/5' }}" @if(request()->routeIs(str_replace('.index','',$item['route']).'*')) style="background-color:#B7925C" @endif>
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $item['icon'] }}"/></svg>
                <span x-show="sidebar">{{ $item['label'] }}</span>
            </a>
            @endforeach
        </nav>
        <div class="absolute bottom-0 left-0 right-0 p-3 border-t border-white/10">
            <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-white/60 hover:text-white hover:bg-white/5 transition">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                <span x-show="sidebar">View Store</span>
            </a>
            <form method="POST" action="{{ route('logout') }}">@csrf
                <button type="submit" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-white/60 hover:text-red-400 hover:bg-white/5 transition w-full">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span x-show="sidebar">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main -->
    <div :class="sidebar ? 'lg:ml-64' : 'lg:ml-[72px]'" class="flex-1 transition-all duration-300 min-h-screen">
        <!-- Top Bar -->
        <header class="sticky top-0 z-40 bg-white/80 backdrop-blur-xl border-b border-slate-200/60">
            <div class="px-4 sm:px-6 py-3.5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <button @click="sidebar = !sidebar" class="hidden lg:block text-slate-400 hover:text-slate-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <button @click="mobileSidebar = true" class="lg:hidden text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <h2 class="text-sm font-semibold text-slate-700">@yield('page_title', 'Dashboard')</h2>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-sm text-slate-500 hidden sm:block">{{ auth()->user()->name }}</span>
                    <a href="{{ route('admin.profile') }}" class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold hover:ring-2 hover:ring-brand-300 transition cursor-pointer text-white" style="background-color:#B7925C" title="Profile Settings">{{ substr(auth()->user()->name, 0, 1) }}</a>
                </div>
            </div>
        </header>

        <!-- Content -->
        <main class="p-4 sm:p-6">
            @if(session('success'))
            <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm font-medium flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm font-medium">{{ session('error') }}</div>
            @endif
            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
