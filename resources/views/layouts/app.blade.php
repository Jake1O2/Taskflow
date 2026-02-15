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
    <nav class="bg-gradient-to-br from-blue-600 to-blue-800 shadow-xl sticky top-0 z-50 h-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full">
            <div class="flex justify-between items-center h-full">
                <!-- Logo & Links -->
                <div class="flex items-center gap-8">
                    <div class="shrink-0 flex items-center">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 group">
                            <span class="text-2xl pt-1 transform group-hover:scale-110 transition-transform duration-300 drop-shadow-md">✅</span>
                            <span class="font-bold text-[24px] text-white tracking-tighter group-hover:text-blue-100 transition-colors drop-shadow-sm">TaskFlow</span>
                        </a>
                    </div>
                    <div class="hidden space-x-1 sm:flex">
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white/80 hover:text-white hover:bg-white/10 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200">
                            Dashboard
                        </x-nav-link>
                        <x-nav-link :href="route('projects.index')" :active="request()->routeIs('projects.*')" class="text-white/80 hover:text-white hover:bg-white/10 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200">
                            Projets
                        </x-nav-link>
                        <x-nav-link :href="route('teams.index')" :active="request()->routeIs('teams.*')" class="text-white/80 hover:text-white hover:bg-white/10 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200">
                            Équipes
                        </x-nav-link>
                    </div>
                </div>

                <!-- Search Bar -->
                <div class="hidden sm:flex items-center flex-1 max-w-md mx-8">
                    <form action="{{ route('search') }}" method="GET" class="w-full relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-white/60 group-focus-within:text-white transition-colors">🔍</span>
                        </div>
                        <input type="text" name="q" placeholder="Rechercher..."
                               class="block w-full pl-10 pr-3 py-2 rounded-lg border border-white/20 bg-white/10 text-white placeholder-white/60 focus:outline-none focus:bg-white/15 focus:border-white/30 focus:ring-0 sm:text-sm transition-all duration-300"
                               value="{{ request('q') }}">
                    </form>
                </div>

                <!-- Profile & Logout -->
                <div class="flex items-center gap-4">
                    <div class="relative group">
                        <button class="flex items-center gap-2 text-white/90 hover:text-white transition-colors focus:outline-none">
                            <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-xs font-bold ring-2 ring-transparent group-hover:ring-white/30 transition-all">
                                {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                            </div>
                            <span class="hidden md:inline font-medium text-sm">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4 text-white/70 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <!-- Dropdown (Simplified implementation for visual, requires Alpine or JS for real toggle) -->
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-1 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top-right z-50">
                            <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Mon Profil</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Déconnexion</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 min-h-screen animate-fade">
        @yield('content')
    </main>

    <footer class="bg-gray-900 text-white py-10 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center">
            <div class="flex space-x-6 mb-6">
                <a href="#" class="text-gray-400 hover:text-white transition-opacity duration-300 hover:opacity-100">
                    <span class="sr-only">GitHub</span>
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
            <p class="text-center text-gray-500 text-xs opacity-70 hover:opacity-100 transition-opacity duration-300">&copy; {{ date('Y') }} TaskFlow. Design by Antigravity.</p>
        </div>
    </footer>
</body>
</html>