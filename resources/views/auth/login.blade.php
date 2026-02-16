@extends('layouts.guest')

@section('content')
    <div class="glass-premium rounded-[24px] p-8 sm:p-12 animate-fade-in relative overflow-hidden group">
        {{-- Decorative light reflection --}}
        <div class="absolute -top-24 -left-24 w-48 h-48 bg-white/10 rounded-full blur-3xl group-hover:bg-white/20 transition-all duration-700"></div>

        <div class="flex flex-col items-center mb-10 translate-y-4 animate-slide-in-up" style="animation-delay: 100ms; animation-fill-mode: both;">
            {{-- Modern Logo Icon --}}
            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl flex items-center justify-center mb-6 shadow-xl shadow-blue-500/30">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <h2 class="text-[32px] font-black tracking-tight text-gray-900 leading-tight text-center">
                Bon retour
            </h2>
            <p class="text-gray-500 font-medium mt-2">Accédez à votre espace TaskFlow</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50/50 backdrop-blur-md border border-red-200/50 text-red-600 p-4 rounded-xl mb-8 text-[14px] animate-fade-in">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <ul class="list-none p-0 m-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <div class="translate-y-4 animate-slide-in-up" style="animation-delay: 200ms; animation-fill-mode: both;">
                <label for="email" class="block text-[14px] font-bold text-gray-700 mb-2 ml-1">Email</label>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400 group-focus-within:text-blue-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                    </span>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full pl-12 pr-4 py-4 text-[16px] bg-white/50 border border-gray-200 rounded-2xl focus:outline-none focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-300 placeholder-gray-400"
                        placeholder="votre@email.com">
                </div>
            </div>

            <div class="translate-y-4 animate-slide-in-up" style="animation-delay: 300ms; animation-fill-mode: both;">
                <label for="password" class="block text-[14px] font-bold text-gray-700 mb-2 ml-1">Mot de passe</label>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400 group-focus-within:text-blue-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </span>
                    <input id="password" type="password" name="password" required
                        class="w-full pl-12 pr-4 py-4 text-[16px] bg-white/50 border border-gray-200 rounded-2xl focus:outline-none focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-300 placeholder-gray-400"
                        placeholder="••••••••">
                </div>
            </div>

            <div class="flex items-center justify-between translate-y-4 animate-slide-in-up" style="animation-delay: 400ms; animation-fill-mode: both;">
                <label class="flex items-center cursor-pointer select-none">
                    <input type="checkbox" name="remember" class="w-5 h-5 rounded-lg border-gray-200 text-blue-600 focus:ring-blue-500/20 transition-all">
                    <span class="ml-3 text-sm font-semibold text-gray-600">Se souvenir</span>
                </label>
                {{-- Add Forgot Password link here --}}
            </div>

            <div class="pt-4 translate-y-4 animate-slide-in-up" style="animation-delay: 500ms; animation-fill-mode: both;">
                <button type="submit" 
                    class="w-full relative group overflow-hidden bg-blue-600 text-white font-bold py-4 rounded-2xl hover:bg-blue-700 active:scale-[0.98] transition-all duration-300 shadow-xl shadow-blue-500/25">
                    <span class="relative z-10 flex items-center justify-center gap-2">
                        Connexion
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                    </span>
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-400 to-blue-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </button>
            </div>

            <div class="text-center pt-8 translate-y-4 animate-slide-in-up" style="animation-delay: 600ms; animation-fill-mode: both;">
                <p class="text-[15px] font-medium text-gray-500">
                    Nouveau chez TaskFlow ? 
                    <a href="{{ route('register') }}" class="text-blue-600 font-bold hover:text-blue-700 transition-colors underline-offset-4 hover:underline">
                        Créer un compte
                    </a>
                </p>
            </div>
        </form>
    </div>
@endsection
