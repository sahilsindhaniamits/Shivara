<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - Shivara')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: {
                colors: { primary: { DEFAULT: '#B7925C', light: '#C9A46C', dark: '#96743A' } },
                fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] },
            }}
        }
    </script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 min-h-screen" x-data="{ sidebarOpen: true }">
    <div class="flex">
        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'w-64' : 'w-20'" class="fixed inset-y-0 left-0 bg-white border-r border-gray-200 z-50 transition-all duration-300 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-primary rounded-xl flex items-center justify-center text-white font-bold text-sm">S</div>
                    <span x-show="sidebarOpen" class="font-bold text-gray-900">Shivara Admin</span>
                </a>
            </div>
            <nav class="p-4 space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm {{ request()->routeIs('admin.dashboard') ? 'bg-primary/10 text-primary font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5 shrink-0"></i>
                    <span x-show="sidebarOpen">Dashboard</span>
                </a>
                <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm {{ request()->routeIs('admin.products.*') ? 'bg-primary/10 text-primary font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i data-lucide="package" class="w-5 h-5 shrink-0"></i>
                    <span x-show="sidebarOpen">Products</span>
                </a>
                <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm {{ request()->routeIs('admin.orders.*') ? 'bg-primary/10 text-primary font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i data-lucide="shopping-cart" class="w-5 h-5 shrink-0"></i>
                    <span x-show="sidebarOpen">Orders</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-600 hover:bg-gray-50">
                    <i data-lucide="users" class="w-5 h-5 shrink-0"></i>
                    <span x-show="sidebarOpen">Customers</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-600 hover:bg-gray-50">
                    <i data-lucide="tag" class="w-5 h-5 shrink-0"></i>
                    <span x-show="sidebarOpen">Coupons</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-600 hover:bg-gray-50">
                    <i data-lucide="image" class="w-5 h-5 shrink-0"></i>
                    <span x-show="sidebarOpen">Banners</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-600 hover:bg-gray-50">
                    <i data-lucide="settings" class="w-5 h-5 shrink-0"></i>
                    <span x-show="sidebarOpen">Settings</span>
                </a>
            </nav>
            <div class="absolute bottom-4 left-0 right-0 px-4">
                <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-600 hover:bg-gray-50">
                    <i data-lucide="external-link" class="w-5 h-5 shrink-0"></i>
                    <span x-show="sidebarOpen">View Store</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-600 hover:bg-gray-50 w-full">
                        <i data-lucide="log-out" class="w-5 h-5 shrink-0"></i>
                        <span x-show="sidebarOpen">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div :class="sidebarOpen ? 'ml-64' : 'ml-20'" class="flex-1 transition-all duration-300">
            <!-- Top bar -->
            <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-sm border-b border-gray-200">
                <div class="px-6 py-4 flex items-center justify-between">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-gray-700">
                        <i data-lucide="menu" class="w-5 h-5"></i>
                    </button>
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-600">{{ auth()->user()->name }}</span>
                        <div class="w-8 h-8 bg-primary/10 rounded-full flex items-center justify-center text-primary text-sm font-semibold">{{ substr(auth()->user()->name, 0, 1) }}</div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-6">
                @if(session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">{{ session('error') }}</div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
    <script>lucide.createIcons();</script>
    @stack('scripts')
</body>
</html>
