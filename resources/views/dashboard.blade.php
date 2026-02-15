@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                Bienvenue, {{ Auth::user()->name }} !
            </h2>

            <!-- Cartes de statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
                <!-- Projets Actifs -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500">
                    <div class="text-gray-500 text-sm font-medium uppercase">Projets</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['projects'] }}</div>
                </div>

                <!-- Tâches à faire -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-gray-500">
                    <div class="text-gray-500 text-sm font-medium uppercase">À faire</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['todo'] }}</div>
                </div>

                <!-- Tâches en cours -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="text-gray-500 text-sm font-medium uppercase">En cours</div>
                    <div class="mt-2 text-3xl font-bold text-blue-600">{{ $stats['in_progress'] }}</div>
                </div>

                <!-- Tâches terminées -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="text-gray-500 text-sm font-medium uppercase">Terminées</div>
                    <div class="mt-2 text-3xl font-bold text-green-600">{{ $stats['completed'] }}</div>
                </div>

                <!-- Équipes -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-purple-500">
                    <div class="text-gray-500 text-sm font-medium uppercase">Équipes</div>
                    <div class="mt-2 text-3xl font-bold text-purple-600">{{ $stats['teams'] }}</div>
                </div>
            </div>

            <!-- Boutons d'accès rapide -->
            <div class="flex flex-wrap gap-3 mb-8">
                <a href="{{ route('projects.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm shadow">
                    + Nouveau projet
                </a>
                <a href="{{ route('projects.index') }}" class="bg-white hover:bg-gray-50 text-gray-700 font-semibold py-2 px-4 border border-gray-300 rounded text-sm shadow-sm">
                    Tous les projets
                </a>
                <a href="{{ route('teams.index') }}" class="bg-white hover:bg-gray-50 text-gray-700 font-semibold py-2 px-4 border border-gray-300 rounded text-sm shadow-sm">
                    Mes équipes
                </a>
                <a href="{{ route('search') }}" class="bg-white hover:bg-gray-50 text-gray-700 font-semibold py-2 px-4 border border-gray-300 rounded text-sm shadow-sm">
                    🔍 Rechercher
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Projets récents -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-gray-800">Projets récents</h3>
                            <a href="{{ route('projects.index') }}" class="text-blue-600 hover:underline text-sm">Voir tout →</a>
                        </div>

                        @if($recentProjects->count() > 0)
                            <ul class="divide-y divide-gray-200">
                                @foreach($recentProjects as $project)
                                    <li class="py-3 flex justify-between items-center">
                                        <div>
                                            <a href="{{ route('projects.show', $project->id) }}" class="text-sm font-medium text-gray-900 hover:text-blue-600">
                                                {{ $project->title }}
                                            </a>
                                            @php
                                                $pStatusColors = [
                                                    'preparation' => 'bg-gray-100 text-gray-800',
                                                    'in_progress' => 'bg-blue-100 text-blue-800',
                                                    'completed' => 'bg-green-100 text-green-800',
                                                ];
                                            @endphp
                                            <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $pStatusColors[$project->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                            </span>
                                        </div>
                                        <span class="text-xs text-gray-400">{{ $project->updated_at->diffForHumans() }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500 italic text-center py-4">Aucun projet.</p>
                        @endif
                    </div>
                </div>

                <!-- Tâches récentes -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-gray-800">Tâches récentes</h3>
                        </div>

                        @if($recentTasks->count() > 0)
                            <ul class="divide-y divide-gray-200">
                                @foreach($recentTasks as $task)
                                    <li class="py-3 flex justify-between items-center">
                                        <div>
                                            <a href="{{ route('tasks.show', $task->id) }}" class="text-sm font-medium text-gray-900 hover:text-blue-600">
                                                {{ $task->title }}
                                            </a>
                                            @php
                                                $tStatusColors = [
                                                    'todo' => 'bg-gray-100 text-gray-800',
                                                    'in_progress' => 'bg-blue-100 text-blue-800',
                                                    'done' => 'bg-green-100 text-green-800',
                                                ];
                                            @endphp
                                            <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $tStatusColors[$task->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                            </span>
                                            <span class="text-xs text-gray-400 ml-2">{{ $task->project->title }}</span>
                                        </div>
                                        <span class="text-xs text-gray-400">{{ $task->updated_at->diffForHumans() }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500 italic text-center py-4">Aucune tâche.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Section Prochaines tâches -->
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-800">Prochaines échéances</h3>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if($upcomingTasks->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tâche</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Projet</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Échéance</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                        <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($upcomingTasks as $task)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $task->title }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <a href="{{ route('projects.show', $task->project_id) }}" class="hover:text-blue-600 hover:underline">
                                                    {{ $task->project->title }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $task->due_date->format('d/m/Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $statusColors = [
                                                        'todo' => 'bg-gray-100 text-gray-800',
                                                        'in_progress' => 'bg-blue-100 text-blue-800',
                                                        'done' => 'bg-green-100 text-green-800',
                                                    ];
                                                    $statusLabels = [
                                                        'todo' => 'À faire',
                                                        'in_progress' => 'En cours',
                                                        'done' => 'Terminé',
                                                    ];
                                                @endphp
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$task->status] }}">
                                                    {{ $statusLabels[$task->status] }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('tasks.edit', $task->id) }}" class="text-indigo-600 hover:text-indigo-900">Éditer</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <p>Aucune tâche en attente.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection