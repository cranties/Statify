<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Statify - Server Monitoring</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased bg-gray-900 text-white font-sans overflow-x-hidden">
    <div class="relative w-full h-screen flex flex-col items-center justify-center bg-zinc-900 bg-cover bg-center"
        style="background-image: url('{{ asset('images/statify_hero.png') }}');">
        <!-- Overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/80 to-transparent"></div>

        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto flex flex-col items-center">
            <div class="mb-4 inline-block">
                <x-application-logo class="block fill-current text-gray-200" style="height: 6rem;" />
            </div>
            <h1
                class="text-5xl md:text-7xl font-extrabold tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-emerald-400 drop-shadow-lg mb-6">
                Statify
            </h1>
            <p class="text-xl md:text-2xl text-gray-300 font-light mb-10 max-w-2xl">
                The ultimate heartbeat monitor for your infrastructure. Keep track of servers, web endpoints, and
                databases in real-time.
            </p>

            <div class="flex space-x-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="px-8 py-4 bg-emerald-500 hover:bg-emerald-400 text-white font-bold rounded-full transition transform hover:scale-105 shadow-lg shadow-emerald-500/50">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="px-8 py-4 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-full transition transform hover:scale-105 shadow-lg shadow-blue-600/50 backdrop-blur-sm">
                            Secure Login
                        </a>
                    @endauth
                @endif
            </div>
        </div>

        <!-- Animated stats decorative -->
        <div class="absolute bottom-10 left-10 hidden md:block">
            <div
                class="bg-gray-800/60 backdrop-blur-md border border-gray-700/50 p-4 rounded-xl flex items-center space-x-3 shadow-xl">
                <div class="relative flex h-3 w-3">
                    <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                </div>
                <span class="text-gray-200 font-mono text-sm tracking-widest uppercase">System Operational</span>
            </div>
        </div>
    </div>
</body>

</html>
