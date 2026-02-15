@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm border border-gray-200 rounded-xl animate-fade-in">
                <div class="p-8">
                    <div class="border-b border-gray-100 pb-6 mb-6">
                        <h1 class="text-2xl font-bold text-gray-900">Créer un nouveau projet</h1>
                        <p class="text-gray-500 mt-2 text-sm">Définissez les objectifs et les délais de votre projet.</p>
                    </div>

                    <form action="{{ route('projects.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="space-y-2">
                            <label for="title" class="block text-sm font-semibold text-gray-700">Titre du projet</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" 
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200 py-2.5" 
                                   placeholder="Ex: Refonte du site web" required autofocus />
                            @error('title')
                                <p class="text-red-500 text-xs mt-1 flex items-center gap-1 animate-shake">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="description" class="block text-sm font-semibold text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="4"
                                      class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200 py-2.5"
                                      placeholder="Détails et objectifs du projet...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1 flex items-center gap-1 animate-shake">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="start_date" class="block text-sm font-semibold text-gray-700">Date de début</label>
                                <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" 
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200 py-2.5" />
                            </div>
                            <div class="space-y-2">
                                <label for="end_date" class="block text-sm font-semibold text-gray-700">Date de fin</label>
                                <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" 
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200 py-2.5" />
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="status" class="block text-sm font-semibold text-gray-700">Statut initial</label>
                            <select name="status" id="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200 py-2.5">
                                <option value="preparation">En préparation</option>
                                <option value="in_progress">En cours</option>
                                <option value="completed">Terminé</option>
                            </select>
                        </div>
                        
                        <!-- Team Selection -->
                        @if($teams->count() > 0)
                            <div class="space-y-2">
                                <label for="team_id" class="block text-sm font-semibold text-gray-700">Associer à une équipe (optionnel)</label>
                                <select name="team_id" id="team_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200 py-2.5">
                                    <option value="">Aucune équipe</option>
                                    @foreach($teams as $team)
                                        <option value="{{ $team->id }}">{{ $team->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="flex items-center justify-end gap-4 pt-6 mt-6 border-t border-gray-100">
                            <a href="{{ route('projects.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                                Annuler
                            </a>
                            <button type="submit" class="px-6 py-2 bg-blue-600 border border-transparent rounded-lg text-white font-bold tracking-wide hover:bg-blue-700 focus:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-md transform hover:-translate-y-0.5">
                                Créer le projet
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection