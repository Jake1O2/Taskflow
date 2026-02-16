@extends('layouts.guest')

@section('content')
    <div class="glass-premium rounded-[40px] p-10 sm:p-14 animate-fade-in relative overflow-hidden group border border-white/10">
        {{-- Grain Texture Overlay --}}
        <div class="absolute inset-0 grain-texture pointer-events-none"></div>

        <div class="relative z-10">
            <div class="flex flex-col items-center mb-12 translate-y-6 animate-slide-in-up" style="animation-delay: 150ms; animation-fill-mode: both;">
                {{-- Futuristic Logo Icon --}}
                <div class="relative w-20 h-20 mb-8">
                    <div class="absolute inset-0 bg-blue-600/30 rounded-3xl blur-2xl animate-pulse"></div>
                    <div class="relative w-20 h-20 bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-700 rounded-[28px] flex items-center justify-center shadow-2xl shadow-blue-500/40 border border-white/20 transform hover:rotate-6 transition-transform duration-500">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
                
                <h2 class="text-4xl sm:text-5xl font-black tracking-tighter text-white leading-tight text-center mb-3">
                    Salut <span class="text-gradient-electric">encore.</span>
                </h2>
                <p class="text-gray-400 font-medium text-lg tracking-wide opacity-80">Ravi de vous revoir sur TaskFlow</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-500/10 backdrop-blur-xl border border-red-500/20 text-red-400 p-5 rounded-3xl mb-10 text-[15px] animate-fade-in flex items-start gap-4">
                    <div class="p-2 bg-red-500/20 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <ul class="list-none p-0 m-0">
                        @foreach ($errors->all() as $error)
                            <li class="font-bold">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-8">
                @csrf

                <div class="translate-y-6 animate-slide-in-up" style="animation-delay: 300ms; animation-fill-mode: both;">
                    <label for="email" class="block text-[14px] font-bold text-gray-300 mb-3 ml-2 tracking-wide uppercase opacity-70">Adresse Email</label>
                    <div class="relative group">
                        <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl blur opacity-0 group-focus-within:opacity-20 transition duration-500"></div>
                        <div class="relative bg-white/5 border border-white/10 rounded-2xl transition-all duration-300 group-focus-within:border-blue-500/50 group-focus-within:bg-white/10">
                            <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-gray-500 group-focus-within:text-blue-400 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                            </span>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                class="w-full pl-14 pr-6 py-5 text-[17px] bg-transparent text-white border-none focus:ring-0 placeholder-gray-600 transition-all font-semibold"
                                placeholder="nom@exemple.com">
                        </div>
                    </div>
                </div>

                <div class="translate-y-6 animate-slide-in-up" style="animation-delay: 450ms; animation-fill-mode: both;">
                    <div class="flex justify-between items-center mb-3 ml-2">
                        <label for="password" class="block text-[14px] font-bold text-gray-300 tracking-wide uppercase opacity-70">Mot de passe</label>
                    </div>
                    <div class="relative group">
                        <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl blur opacity-0 group-focus-within:opacity-20 transition duration-500"></div>
                        <div class="relative bg-white/5 border border-white/10 rounded-2xl transition-all duration-300 group-focus-within:border-blue-500/50 group-focus-within:bg-white/10">
                            <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-gray-500 group-focus-within:text-blue-400 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </span>
                            <input id="password" type="password" name="password" required
                                class="w-full pl-14 pr-6 py-5 text-[17px] bg-transparent text-white border-none focus:ring-0 placeholder-gray-600 transition-all font-semibold"
                                placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between translate-y-6 animate-slide-in-up" style="animation-delay: 600ms; animation-fill-mode: both;">
                    <label class="flex items-center cursor-pointer select-none group">
                        <div class="relative w-6 h-6">
                            <input type="checkbox" name="remember" class="peer absolute inset-0 opacity-0 cursor-pointer z-10">
                            <div class="w-6 h-6 border-2 border-white/20 rounded-lg group-hover:border-blue-500/50 transition-colors peer-checked:bg-blue-600 peer-checked:border-blue-600 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                        </div>
                        <span class="ml-3 text-[15px] font-bold text-gray-400 group-hover:text-gray-300 transition-colors tracking-wide">Rester connecté</span>
                    </label>
                </div>

                <div class="pt-6 translate-y-6 animate-slide-in-up" style="animation-delay: 750ms; animation-fill-mode: both;">
                    <button type="submit" 
                        class="w-full relative group overflow-hidden bg-blue-600 rounded-[20px] py-5 px-8 transition-all duration-500 active:scale-[0.97] shadow-2xl shadow-blue-500/40">
                        {{-- Shimmer Effect --}}
                        <div class="absolute inset-x-0 top-0 h-[100%] w-[200%] bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-[100%] group-hover:animate-shimmer transition-transform"></div>
                        
                        <span class="relative z-10 flex items-center justify-center gap-3 text-white text-[18px] font-black tracking-tight uppercase italic">
                            Se connecter
                            <svg class="w-6 h-6 group-hover:translate-x-2 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </span>
                    </button>
                </div>

                <div class="text-center pt-10 translate-y-6 animate-slide-in-up" style="animation-delay: 900ms; animation-fill-mode: both;">
                    <p class="text-[16px] font-bold text-gray-500 tracking-tight">
                        Pas encore membre ? 
                        <a href="{{ route('register') }}" class="text-white hover:text-blue-400 transition-all duration-300 relative group">
                            <span>Rejoindre l'aventure</span>
                            <span class="absolute bottom-0 left-0 w-full h-[1px] bg-blue-500/50 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></span>
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
@endsection
