<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900 bg-gray-50">
        <!-- Lucide Icons -->
        <script src="https://unpkg.com/lucide@latest"></script>

        <div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">
            
            <!-- Mobile Overlay -->
            <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-40 lg:hidden"></div>

            <!-- Sidebar -->
            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'" class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transform transition-transform duration-300 ease-in-out lg:static lg:flex flex-col print:hidden">
                <!-- Logo -->
                <div class="h-16 flex items-center justify-between px-6 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        @if(isset($globalSetting) && $globalSetting->logo_path)
                            <img src="{{ asset('storage/' . $globalSetting->logo_path) }}" alt="Logo" class="h-8 w-auto object-contain">
                        @else
                            <i data-lucide="printer" class="h-8 w-8 text-blue-600"></i>
                        @endif
                        <span class="text-xl font-bold text-gray-800 tracking-tight">
                            {{ $globalSetting->shop_name ?? config('app.name') }}
                        </span>
                    </div>
                    <!-- Close Button (Mobile) -->
                    <button @click="sidebarOpen = false" class="lg:hidden text-gray-500 hover:text-gray-700">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>

                <!-- Nav -->
                <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                        Dashboard
                    </a>
                    
                    <div class="pt-4 pb-2">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-3">Main Menu</span>
                    </div>

                    <a href="{{ route('invoices.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('invoices.*') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i data-lucide="file-text" class="w-5 h-5"></i>
                        Invoices
                    </a>
                    
                    <a href="{{ route('customers.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('customers.*') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i data-lucide="users" class="w-5 h-5"></i>
                        Customers
                    </a>

                    <a href="{{ route('reports.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('reports.*') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
                        Reports
                    </a>

                    <div class="pt-4 pb-2">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-3">System</span>
                    </div>

                    <a href="{{ route('settings.edit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('settings.*') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i data-lucide="settings" class="w-5 h-5"></i>
                        Shop Settings
                    </a>
                </nav>

                <!-- User Footer -->
                <div class="p-4 border-t border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="text-gray-400 hover:text-red-500 transition" title="Logout">
                                <i data-lucide="log-out" class="w-5 h-5"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Mobile Header -->
                <header class="bg-white border-b border-gray-200 min-h-[4rem] flex items-center justify-between px-4 lg:hidden print:hidden">
                    <div class="flex-1 mr-4">
                        @isset($header)
                            {{ $header }}
                        @endisset
                    </div>
                    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-gray-700 focus:outline-none flex-shrink-0">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                </header>

                <!-- Scrollable Content -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                    @isset($header)
                        <div class="mb-6 hidden lg:block">
                            {{ $header }}
                        </div>
                    @endisset

                    {{ $slot }}
                </main>
            </div>
        </div>
        
        <script>
            lucide.createIcons();
        </script>
    </body>
</html>
