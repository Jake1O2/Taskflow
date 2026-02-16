@extends('layouts.guest')

@section('content')
    <h2 class="text-2xl font-bold mb-8 text-gray-900">Créer un compte</h2>
    
    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div class="space-y-2">
            <label class="text-sm font-bold text-gray-700 ml-1" for="name">Nom complet</label>
            <x-text-input id="name" type="text" name="name" placeholder="John Doe" :value="old('name')" required autofocus />
            @error('name')
                <p class="text-danger text-xs mt-1 font-semibold">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-2">
            <label class="text-sm font-bold text-gray-700 ml-1" for="email">Adresse email</label>
            <x-text-input id="email" type="email" name="email" placeholder="nom@exemple.com" :value="old('email')" required />
            @error('email')
                <p class="text-danger text-xs mt-1 font-semibold">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-2">
                <label class="text-sm font-bold text-gray-700 ml-1" for="password">Mot de passe</label>
                <x-text-input id="password" type="password" name="password" placeholder="••••••••" required />
            </div>
            <div class="space-y-2">
                <label class="text-sm font-bold text-gray-700 ml-1" for="password_confirmation">Confirmer</label>
                <x-text-input id="password_confirmation" type="password" name="password_confirmation" placeholder="••••••••" required />
            </div>
        </div>
        @error('password')
            <p class="text-danger text-xs mt-1 font-semibold">{{ $message }}</p>
        @enderror

        <div class="pt-6">
            <x-primary-button class="w-full justify-center">
                S'inscrire
            </x-primary-button>
        </div>

        <div class="text-center pt-2">
            <p class="text-sm text-gray-500 font-medium">
                Déjà un compte ? 
                <a href="{{ route('login') }}" class="text-primary font-bold hover:underline">Se connecter</a>
            </p>
        </div>
    </form>
@endsection