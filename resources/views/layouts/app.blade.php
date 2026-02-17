<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TaskFlow') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- Scripts and Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-sans bg-gray-50 text-gray-900">
        <div class="min-h-screen relative">
            <!-- Decorative Background Elements -->
            <div class="fixed inset-0 pointer-events-none overflow-hidden -z-10">
                <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-blue-100/40 rounded-full blur-[120px]"></div>
                <div class="absolute top-[20%] -right-[10%] w-[35%] h-[35%] bg-purple-100/30 rounded-full blur-[120px]"></div>
            </div>

            <!-- Floating Navbar -->
            <nav class="sticky top-4 z-50 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="glass rounded-2xl shadow-premium border border-white/40 flex items-center justify-between h-16 px-6">
                    <!-- Logo Area -->
                    <div class="flex items-center gap-8">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 group">
                            <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="font-bold text-xl tracking-tight text-gray-900">TaskFlow</span>
                        </a>

                        <!-- Desktop Navigation -->
                        <div class="hidden md:flex items-center gap-1">
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Tableau de bord</x-nav-link>
                            <x-nav-link :href="route('projects.index')" :active="request()->routeIs('projects.*')">Projets</x-nav-link>
                            <x-nav-link :href="route('teams.index')" :active="request()->routeIs('teams.*')">Équipes</x-nav-link>
                            <x-nav-link :href="route('notifications.index')" :active="request()->routeIs('notifications.*')">Notifications</x-nav-link>
                        </div>
                    </div>

                    <!-- Right Area: Search & Profile -->
                    <div class="flex items-center gap-4">
                        <!-- Refined Search Bar -->
                        <div class="hidden sm:flex items-center w-64 group relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400 group-focus-within:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <form action="{{ route('search') }}" method="GET" class="w-full">
                                <input type="text" name="q" placeholder="Rechercher..." 
                                       class="block w-full pl-10 pr-3 py-2 bg-gray-100/50 border-none rounded-xl text-sm placeholder-gray-500 focus:bg-white focus:ring-2 focus:ring-primary/20 transition-all shadow-inner"
                                       value="{{ request('q') }}">
                            </form>
                        </div>

                        <!-- Notifications Bell -->
                        <div class="relative group mr-2">
                            <a href="{{ route('notifications.index') }}" class="p-2 text-gray-400 hover:text-primary hover:bg-primary/5 rounded-xl transition-all duration-200 flex items-center justify-center relative">
                                <svg class="w-6 h-6 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                
                                {{-- Notification Badge --}}
                                @php $unreadCount = Auth::user()->unreadNotifications->count(); @endphp
                                @if($unreadCount > 0)
                                    <span class="badge-red animate-pulse-custom">
                                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                    </span>
                                @endif
                            </a>
                        </div>

                        <!-- Profile Dropdown -->
                        <div class="relative" id="profile-dropdown">
                            <button type="button" class="flex items-center gap-3 pl-4 border-l border-gray-200/50 group focus:outline-none" id="profile-button">
                                <span class="hidden lg:block text-sm font-bold text-gray-700 group-hover:text-primary transition-colors">{{ Auth::user()->name }}</span>
                                <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center border border-primary/20 text-primary font-bold text-xs group-hover:bg-primary group-hover:text-white transition-all">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            </button>

                            <!-- Dropdown Menu -->
                            <div class="hidden absolute right-0 mt-3 w-56 bg-white border border-gray-100 rounded-2xl shadow-xl z-[60] py-2 animate-slide-up" id="dropdown-menu">
                                <div class="px-4 py-2 border-b border-gray-50 mb-1">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Compte</p>
                                    <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->email }}</p>
                                </div>
                                
                                <a href="{{ route('profile') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-primary transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    Mon compte
                                </a>
                                <a href="{{ route('billing.index') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-primary transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                    Facturation
                                </a>
                                <a href="{{ route('api.tokens.index') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-primary transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                                    Tokens API
                                </a>
                                <a href="{{ route('webhooks.index') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-primary transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    Webhooks
                                </a>

                                <div class="h-px bg-gray-50 my-2"></div>
                                
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-500 hover:bg-red-50 transition-colors text-left font-bold">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                        Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="animate-fade-in py-8">
                @yield('content')
            </main>

            <!-- Refined Footer -->
            <footer class="py-12 mt-auto border-t border-gray-100 bg-white/50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center gap-4">
                    <span class="font-bold text-gray-900">TaskFlow</span>
                    <p class="text-gray-500 text-sm italic">Simplifiez votre gestion de projet.</p>
                    <div class="text-xs text-gray-400 mt-4">&copy; {{ date('Y') }} TaskFlow. Tous droits réservés.</div>
                </div>
            </footer>
        </div>

        <!-- Dropdown Script -->
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const button = document.getElementById('profile-button');
                const menu = document.getElementById('dropdown-menu');

                if (button && menu) {
                    button.addEventListener('click', (e) => {
                        e.stopPropagation();
                        menu.classList.toggle('hidden');
                    });

                    document.addEventListener('click', () => {
                        menu.classList.add('hidden');
                    });

                    menu.addEventListener('click', (e) => {
                        e.stopPropagation();
                    });
                }
            });
        </script>
    </body>
</html>