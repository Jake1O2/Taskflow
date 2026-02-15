@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm border border-gray-200 rounded-xl animate-fade-in">
                <div class="p-8">
                    <div class="flex justify-between items-center mb-6 border-b pb-6 border-gray-100">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Modifier le projet</h1>
                            <p class="text-gray-500 mt-1 text-sm">{{ $project->title }}</p>
                        </div>
                        <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-mono rounded-full">ID: {{ $project->id }}</span>
                    </div>

                    <form action="{{ route('projects.update', $project->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="space-y-2">
                            <label for="title" class="block text-sm font-semibold text-gray-700">Titre du projet</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $project->title) }}" 
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200 py-2.5" 
                                   required />
                            @error('title')
                                <p class="text-red-500 text-xs mt-1 flex items-center gap-1 animate-shake">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="description" class="block text-sm font-semibold text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="4"
                                      class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200 py-2.5">{{ old('description', $project->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1 flex items-center gap-1 animate-shake">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="start_date" class="block text-sm font-semibold text-gray-700">Date de début</label>
                                <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $project->start_date) }}" 
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200 py-2.5" />
                            </div>
                            <div class="space-y-2">
                                <label for="end_date" class="block text-sm font-semibold text-gray-700">Date de fin</label>
                                <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $project->end_date) }}" 
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200 py-2.5" />
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="status" class="block text-sm font-semibold text-gray-700">Statut</label>
                            <select name="status" id="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200 py-2.5">
                                <option value="preparation" {{ $project->status == 'preparation' ? 'selected' : '' }}>En préparation</option>
                                <option value="in_progress" {{ $project->status == 'in_progress' ? 'selected' : '' }}>En cours</option>
                                <option value="completed" {{ $project->status == 'completed' ? 'selected' : '' }}>Terminé</option>
                            </select>
                        </div>
                        
                         <!-- Team Selection -->
                        @if($teams->count() > 0)
                            <div class="space-y-2">
                                <label for="team_id" class="block text-sm font-semibold text-gray-700">Équipe associée</label>
                                <select name="team_id" id="team_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200 py-2.5">
                                    <option value="">Aucune équipe</option>
                                    @foreach($teams as $team)
                                            <option value="{{ $team->id }}" {{ $project->team_id == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="flex items-center justify-end gap-4 pt-6 mt-6 border-t border-gray-100">
                            <a href="{{ route('projects.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                                Annuler
                            </a>
                            <button type="submit" class="px-6 py-2 bg-blue-600 border border-transparent rounded-lg text-white font-bold tracking-wide hover:bg-blue-700 focus:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-md transform hover:-translate-y-0.5">
                                Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection