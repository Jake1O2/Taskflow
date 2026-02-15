@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <p class="mb-4 text-green-600">{{ session('success') }}</p>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">{{ $task->title }}</h2>
                            <p class="text-sm text-gray-500 mt-1">Projet : {{ $task->project->title }}</p>
                            <p class="mt-2 text-gray-700">{{ $task->description ?? '—' }}</p>
                            <p class="mt-2 text-sm">
                                <span class="font-medium">Statut :</span>
                                @switch($task->status)
                                    @case('todo') À faire @break
                                    @case('in_progress') En cours @break
                                    @case('done') Terminé @break
                                @endswitch
                            </p>
                            @if($task->due_date)
                                <p class="text-sm text-gray-600">Échéance : {{ $task->due_date->format('d/m/Y') }}</p>
                            @endif
                        </div>
                        <a href="{{ route('tasks.edit', $task->id) }}" class="text-blue-600 hover:underline">Éditer</a>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Commentaires</h3>

                    <form action="{{ route('comments.store', $task->id) }}" method="POST" class="mb-6">
                        @csrf
                        <div class="mb-2">
                            <label for="content" class="block text-sm font-medium text-gray-700">Nouveau commentaire</label>
                            <textarea name="content" id="content" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" maxlength="1000" required></textarea>
                            @error('content')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Ajouter</button>
                    </form>

                    <ul class="space-y-3">
                        @forelse($comments as $comment)
                            <li class="flex justify-between items-start border-b border-gray-100 pb-3">
                                <div>
                                    <p class="text-gray-800">{{ $comment->content }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $comment->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                @if($comment->user_id === auth()->id())
                                    <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Supprimer ce commentaire ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 text-sm hover:underline">Supprimer</button>
                                    </form>
                                @endif
                            </li>
                        @empty
                            <li class="text-gray-500">Aucun commentaire.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <p class="mt-4">
                <a href="{{ route('projects.show', $task->project_id) }}" class="text-blue-600 hover:underline">← Retour au projet</a>
            </p>
        </div>
    </div>
@endsection
