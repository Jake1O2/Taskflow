<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'TaskFlow') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-700">
    <nav class="bg-gradient-to-r from-blue-600 to-blue-800 shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo & Links -->
                <div class="flex items-center">
                    <div class="shrink-0 flex items-center">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 group">
                            <span class="text-2xl pt-1 transform group-hover:scale-110 transition-transform duration-300">✅</span>
                            <span class="font-bold text-xl text-white tracking-tight group-hover:text-blue-100 transition-colors">TaskFlow</span>
                        </a>
                    </div>
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white/90 hover:text-white hover:bg-white/10 px-3 py-2 rounded-md text-sm font-medium transition-all">
                            Dashboard
                        </x-nav-link>
                        <x-nav-link :href="route('projects.index')" :active="request()->routeIs('projects.*')" class="text-white/90 hover:text-white hover:bg-white/10 px-3 py-2 rounded-md text-sm font-medium transition-all">
                            Projets
                        </x-nav-link>
                        <x-nav-link :href="route('teams.index')" :active="request()->routeIs('teams.*')" class="text-white/90 hover:text-white hover:bg-white/10 px-3 py-2 rounded-md text-sm font-medium transition-all">
                            Équipes
                        </x-nav-link>
                    </div>
                </div>

                <!-- Search Bar -->
                <div class="hidden sm:flex items-center flex-1 max-w-md mx-8">
                    <form action="{{ route('search') }}" method="GET" class="w-full relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-400 group-focus-within:text-blue-500 transition-colors">🔍</span>
                        </div>
                        <input type="text" name="q" placeholder="Rechercher projets, tâches..."
                               class="block w-full pl-10 pr-3 py-2 border-none rounded-full leading-5 bg-white/10 text-white placeholder-white/70 focus:outline-none focus:bg-white focus:text-gray-900 focus:placeholder-gray-500 focus:ring-2 focus:ring-white/50 sm:text-sm transition-all duration-300 shadow-inner"
                               value="{{ request('q') }}">
                    </form>
                </div>

                <!-- Profile & Logout -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('profile') }}" class="text-white/90 hover:text-white font-medium text-sm transition-colors flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-xs font-bold ring-2 ring-transparent hover:ring-white/50 transition-all">
                            {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                        </div>
                        <span class="hidden md:inline">{{ Auth::user()->name }}</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-red-100 hover:text-red-50 hover:bg-red-600/20 px-3 py-2 rounded-md text-sm font-medium transition-all">
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 min-h-screen animate-fade">
        @yield('content')
    </main>

    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 flex flex-col items-center">
            <div class="flex space-x-6 mb-4">
                <a href="#" class="text-gray-400 hover:text-gray-500">
                    <span class="sr-only">GitHub</span>
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
            <p class="text-center text-gray-400 text-sm">&copy; {{ date('Y') }} TaskFlow. Design by Antigravity.</p>
        </div>
    </footer>
</body>
</html>