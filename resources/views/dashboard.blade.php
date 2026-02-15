@extends('layouts.app')

@section('content')
    @if(isset($animationTimestamps))
        <script>window.__dashboardAnimationTimestamps = @json($animationTimestamps);</script>
    @endif
    <div class="py-12" @if(isset($animationTimestamps)) data-loaded-at="{{ $animationTimestamps['loadedAt'] ?? '' }}" @endif>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight mb-8 flex items-center gap-2 animate-slide-down">
                <span>Bienvenue, {{ Auth::user()->name }} !</span>
            </h2>

            <!-- Cartes de statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500">
                    <div class="text-gray-500 text-sm font-medium uppercase">Projets</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['projects'] }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="text-gray-500 text-sm font-medium uppercase">Tâches</div>
                    <div class="mt-2 text-3xl font-bold text-blue-600">{{ $stats['tasks'] }}</div>
                </div>
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
                <a href="{{ route('search') }}" class="bg-white hover:bg-gray-50 text-gray-700 font-semibold py-2 px-4 border border-gray-300 rounded text-sm shadow-sm flex items-center gap-1">
                    <i class="fa-solid fa-magnifying-glass"></i> Rechercher
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
                                        @php
                                            $statusStyles = [
                                                'todo' => 'bg-gray-100 text-gray-800 border-gray-200',
                                                'in_progress' => 'bg-blue-50 text-blue-700 border-blue-200',
                                                'done' => 'bg-green-50 text-green-700 border-green-200',
                                            ];
                                        @endphp
                                        <div class="flex items-center gap-2">
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $statusStyles[$task->status] ?? 'bg-gray-100 text-gray-600' }}">
                                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                            </span>
                                            <a href="{{ route('tasks.show', $task->id) }}" class="text-sm font-medium text-gray-900 hover:text-blue-600">
                                                {{ $task->title }}
                                            </a>
                                            @if($task->project)
                                                <span class="text-xs text-gray-400 ml-2">{{ $task->project->title }}</span>
                                            @endif
                                        </div>
                                        <span class="text-xs text-gray-400">{{ $task->created_at->diffForHumans() }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500 italic text-center py-4">Aucune tâche.</p>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection