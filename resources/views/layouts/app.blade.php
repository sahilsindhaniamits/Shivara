<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('shivara.name') . ' - ' . config('shivara.tagline'))</title>
    <meta name="description" content="@yield('meta_description', config('shivara.description'))">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CDN (for fast loading, replace with Vite build for production) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { DEFAULT: '#B7925C', light: '#C9A46C', dark: '#96743A' },
                        secondary: { DEFAULT: '#2C2C2C', light: '#4A4A4A' },
                        accent: '#F5F0E8',
                        background: '#F7F4EF',
                        surface: '#FFFFFF',
                        border: '#E8E3DB',
                        muted: '#9B9B9B',
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                        serif: ['Cormorant Garamond', 'Georgia', 'serif'],
                    },
                }
            }
        }
    </script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>

    <style>
        body { background: #F7F4EF; font-family: 'Inter', sans-serif; }
        .font-editorial { font-family: 'Cormorant Garamond', serif; letter-spacing: -0.02em; }
        .section-label { font-size: 0.75rem; font-weight: 500; letter-spacing: 0.2em; text-transform: uppercase; color: #B7925C; }
        @keyframes marquee { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
        .animate-marquee { animation: marquee 30s linear infinite; }
        .product-card:hover { box-shadow: 0 8px 30px rgba(0,0,0,0.08); transform: translateY(-2px); }
        .product-card { transition: all 0.4s cubic-bezier(0.25,0.46,0.45,0.94); }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #F5F0E8; }
        ::-webkit-scrollbar-thumb { background: #B7925C; border-radius: 3px; }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen flex flex-col antialiased">
    @include('partials.header')

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="bg-green-50 border-b border-green-200 px-4 py-3 text-center text-sm text-green-700" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)">
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="bg-red-50 border-b border-red-200 px-4 py-3 text-center text-sm text-red-700">
        {{ session('error') }}
    </div>
    @endif

    <main class="flex-1">
        @yield('content')
    </main>

    @include('partials.footer')

    <!-- Alpine.js for interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>lucide.createIcons();</script>
    @stack('scripts')
</body>
</html>
