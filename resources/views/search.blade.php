@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Recherche</h1>

            {{-- Formulaire de recherche --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form action="{{ route('search') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                        <div class="flex-1">
                            <input type="text" name="q" value="{{ $query }}"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="Rechercher des projets ou tâches..." autofocus>
                        </div>
                        <div>
                            <select name="type" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="all" @selected($type === 'all')>Tout</option>
                                <option value="projects" @selected($type === 'projects')>Projets</option>
                                <option value="tasks" @selected($type === 'tasks')>Tâches</option>
                            </select>
                        </div>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                            🔍 Rechercher
                        </button>
                    </form>
                </div>
            </div>

            @if($query)
                {{-- Résultats Projets --}}
                @if($type === 'all' || $type === 'projects')
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            <h2 class="text-xl font-bold text-gray-800 mb-4">
                                Projets
                                <span class="text-sm font-normal text-gray-500">({{ $projects->count() }} résultat{{ $projects->count() > 1 ? 's' : '' }})</span>
                            </h2>

                            @if($projects->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tâches</th>
                                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($projects as $project)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900">{{ $project->title }}</div>
                                                        @if($project->description)
                                                            <div class="text-sm text-gray-500">{{ Str::limit($project->description, 60) }}</div>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        @php
                                                            $statusColors = [
                                                                'preparation' => 'bg-gray-100 text-gray-800',
                                                                'in_progress' => 'bg-blue-100 text-blue-800',
                                                                'completed' => 'bg-green-100 text-green-800',
                                                            ];
                                                        @endphp
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$project->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                            {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $project->tasks->count() }} tâche{{ $project->tasks->count() > 1 ? 's' : '' }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                                        <a href="{{ route('projects.show', $project->id) }}" class="text-blue-600 hover:text-blue-900">Voir</a>
                                                        <a href="{{ route('projects.edit', $project->id) }}" class="text-indigo-600 hover:text-indigo-900">Éditer</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-gray-500 italic">Aucun projet trouvé.</p>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Résultats Tâches --}}
                @if($type === 'all' || $type === 'tasks')
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h2 class="text-xl font-bold text-gray-800 mb-4">
                                Tâches
                                <span class="text-sm font-normal text-gray-500">({{ $tasks->count() }} résultat{{ $tasks->count() > 1 ? 's' : '' }})</span>
                            </h2>

                            @if($tasks->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Projet</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Échéance</th>
                                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($tasks as $task)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900">{{ $task->title }}</div>
                                                        @if($task->description)
                                                            <div class="text-sm text-gray-500">{{ Str::limit($task->description, 60) }}</div>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        <a href="{{ route('projects.show', $task->project_id) }}" class="text-blue-600 hover:underline">
                                                            {{ $task->project->title }}
                                                        </a>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        @php
                                                            $taskStatusColors = [
                                                                'todo' => 'bg-gray-100 text-gray-800',
                                                                'in_progress' => 'bg-blue-100 text-blue-800',
                                                                'done' => 'bg-green-100 text-green-800',
                                                            ];
                                                            $taskStatusLabels = [
                                                                'todo' => 'À faire',
                                                                'in_progress' => 'En cours',
                                                                'done' => 'Terminée',
                                                            ];
                                                        @endphp
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $taskStatusColors[$task->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                            {{ $taskStatusLabels[$task->status] ?? $task->status }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $task->due_date ? $task->due_date->format('d/m/Y') : '—' }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                                        <a href="{{ route('tasks.show', $task->id) }}" class="text-blue-600 hover:text-blue-900">Voir</a>
                                                        <a href="{{ route('tasks.edit', $task->id) }}" class="text-indigo-600 hover:text-indigo-900">Éditer</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-gray-500 italic">Aucune tâche trouvée.</p>
                            @endif
                        </div>
                    </div>
                @endif
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center text-gray-500">
                        <p class="text-lg">Entrez un terme de recherche pour trouver des projets ou tâches.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
