@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            {{-- Détails de la tâche --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $task->title }}</h1>
                            <p class="text-sm text-gray-500 mb-3">
                                Projet :
                                <a href="{{ route('projects.show', $task->project_id) }}" class="text-blue-600 hover:underline">{{ $task->project->title }}</a>
                            </p>
                        </div>
                        <div>
                            @php
                                $statusColors = [
                                    'todo' => 'bg-gray-100 text-gray-800',
                                    'in_progress' => 'bg-blue-100 text-blue-800',
                                    'done' => 'bg-green-100 text-green-800',
                                ];
                                $statusLabels = [
                                    'todo' => 'À faire',
                                    'in_progress' => 'En cours',
                                    'done' => 'Terminée',
                                ];
                            @endphp
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $statusColors[$task->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusLabels[$task->status] ?? $task->status }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Description</h3>
                        <p class="text-gray-700 whitespace-pre-line">{{ $task->description ?: 'Aucune description.' }}</p>
                    </div>

                    @if($task->due_date)
                        <p class="mt-4 text-sm text-gray-600">
                            <span class="font-bold">Échéance :</span> {{ $task->due_date->format('d/m/Y') }}
                        </p>
                    @endif

                    {{-- Boutons d'action --}}
                    <div class="mt-6 flex flex-wrap gap-2">
                        <a href="{{ route('tasks.edit', $task->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Éditer
                        </a>
                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Supprimer
                            </button>
                        </form>
                        <a href="{{ route('projects.show', $task->project_id) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                            Retour
                        </a>
                    </div>
                </div>
            </div>

            {{-- Section Commentaires --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Commentaires</h2>

                    {{-- Formulaire d'ajout --}}
                    <form action="{{ route('comments.store', $task->id) }}" method="POST" class="mb-8">
                        @csrf
                        <div class="mb-3">
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Nouveau commentaire</label>
                            <textarea name="content" id="content" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Écrire un commentaire..." required></textarea>
                            @error('content')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Ajouter
                        </button>
                    </form>

                    {{-- Liste des commentaires --}}
                    <div class="space-y-4">
                        @forelse($comments as $c)
                            <div class="border-b border-gray-100 pb-4">
                                <div class="flex justify-between items-center mb-2">
                                    <div>
                                        <span class="font-bold text-gray-800">{{ $c->user->name }}</span>
                                        <span class="text-gray-400 text-sm ml-2">{{ $c->created_at->diffForHumans() }}</span>
                                    </div>
                                    @if(Auth::id() === $c->user_id)
                                        <form action="{{ route('comments.destroy', $c->id) }}" method="POST" onsubmit="return confirm('Supprimer ce commentaire ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">Supprimer</button>
                                        </form>
                                    @endif
                                </div>
                                <p class="text-gray-700">{{ $c->content }}</p>
                            </div>
                        @empty
                            <p class="text-gray-500 italic">Aucun commentaire.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection