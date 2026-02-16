@extends('layouts.guest')

@section('content')
    <div class="glass-premium rounded-[40px] p-12 sm:p-16 animate-fade-in relative overflow-hidden group border border-white/10 text-center">
        {{-- Grain Texture Overlay --}}
        <div class="absolute inset-0 grain-texture pointer-events-none"></div>

        <div class="relative z-10">
            <div class="flex flex-col items-center mb-10 translate-y-6 animate-slide-in-up" style="animation-delay: 150ms; animation-fill-mode: both;">
                {{-- Futuristic Brand Mark --}}
                <div class="relative w-24 h-24 mb-10">
                    <div class="absolute inset-0 bg-blue-600/30 rounded-[32px] blur-3xl animate-pulse"></div>
                    <div class="relative w-24 h-24 bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-700 rounded-[30px] flex items-center justify-center shadow-2xl shadow-blue-500/40 border border-white/20 transform hover:scale-110 transition-transform duration-700">
                        <svg class="w-14 h-14 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
                
                <h1 class="text-5xl sm:text-6xl font-black tracking-tighter text-white leading-tight mb-4">
                    Maîtrisez votre <br><span class="text-gradient-electric">flux.</span>
                </h1>
                <p class="text-gray-400 font-medium text-lg tracking-wide opacity-80 max-w-sm mx-auto">
                    Gérez vos projets et collaborez avec une fluidité sans précédent.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-5 pt-8 translate-y-6 animate-slide-in-up" style="animation-delay: 400ms; animation-fill-mode: both;">
                <a href="{{ route('login') }}" 
                    class="relative group overflow-hidden bg-blue-600 rounded-[24px] py-5 px-8 transition-all duration-500 active:scale-[0.97] shadow-2xl shadow-blue-500/40">
                    {{-- Shimmer Effect --}}
                    <div class="absolute inset-x-0 top-0 h-[100%] w-[200%] bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-[100%] group-hover:animate-shimmer transition-transform"></div>
                    
                    <span class="relative z-10 flex items-center justify-center gap-3 text-white text-[18px] font-black tracking-tight uppercase italic">
                        Connexion
                        <svg class="w-6 h-6 group-hover:translate-x-2 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l4-4m0 0l-4-4m4 4H9m6 4a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </span>
                </a>

                <a href="{{ route('register') }}" 
                    class="relative group overflow-hidden bg-white/5 border border-white/10 hover:bg-white/10 rounded-[24px] py-5 px-8 transition-all duration-500 active:scale-[0.97]">
                    <span class="relative z-10 flex items-center justify-center gap-3 text-gray-300 group-hover:text-white text-[18px] font-black tracking-tight uppercase italic transition-colors">
                        Rejoindre l'aventure
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    </span>
                </a>
            </div>

            <div class="mt-12 pt-8 border-t border-white/5 opacity-50 translate-y-6 animate-slide-in-up" style="animation-delay: 600ms; animation-fill-mode: both;">
                <p class="text-[13px] font-bold text-gray-500 tracking-widest uppercase">
                    Propulsé par <span class="text-blue-500/80">TaskFlow Engine v4.0</span>
                </p>
            </div>
        </div>
    </div>
@endsection
