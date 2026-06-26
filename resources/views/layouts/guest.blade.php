<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Statify') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-100 antialiased bg-gray-900">
        
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-cover bg-center relative" style="background-image: url('{{ asset('images/statify_hero.png') }}');">
            <!-- Dark Overlay -->
            <div class="absolute inset-0 bg-gray-900/80 backdrop-blur-sm z-0"></div>
            
            <div class="relative z-10 mb-8 mt-10 sm:mt-0 text-center">
                <a href="/">
                    <div class="inline-block bg-gray-900/50 p-4 rounded-full border border-gray-700/50 backdrop-blur-md shadow-[0_0_20px_rgba(52,211,153,0.3)] hover:shadow-[0_0_30px_rgba(52,211,153,0.6)] transition-all">
                        <svg class="w-12 h-12 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                </a>
                <h2 class="mt-4 text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-emerald-400 tracking-wider">Statify Secure</h2>
            </div>

            <div class="relative z-10 w-full sm:max-w-md px-8 py-8 bg-gray-800/80 backdrop-blur-xl shadow-2xl border border-gray-700/60 overflow-hidden sm:rounded-2xl dark:text-gray-300">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
