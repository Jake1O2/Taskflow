@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-100 animate-slide-down">
                <div class="p-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2 border-b pb-4 border-gray-100">Créer un projet</h1>
                    <p class="text-gray-500 mb-6 text-sm">Définissez les objectifs et les délais de votre nouveau projet.</p>

                    <form action="{{ route('projects.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Titre du projet</label>
                            <x-text-input type="text" name="title" id="title" value="{{ old('title') }}" class="w-full" placeholder="Ex: Refonte du site web" required autofocus />
                            @error('title')
                                <p class="text-red-500 text-xs mt-1 flex items-center gap-1 animate-shake">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" id="description" rows="4"
                                      class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm transition-all duration-200 focus:scale-[1.01]"
                                      placeholder="Détails du projet...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1 flex items-center gap-1 animate-shake">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Date de début</label>
                                <x-text-input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" class="w-full" />
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Date de fin</label>
                                <x-text-input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" class="w-full" />
                            </div>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                            <select name="status" id="status" class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm transition-all duration-200">
                                <option value="preparation">En préparation</option>
                                <option value="in_progress">En cours</option>
                                <option value="completed">Terminé</option>
                            </select>
                        </div>
                        
                        <!-- Team Selection -->
                        @if($teams->count() > 0)
                            <div>
                                <label for="team_id" class="block text-sm font-medium text-gray-700 mb-1">Associer à une équipe (optionnel)</label>
                                <select name="team_id" id="team_id" class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm transition-all duration-200">
                                    <option value="">Aucune équipe</option>
                                    @foreach($teams as $team)
                                        <option value="{{ $team->id }}">{{ $team->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100">
                            <a href="{{ route('projects.index') }}" class="text-gray-600 hover:text-gray-900 font-medium text-sm transition-colors">
                                Annuler
                            </a>
                            <x-primary-button>
                                Créer le projet
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection