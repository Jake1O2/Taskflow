@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 animate-slide-up">
        <header>
            <h1 class="text-3xl font-bold text-gray-900">Modifier la tâche</h1>
            <p class="text-gray-500 font-medium mt-1 italic text-sm">ID: {{ $task->id }}</p>
        </header>

        <div class="card-premium overflow-hidden">
            <form action="{{ route('tasks.update', $task->id) }}" method="POST" class="space-y-8 p-1">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <!-- Title -->
                    <div class="space-y-2">
                        <label for="title" class="text-sm font-bold text-gray-700 ml-1">Titre de la tâche</label>
                        <x-text-input type="text" name="title" id="title" value="{{ old('title', $task->title) }}" required />
                        @error('title')
                            <p class="text-danger text-xs mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Project Selection -->
                        <div class="space-y-2">
                            <label for="project_id" class="text-sm font-bold text-gray-700 ml-1">Projet</label>
                            <select name="project_id" id="project_id" class="w-full border-none bg-gray-100/50 focus:bg-white focus:ring-4 focus:ring-primary/10 rounded-2xl py-3 px-4 text-gray-900 text-sm transition-all shadow-inner appearance-none">
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ old('project_id', $task->project_id) == $project->id ? 'selected' : '' }}>
                                        {{ $project->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Due Date -->
                        <div class="space-y-2">
                            <label for="due_date" class="text-sm font-bold text-gray-700 ml-1">Date d'échéance</label>
                            <x-text-input type="date" name="due_date" id="due_date" value="{{ old('due_date', $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') : '') }}" />
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="space-y-2">
                        <label for="description" class="text-sm font-bold text-gray-700 ml-1">Détails</label>
                        <textarea name="description" id="description" rows="4" class="w-full border-none bg-gray-100/50 focus:bg-white focus:ring-4 focus:ring-primary/10 rounded-2xl py-3 px-4 text-gray-900 placeholder-gray-400 text-sm transition-all shadow-inner">{{ old('description', $task->description) }}</textarea>
                    </div>

                    <!-- Status -->
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">Statut</label>
                        <div class="grid grid-cols-3 gap-3">
                            @foreach(['todo' => 'À faire', 'in_progress' => 'En cours', 'done' => 'Terminé'] as $val => $lab)
                                <label class="cursor-pointer group">
                                    <input type="radio" name="status" value="{{ $val }}" class="hidden peer" {{ old('status', $task->status) == $val ? 'checked' : '' }}>
                                    <div class="text-center py-3 rounded-2xl bg-gray-50 border border-gray-100 text-sm font-bold text-gray-500 peer-checked:bg-primary peer-checked:text-white peer-checked:border-primary peer-checked:shadow-lg peer-checked:shadow-primary/20 transition-all">
                                        {{ $lab }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-8 border-t border-gray-50">
                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Confirmer la suppression de cette tâche ?');" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-5 py-2.5 text-danger font-bold text-sm hover:bg-danger/5 rounded-2xl transition-colors">
                            Supprimer
                        </button>
                    </form>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('tasks.show', $task->id) }}" class="px-5 py-2.5 text-gray-500 hover:text-gray-900 font-bold text-sm transition-colors">
                            Annuler
                        </a>
                        <x-primary-button>
                            Sauvegarder
                        </x-primary-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection