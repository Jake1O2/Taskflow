@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 animate-slide-up">
        <header>
            <h1 class="text-3xl font-bold text-gray-900">Modifier l'équipe</h1>
            <p class="text-gray-500 font-medium mt-1 text-sm italic">ID: {{ $team->id }}</p>
        </header>

        <div class="card-premium">
            <form action="{{ route('teams.update', $team->id) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                <div class="space-y-3">
                    <label for="name" class="text-sm font-bold text-gray-700 ml-1">Nom de l'équipe</label>
                    <x-text-input type="text" name="name" id="name" value="{{ old('name', $team->name) }}" required />
                    @error('name')
                        <p class="text-danger text-xs mt-2 px-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-50">
                    <a href="{{ route('teams.index') }}" class="px-5 py-2.5 text-gray-500 hover:text-gray-900 font-bold text-sm transition-colors">
                        Annuler
                    </a>
                    <x-primary-button>
                        Enregistrer les modifications
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
@endsection
