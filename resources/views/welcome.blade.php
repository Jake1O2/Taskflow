@extends('layouts.guest')

@section('content')
    <div class="text-center">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Bienvenue sur TaskFlow</h1>
        <p class="text-gray-600 mb-8">Gérez vos projets et tâches efficacement.</p>
        
        <div class="space-x-4">
            <a href="{{ route('login') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Connexion</a>
            <a href="{{ route('register') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Inscription</a>
        </div>
    </div>
@endsection
