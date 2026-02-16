@extends('layouts.guest')

@section('content')
    <h2 class="text-2xl font-bold mb-8 text-gray-900">Bienvenue</h2>
    
    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf
        
        <div class="space-y-2">
            <label class="text-sm font-bold text-gray-700 ml-1" for="email">Adresse email</label>
            <x-text-input id="email" type="email" name="email" placeholder="nom@exemple.com" :value="old('email')" required autofocus />
            @error('email')
                <p class="text-danger text-xs mt-1 font-semibold">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-2">
            <div class="flex justify-between items-center px-1">
                <label class="text-sm font-bold text-gray-700" for="password">Mot de passe</label>
                {{-- Add password reset link here if needed --}}
            </div>
            <x-text-input id="password" type="password" name="password" placeholder="••••••••" required />
            @error('password')
                <p class="text-danger text-xs mt-1 font-semibold">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-2 px-1">
            <input type="checkbox" name="remember" id="remember" class="rounded-lg border-gray-200 text-primary focus:ring-primary/20">
            <label for="remember" class="text-xs font-bold text-gray-500 uppercase tracking-widest cursor-pointer select-none">Se souvenir de moi</label>
        </div>

        <div class="pt-4">
            <x-primary-button class="w-full justify-center">
                Se connecter
            </x-primary-button>
        </div>

        <div class="text-center pt-2">
            <p class="text-sm text-gray-500 font-medium">
                Pas encore de compte ? 
                <a href="{{ route('register') }}" class="text-primary font-bold hover:underline">Créer un compte</a>
            </p>
        </div>
    </form>
@endsection