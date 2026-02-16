@extends('layouts.guest')

@section('content')
    <div class="glass-premium rounded-[40px] p-10 sm:p-14 animate-fade-in relative overflow-hidden group border border-white/10">
        {{-- Grain Texture Overlay --}}
        <div class="absolute inset-0 grain-texture pointer-events-none"></div>

        <div class="relative z-10">
            <div class="flex flex-col items-center mb-10 translate-y-6 animate-slide-up" style="animation-delay: 150ms; animation-fill-mode: both;">
                {{-- Futuristic Logo Icon --}}
                <div class="relative w-16 h-16 mb-6">
                    <div class="absolute inset-0 bg-blue-600/30 rounded-3xl blur-2xl animate-pulse"></div>
                    <div class="relative w-16 h-16 bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-700 rounded-[20px] flex items-center justify-center shadow-2xl shadow-blue-500/40 border border-white/20 transform hover:-rotate-6 transition-transform duration-500">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                    </div>
                </div>
                
                <h2 class="text-3xl sm:text-4xl font-black tracking-tighter text-white leading-tight text-center mb-2">
                    Rejoindre l'<span class="text-gradient-electric">aventure.</span>
                </h2>
                <p class="text-gray-400 font-medium text-base tracking-wide opacity-80">Créez votre compte TaskFlow</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-500/10 backdrop-blur-xl border border-red-500/20 text-red-400 p-4 rounded-3xl mb-8 text-[14px] animate-fade-in flex items-start gap-3">
                    <div class="p-1.5 bg-red-500/20 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <ul class="list-none p-0 m-0">
                        @foreach ($errors->all() as $error)
                            <li class="font-semibold">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <div class="translate-y-6 animate-slide-up" style="animation-delay: 300ms; animation-fill-mode: both;">
                    <label for="name" class="block text-[13px] font-bold text-gray-300 mb-2 ml-2 tracking-wide uppercase opacity-70">Identité</label>
                    <div class="relative group">
                        <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl blur opacity-0 group-focus-within:opacity-20 transition duration-500"></div>
                        <div class="relative bg-white/5 border border-white/10 rounded-2xl transition-all duration-300 group-focus-within:border-blue-500/50 group-focus-within:bg-white/10">
                            <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-gray-500 group-focus-within:text-blue-400 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </span>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                                class="w-full pl-12 pr-6 py-4 text-[16px] bg-transparent text-white border-none focus:ring-0 placeholder-gray-600 transition-all font-medium"
                                placeholder="Votre nom complet">
                        </div>
                    </div>
                </div>

                <div class="translate-y-6 animate-slide-up" style="animation-delay: 400ms; animation-fill-mode: both;">
                    <label for="email" class="block text-[13px] font-bold text-gray-300 mb-2 ml-2 tracking-wide uppercase opacity-70">Adresse Email</label>
                    <div class="relative group">
                        <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl blur opacity-0 group-focus-within:opacity-20 transition duration-500"></div>
                        <div class="relative bg-white/5 border border-white/10 rounded-2xl transition-all duration-300 group-focus-within:border-blue-500/50 group-focus-within:bg-white/10">
                            <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-gray-500 group-focus-within:text-blue-400 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </span>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                class="w-full pl-12 pr-6 py-4 text-[16px] bg-transparent text-white border-none focus:ring-0 placeholder-gray-600 transition-all font-medium"
                                placeholder="votre@email.com">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 translate-y-6 animate-slide-in-up" style="animation-delay: 500ms; animation-fill-mode: both;">
                    <div>
                        <label for="password" class="block text-[13px] font-bold text-gray-300 mb-2 ml-2 tracking-wide uppercase opacity-70">Clé d'accès</label>
                        <div class="relative group">
                            <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl blur opacity-0 group-focus-within:opacity-20 transition duration-500"></div>
                            <div class="relative bg-white/5 border border-white/10 rounded-2xl transition-all duration-300 group-focus-within:border-blue-500/50 group-focus-within:bg-white/10">
                                <input id="password" type="password" name="password" required
                                    class="w-full px-6 py-4 text-[16px] bg-transparent text-white border-none focus:ring-0 placeholder-gray-600 transition-all font-medium"
                                    placeholder="••••••••">
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-[13px] font-bold text-gray-300 mb-2 ml-2 tracking-wide uppercase opacity-70">Confirmation</label>
                        <div class="relative group">
                            <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl blur opacity-0 group-focus-within:opacity-20 transition duration-500"></div>
                            <div class="relative bg-white/5 border border-white/10 rounded-2xl transition-all duration-300 group-focus-within:border-blue-500/50 group-focus-within:bg-white/10">
                                <input id="password_confirmation" type="password" name="password_confirmation" required
                                    class="w-full px-6 py-4 text-[16px] bg-transparent text-white border-none focus:ring-0 placeholder-gray-600 transition-all font-medium"
                                    placeholder="••••••••">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-6 translate-y-6 animate-slide-in-up" style="animation-delay: 600ms; animation-fill-mode: both;">
                    <button type="submit" 
                        class="w-full relative group overflow-hidden bg-blue-600 rounded-[20px] py-4 px-8 transition-all duration-500 active:scale-[0.97] shadow-2xl shadow-blue-500/40">
                        <div class="absolute inset-x-0 top-0 h-[100%] w-[200%] bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-[100%] group-hover:animate-shimmer transition-transform"></div>
                        
                        <span class="relative z-10 flex items-center justify-center gap-2 text-white text-[16px] font-black tracking-tight uppercase italic">
                            Commencer l'aventure
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </span>
                    </button>
                </div>

                <div class="text-center pt-8 translate-y-6 animate-slide-in-up" style="animation-delay: 700ms; animation-fill-mode: both;">
                    <p class="text-[15px] font-bold text-gray-500 tracking-tight">
                        Déjà parmi nous ? 
                        <a href="{{ route('login') }}" class="text-white hover:text-blue-400 transition-all duration-300 relative group">
                            <span>Connexion</span>
                            <span class="absolute bottom-0 left-0 w-full h-[1px] bg-blue-500/50 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></span>
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
@endsection
