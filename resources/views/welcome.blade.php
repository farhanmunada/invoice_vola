<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $globalSetting->shop_name ?? config('app.name') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
    </style>
</head>
<body class="antialiased bg-slate-50 text-slate-800 selection:bg-indigo-500 selection:text-white overflow-x-hidden">

    <!-- Background Decoration -->
    <div class="fixed inset-0 pointer-events-none -z-10">
        <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-blue-400/10 rounded-full blur-3xl opacity-50 transform translate-x-1/3 -translate-y-1/3"></div>
        <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-indigo-400/10 rounded-full blur-3xl opacity-50 transform -translate-x-1/3 translate-y-1/3"></div>
    </div>

    <!-- Navigation -->
    <nav class="fixed w-full z-50 transition-all duration-300 glass-card border-b border-indigo-50/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center gap-3">
                    @if($globalSetting && $globalSetting->logo_path)
                        <img src="{{ asset('storage/' . $globalSetting->logo_path) }}" alt="Logo" class="h-10 w-auto rounded-xl shadow-sm">
                    @else
                        <div class="bg-gradient-to-br from-indigo-600 to-blue-600 p-2.5 rounded-xl text-white shadow-lg shadow-indigo-200">
                            <i data-lucide="printer" class="w-6 h-6"></i>
                        </div>
                    @endif
                    <span class="font-bold text-xl tracking-tight text-slate-900">{{ $globalSetting->shop_name ?? config('app.name') }}</span>
                </div>

                <!-- Auth Links -->
                <div class="flex items-center gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="group relative inline-flex items-center justify-center px-6 py-2.5 text-sm font-bold text-white transition-all bg-indigo-600 rounded-full hover:bg-indigo-700 hover:scale-105 hover:shadow-lg hover:shadow-indigo-500/30 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">
                                <span>Go to Dashboard</span>
                                <i data-lucide="arrow-right" class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="group relative inline-flex items-center justify-center px-8 py-3 text-sm font-bold text-white transition-all bg-indigo-600 rounded-full hover:bg-indigo-700 hover:scale-105 hover:shadow-xl hover:shadow-indigo-500/30 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">
                                <i data-lucide="log-in" class="w-4 h-4 mr-2"></i>
                                <span>Log In</span>
                            </a>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative pt-32 pb-20 sm:pt-48 sm:pb-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-4xl mx-auto">
                
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-indigo-100 text-indigo-600 text-sm font-bold shadow-sm mb-8 animate-fade-in-up">
                    <span class="relative flex h-3 w-3">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-indigo-500"></span>
                    </span>
                    The Ultimate Invoicing Solution
                </div>

                <h1 class="text-5xl sm:text-7xl font-extrabold text-slate-900 tracking-tight mb-8 leading-tight">
                    Manage Your Business <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-500">With Confidence</span>
                </h1>
                
                <p class="text-xl sm:text-2xl text-slate-500 max-w-2xl mx-auto mb-12 leading-relaxed">
                    Streamline receipts, track orders, and manage customers effortlessly. Built for speed and reliability.
                </p>

                <div class="flex flex-col sm:flex-row justify-center gap-4 mb-20">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white bg-indigo-600 rounded-2xl hover:bg-indigo-700 transition-all hover:scale-105 hover:shadow-xl hover:shadow-indigo-500/30 transform duration-200">
                            Access Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white bg-indigo-600 rounded-2xl hover:bg-indigo-700 transition-all hover:scale-105 hover:shadow-xl hover:shadow-indigo-500/30 transform duration-200 w-full sm:w-auto">
                            Login into System
                        </a>
                    @endauth
                </div>

                <!-- Features Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-left">
                    <!-- Feature 1 -->
                    <div class="glass-card p-8 rounded-3xl hover:-translate-y-2 transition-transform duration-300">
                        <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center mb-6 text-blue-600">
                            <i data-lucide="file-text" class="w-7 h-7"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Smart Invoicing</h3>
                        <p class="text-slate-500 leading-relaxed">Create professional invoices in seconds with automatic calculations and PDF generation.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="glass-card p-8 rounded-3xl hover:-translate-y-2 transition-transform duration-300">
                        <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center mb-6 text-indigo-600">
                            <i data-lucide="users" class="w-7 h-7"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Customer Management</h3>
                        <p class="text-slate-500 leading-relaxed">Keep track of your client database, order history, and contact details in one place.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="glass-card p-8 rounded-3xl hover:-translate-y-2 transition-transform duration-300">
                        <div class="w-14 h-14 bg-purple-50 rounded-2xl flex items-center justify-center mb-6 text-purple-600">
                            <i data-lucide="bar-chart-3" class="w-7 h-7"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Real-time Analytics</h3>
                        <p class="text-slate-500 leading-relaxed">Visualize your income and performance with intuitive, real-time dashboards.</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-100 py-12 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row justify-between items-center gap-6">
            <p class="text-slate-500 text-sm font-medium">
                &copy; {{ date('Y') }} {{ $globalSetting->shop_name ?? config('app.name') }}. Powered by Invoice Vola.
            </p>
            <div class="flex gap-6">
                <a href="#" class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors">
                    <i data-lucide="github" class="w-5 h-5"></i>
                </a>
                <a href="#" class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:text-blue-500 hover:bg-blue-50 transition-colors">
                    <i data-lucide="twitter" class="w-5 h-5"></i>
                </a>
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
