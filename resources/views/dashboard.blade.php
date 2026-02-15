@extends('layouts.app')

@section('content')
    @if(isset($animationTimestamps))
        <script>window.__dashboardAnimationTimestamps = @json($animationTimestamps);</script>
    @endif
    
    <div class="space-y-8" @if(isset($animationTimestamps)) data-loaded-at="{{ $animationTimestamps['loadedAt'] ?? '' }}" @endif>
        
        <!-- Hero Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 mb-8 relative overflow-hidden">
            <div class="relative z-10">
                <h2 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-blue-800 mb-2">
                    Bienvenue, {{ Auth::user()->name }} !
                </h2>
                <p class="text-gray-500">Voici un aperçu de votre activité aujourd'hui.</p>
            </div>
            <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-blue-50 to-transparent opacity-50"></div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Projects Card -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 animate-scale-in" style="animation-delay: 0ms;">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-blue-50 rounded-lg text-blue-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                    </div>
                    <span class="text-sm font-medium text-green-500 flex items-center bg-green-50 px-2 py-1 rounded-full">
                        Actif
                    </span>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['projects'] }}</div>
                <div class="text-sm text-gray-500 font-medium">Projets en cours</div>
            </div>

            <!-- Tasks Card -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 animate-scale-in" style="animation-delay: 100ms;">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-indigo-50 rounded-lg text-indigo-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['tasks'] }}</div>
                <div class="text-sm text-gray-500 font-medium">Tâches assignées</div>
            </div>

            <!-- Teams Card -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 animate-scale-in" style="animation-delay: 200ms;">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-purple-50 rounded-lg text-purple-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['teams'] }}</div>
                <div class="text-sm text-gray-500 font-medium">Équipes actives</div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <a href="{{ route('projects.create') }}" class="flex flex-col items-center justify-center p-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-md transition-all duration-200 hover:scale-105 group">
                <svg class="w-6 h-6 mb-2 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span class="font-medium text-sm">Nouveau Projet</span>
            </a>
            <a href="{{ route('projects.index') }}" class="flex flex-col items-center justify-center p-4 bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 rounded-xl shadow-sm transition-all duration-200 hover:border-blue-300 hover:text-blue-600 group">
                <svg class="w-6 h-6 mb-2 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                <span class="font-medium text-sm">Tous les Projets</span>
            </a>
            <a href="{{ route('teams.index') }}" class="flex flex-col items-center justify-center p-4 bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 rounded-xl shadow-sm transition-all duration-200 hover:border-purple-300 hover:text-purple-600 group">
                <svg class="w-6 h-6 mb-2 text-gray-400 group-hover:text-purple-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <span class="font-medium text-sm">Mes Équipes</span>
            </a>
            <a href="{{ route('search') }}" class="flex flex-col items-center justify-center p-4 bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 rounded-xl shadow-sm transition-all duration-200 hover:border-indigo-300 hover:text-indigo-600 group">
                <svg class="w-6 h-6 mb-2 text-gray-400 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <span class="font-medium text-sm">Rechercher</span>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Projects -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-lg font-bold text-gray-900">Projets Récents</h3>
                    <a href="{{ route('projects.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium hover:underline">Voir tout</a>
                </div>
                <div class="p-0">
                    @if($recentProjects->count() > 0)
                        <div class="divide-y divide-gray-100">
                            @foreach($recentProjects as $project)
                                <div class="group p-4 hover:bg-gray-50 transition-colors duration-200 flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                                        </div>
                                        <div>
                                            <a href="{{ route('projects.show', $project->id) }}" class="block text-sm font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">
                                                {{ $project->title }}
                                            </a>
                                            <span class="text-xs text-gray-500">{{ $project->updated_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        @php
                                            $statusClasses = [
                                                'preparation' => 'bg-gray-100 text-gray-700',
                                                'in_progress' => 'bg-blue-100 text-blue-700',
                                                'completed' => 'bg-emerald-100 text-emerald-700',
                                            ];
                                            $statusLabel = ucfirst(str_replace('_', ' ', $project->status));
                                            $statusClass = $statusClasses[$project->status] ?? 'bg-gray-100 text-gray-700';
                                        @endphp
                                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                        <a href="{{ route('projects.edit', $project->id) }}" class="text-gray-400 hover:text-blue-600 transition-colors p-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-8 text-center">
                            <div class="text-gray-400 mb-2">📂</div>
                            <p class="text-gray-500 italic">Aucun projet récent.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Tasks -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-lg font-bold text-gray-900">Tâches Récentes</h3>
                </div>
                <div class="p-0">
                    @if($recentTasks->count() > 0)
                        <div class="divide-y divide-gray-100">
                            @foreach($recentTasks as $task)
                                <div class="group p-4 hover:bg-gray-50 transition-colors duration-200 flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <div class="min-w-0">
                                            <a href="{{ route('tasks.show', $task->id) }}" class="block text-sm font-semibold text-gray-900 truncate group-hover:text-indigo-600 transition-colors">
                                                {{ $task->title }}
                                            </a>
                                            <div class="flex items-center gap-2 mt-0.5">
                                                @if($task->project)
                                                    <span class="text-xs text-gray-500 flex items-center gap-1">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span>
                                                        {{ $task->project->title }}
                                                    </span>
                                                @endif
                                                <span class="text-xs text-gray-400">• {{ $task->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        @php
                                            $statusClasses = [
                                                'todo' => 'bg-gray-100 text-gray-600',
                                                'in_progress' => 'bg-blue-100 text-blue-600',
                                                'done' => 'bg-green-100 text-green-600',
                                            ];
                                            $statusLabel = ucfirst(str_replace('_', ' ', $task->status));
                                            $statusClass = $statusClasses[$task->status] ?? 'bg-gray-100 text-gray-600';
                                        @endphp
                                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-8 text-center">
                            <div class="text-gray-400 mb-2">📝</div>
                            <p class="text-gray-500 italic">Aucune tâche récente.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection