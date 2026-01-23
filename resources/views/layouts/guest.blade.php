<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-slate-50 relative">
        <!-- Background Decoration -->
        <div class="fixed inset-0 pointer-events-none -z-10 overflow-hidden">
            <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-blue-400/10 rounded-full blur-3xl opacity-50 transform translate-x-1/3 -translate-y-1/3"></div>
            <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-indigo-400/10 rounded-full blur-3xl opacity-50 transform -translate-x-1/3 translate-y-1/3"></div>
        </div>

        <!-- Lucide Icons -->
        <script src="https://unpkg.com/lucide@latest"></script>

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative z-10">
            <div class="mb-8 text-center">
                <div class="flex justify-center mb-6">
                    @if($globalSetting->logo_path)
                        <img src="{{ asset('storage/' . $globalSetting->logo_path) }}" alt="Logo" class="h-16 w-auto object-contain rounded-xl shadow-sm">
                    @else
                        <div class="bg-gradient-to-br from-indigo-600 to-blue-600 p-3.5 rounded-2xl shadow-xl shadow-indigo-200">
                            <i data-lucide="printer" class="w-10 h-10 text-white"></i>
                        </div>
                    @endif
                </div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $globalSetting->shop_name ?? config('app.name') }}</h1>
                <p class="text-slate-500 mt-2 font-medium">Sign in to manage your invoices</p>
            </div>

            <div class="w-full sm:max-w-md mt-2 px-8 py-10 bg-white shadow-2xl overflow-hidden rounded-3xl border border-white/50 ring-1 ring-slate-900/5">
                {{ $slot }}
            </div>
            
            <script>
                lucide.createIcons();
            </script>
        </div>
    </body>
</html>
