@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <!-- Hero Section -->
        <div class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <h1 class="text-4xl font-bold text-gray-900">Bienvenue, {{ Auth::user()->name }}</h1>
                <p class="text-gray-600 mt-2">Gestion de projets collaboratifs</p>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                <!-- Projects Card -->
                <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-lg transition-shadow duration-200">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium mb-1">Projets</p>
                            <p class="text-4xl font-bold text-gray-900">{{ $stats['projects'] }}</p>
                        </div>
                        <div class="bg-blue-100 text-blue-600 rounded-lg p-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-gray-500 text-xs mt-4">
                        <a href="{{ route('projects.index') }}" class="text-blue-600 hover:text-blue-700 font-medium">Voir tous</a>
                    </p>
                </div>

                <!-- Tasks Card -->
                <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-lg transition-shadow duration-200">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium mb-1">Tâches</p>
                            <p class="text-4xl font-bold text-gray-900">{{ $stats['tasks'] }}</p>
                        </div>
                        <div class="bg-emerald-100 text-emerald-600 rounded-lg p-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-gray-500 text-xs mt-4">
                        <span class="text-emerald-600 font-medium">À compléter</span>
                    </p>
                </div>

                <!-- Teams Card -->
                <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-lg transition-shadow duration-200">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium mb-1">Équipes</p>
                            <p class="text-4xl font-bold text-gray-900">{{ $stats['teams'] }}</p>
                        </div>
                        <div class="bg-purple-100 text-purple-600 rounded-lg p-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 8.646 4 4 0 010-8.646M3 20.394A9 9 0 0115 3c4.97 0 9 3.582 9 8s-4.03 8-9 8c-4.6 0-8.56-3.124-9-7.606z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-gray-500 text-xs mt-4">
                        <a href="{{ route('teams.index') }}" class="text-purple-600 hover:text-purple-700 font-medium">Gérer</a>
                    </p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Accès rapide</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="{{ route('projects.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white rounded-lg p-4 font-medium transition-colors duration-200 flex items-center justify-center">
                        Nouveau Projet
                    </a>
                    <a href="{{ route('teams.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white rounded-lg p-4 font-medium transition-colors duration-200 flex items-center justify-center">
                        Créer Équipe
                    </a>
                    <a href="{{ route('projects.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg p-4 font-medium transition-colors duration-200 flex items-center justify-center">
                        Voir Projets
                    </a>
                    <a href="{{ route('teams.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg p-4 font-medium transition-colors duration-200 flex items-center justify-center">
                        Voir Équipes
                    </a>
                </div>
            </div>

            <!-- Recent Projects -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Projets récents</h2>
                @if($recentProjects->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($recentProjects as $project)
                            <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-lg transition-shadow duration-200">
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">{{ $project->title }}</h3>
                                        <p class="text-gray-600 text-sm mt-1">{{ \Illuminate\Support\Str::limit($project->description, 60) }}</p>
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-xs font-medium
                                        @if($project->status === 'completed') bg-emerald-100 text-emerald-800
                                        @elseif($project->status === 'in_progress') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between text-sm text-gray-600 mb-4">
                                    <span>{{ $project->tasks_count ?? $project->tasks->count() }} tâches</span>
                                    <span>{{ $project->created_at->format('d/m/Y') }}</span>
                                </div>
                                <a href="{{ route('projects.show', $project->id) }}" class="inline-block text-blue-600 hover:text-blue-700 font-medium text-sm">
                                    Voir le projet
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white rounded-lg border border-gray-200 p-12 text-center">
                        <p class="text-gray-600 mb-4">Aucun projet pour le moment</p>
                        <a href="{{ route('projects.create') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                            Créer votre premier projet
                        </a>
                    </div>
                @endif
            </div>

            <!-- Recent Tasks -->
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Tâches récentes</h2>
                @if($recentTasks->count() > 0)
                    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Tâche</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Projet</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Statut</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Échéance</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($recentTasks as $task)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 text-sm">
                                            <span class="font-medium text-gray-900">{{ $task->title }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            {{ $task->project->title }}
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                                @if($task->status === 'done') bg-emerald-100 text-emerald-800
                                                @elseif($task->status === 'in_progress') bg-blue-100 text-blue-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            @if($task->due_date)
                                                {{ $task->due_date->format('d/m/Y') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right text-sm">
                                            <a href="{{ route('tasks.show', $task->id) }}" class="text-blue-600 hover:text-blue-700 font-medium">
                                                Voir
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="bg-white rounded-lg border border-gray-200 p-12 text-center">
                        <p class="text-gray-600">Aucune tâche pour le moment</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection