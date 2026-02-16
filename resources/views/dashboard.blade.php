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
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Projects Card -->
                <div class="card-internal bg-white flex flex-col justify-between">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium mb-1">Projets</p>
                            <p class="text-4xl font-bold text-gray-900 leading-none">{{ $stats['projects'] }}</p>
                        </div>
                        <div class="bg-blue-50 text-blue-600 rounded-xl p-3 border border-blue-100 shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-50">
                        <a href="{{ route('projects.index') }}" class="text-blue-600 hover:text-blue-700 text-xs font-bold inline-flex items-center gap-1 group">
                            Voir tous les projets
                            <svg class="w-3 h-3 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>

                <!-- Tasks Card -->
                <div class="card-internal bg-white flex flex-col justify-between">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium mb-1">Tâches</p>
                            <p class="text-4xl font-bold text-gray-900 leading-none">{{ $stats['tasks'] }}</p>
                        </div>
                        <div class="bg-emerald-50 text-emerald-600 rounded-xl p-3 border border-emerald-100 shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-50">
                        <span class="text-emerald-600 text-xs font-bold">{{ $completedTasks }} terminées</span>
                    </div>
                </div>

                <!-- Teams Card -->
                <div class="card-internal bg-white flex flex-col justify-between">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium mb-1">Équipes</p>
                            <p class="text-4xl font-bold text-gray-900 leading-none">{{ $stats['teams'] }}</p>
                        </div>
                        <div class="bg-purple-50 text-purple-600 rounded-xl p-3 border border-purple-100 shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 8.646 4 4 0 010-8.646M3 20.394A9 9 0 0115 3c4.97 0 9 3.582 9 8s-4.03 8-9 8c-4.6 0-8.56-3.124-9-7.606z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-50">
                        <a href="{{ route('teams.index') }}" class="text-purple-600 hover:text-purple-700 text-xs font-bold inline-flex items-center gap-1 group">
                            Gérer les équipes
                            <svg class="w-3 h-3 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Analytics Section -->
            <div class="card-internal bg-white p-6 mb-12 shadow-sm border-gray-200">
                <h2 class="text-xl font-bold text-gray-900 mb-6 tracking-tight">Activité</h2>
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {{-- 1. Taux de complétion --}}
                    <div class="p-4 rounded-xl border border-gray-100 bg-gray-50/50">
                        <h3 class="text-[12px] font-bold text-gray-500 uppercase tracking-widest mb-6 border-b border-gray-100 pb-2 flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Taux de complétion
                        </h3>
                        <div class="flex flex-col items-center justify-center pt-2">
                            <div class="relative w-32 h-32 flex items-center justify-center">
                                <svg class="w-full h-full transform -rotate-90">
                                    <circle cx="64" cy="64" r="56" stroke="currentColor" stroke-width="8" fill="transparent" class="text-gray-200"></circle>
                                    <circle cx="64" cy="64" r="56" stroke="currentColor" stroke-width="8" fill="transparent" class="text-emerald-500" stroke-dasharray="351.8" stroke-dashoffset="{{ 351.8 * (1 - $taskCompletionRate / 100) }}" style="transition: stroke-dashoffset 1s cubic-bezier(0.4, 0, 0.2, 1);"></circle>
                                </svg>
                                <div class="absolute inset-0 flex flex-col items-center justify-center">
                                    <span class="text-2xl font-black text-gray-900">{{ $taskCompletionRate }}%</span>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Fait</span>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-6 font-medium">{{ $completedTasks }} tâches sur {{ $totalTasks }}</p>
                        </div>
                    </div>

                    {{-- 2. Projets par statut --}}
                    <div class="p-4 rounded-xl border border-gray-100 bg-gray-50/50">
                        <h3 class="text-[12px] font-bold text-gray-500 uppercase tracking-widest mb-6 border-b border-gray-100 pb-2 flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            Projets par statut
                        </h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-gray-100 group hover:border-blue-200 transition-colors">
                                <div class="flex items-center gap-3">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span class="text-sm font-bold text-gray-600 group-hover:text-gray-900 transition-colors">Préparation</span>
                                </div>
                                <span class="text-sm font-black text-gray-900">{{ $projectsByStatus['preparation'] ?? 0 }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-gray-100 group hover:border-blue-200 transition-colors">
                                <div class="flex items-center gap-3">
                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    <span class="text-sm font-bold text-gray-600 group-hover:text-gray-900 transition-colors">En cours</span>
                                </div>
                                <span class="text-sm font-black text-gray-900">{{ $projectsByStatus['in_progress'] ?? 0 }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-gray-100 group hover:border-blue-200 transition-colors">
                                <div class="flex items-center gap-3">
                                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    <span class="text-sm font-bold text-gray-600 group-hover:text-gray-900 transition-colors">Complété</span>
                                </div>
                                <span class="text-sm font-black text-gray-900">{{ $projectsByStatus['completed'] ?? 0 }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- 3. Activité Hebdomadaire --}}
                    <div class="p-4 rounded-xl border border-gray-100 bg-gray-50/50">
                        <h3 class="text-[12px] font-bold text-gray-500 uppercase tracking-widest mb-6 border-b border-gray-100 pb-2 flex items-center gap-2">
                            <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                            Dernière semaine
                        </h3>
                        <div class="h-32 flex items-end justify-between gap-3 px-2">
                            @php $maxActivity = max(array_merge([1], array_values($activityData))); @endphp
                            @foreach($activityData as $day => $count)
                                <div class="flex-1 flex flex-col items-center gap-2 group cursor-help relative">
                                    {{-- Tooltip --}}
                                    <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap">
                                        {{ $count }} tâches
                                    </div>
                                    <div class="w-full bg-blue-100/50 rounded-t-lg transition-all duration-300 group-hover:bg-blue-200 relative overflow-hidden" style="height: {{ ($count / $maxActivity) * 100 }}%">
                                        <div class="absolute inset-0 bg-blue-600 rounded-t-lg transition-all duration-700 h-full"></div>
                                    </div>
                                    <span class="text-[10px] font-black text-gray-400 group-hover:text-blue-600">{{ ucfirst(\Carbon\Carbon::parse($day)->isoFormat('ddd')) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 tracking-tight">Accès rapide</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                    <a href="{{ route('projects.create') }}" class="group relative overflow-hidden bg-blue-600 rounded-xl p-6 font-bold transition-all duration-300 hover:shadow-xl hover:shadow-blue-500/20 hover:-translate-y-1">
                        <div class="relative z-10 flex items-center justify-between text-white">
                            <span>Nouveau Projet</span>
                            <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </div>
                    </a>
                    <a href="{{ route('teams.create') }}" class="group relative overflow-hidden bg-purple-600 rounded-xl p-6 font-bold transition-all duration-300 hover:shadow-xl hover:shadow-purple-500/20 hover:-translate-y-1">
                        <div class="relative z-10 flex items-center justify-between text-white">
                            <span>Créer Équipe</span>
                            <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                        </div>
                    </a>
                    <a href="{{ route('projects.index') }}" class="group card-internal p-6 hover:-translate-y-1">
                        <div class="flex items-center justify-between text-gray-900">
                            <span class="font-bold">Voir Projets</span>
                            <svg class="w-6 h-6 text-gray-400 group-hover:text-blue-600 transition-colors group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </a>
                    <a href="{{ route('projects.index') }}" class="group card-internal p-6 hover:-translate-y-1">
                        <div class="flex items-center justify-between text-gray-900">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-teal-50 flex items-center justify-center text-teal-600 group-hover:bg-teal-600 group-hover:text-white transition-all">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <span class="font-bold">Calendrier</span>
                            </div>
                            <svg class="w-6 h-6 text-gray-400 group-hover:text-teal-600 transition-colors group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </a>
                    <a href="{{ route('teams.index') }}" class="group card-internal p-6 hover:-translate-y-1">
                        <div class="flex items-center justify-between text-gray-900">
                            <span class="font-bold">Voir Équipes</span>
                            <svg class="w-6 h-6 text-gray-400 group-hover:text-purple-600 transition-colors group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Projects -->
                <div class="space-y-6">
                    <h2 class="text-2xl font-bold text-gray-900 tracking-tight flex items-center gap-3">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        Projets récents
                    </h2>
                    @if($recentProjects->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentProjects as $project)
                                <div class="card-internal group">
                                    <div class="flex items-start justify-between mb-4">
                                        <div>
                                            <h3 class="text-lg font-bold text-gray-900 group-hover:text-blue-600 transition-colors">{{ $project->title }}</h3>
                                            <p class="text-gray-500 text-sm mt-1 leading-relaxed">{{ \Illuminate\Support\Str::limit($project->description, 80) }}</p>
                                        </div>
                                        <div class="shrink-0 text-right">
                                            @php
                                                $statusClasses = [
                                                    'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                                    'in_progress' => 'bg-blue-50 text-blue-700 border-blue-100',
                                                    'preparation' => 'bg-gray-50 text-gray-600 border-gray-100'
                                                ];
                                            @endphp
                                            <span class="px-3 py-1 border rounded-lg text-xs font-bold uppercase tracking-widest {{ $statusClasses[$project->status] ?? $statusClasses['preparation'] }}">
                                                {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                            </span>
                                            <p class="text-[10px] text-gray-400 font-bold mt-2">{{ $project->created_at->translatedFormat('j M Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between border-t border-gray-50 pt-4 mt-4">
                                        <div class="flex items-center gap-4">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                                <span class="text-xs font-bold text-gray-600">{{ $project->tasks_count ?? $project->tasks->count() }} tâches</span>
                                            </div>
                                            <a href="{{ route('projects.calendar', $project->id) }}" class="flex items-center gap-2 text-gray-400 hover:text-teal-600 transition-colors" title="Calendrier">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </a>
                                        </div>
                                        <a href="{{ route('projects.show', $project->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-bold flex items-center gap-1 group/link transition-colors">
                                            Accéder
                                            <svg class="w-4 h-4 transition-transform group-hover/link:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="card-internal border-dashed text-center py-16">
                            <p class="text-gray-400 font-medium italic mb-6">Aucun projet pour le moment</p>
                            <a href="{{ route('projects.create') }}" class="btn-secondary">Créer un projet</a>
                        </div>
                    @endif
                </div>

                <!-- Recent Tasks -->
                <div class="space-y-6">
                    <h2 class="text-2xl font-bold text-gray-900 tracking-tight flex items-center gap-3">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        Tâches à faire
                    </h2>
                    @if($recentTasks->count() > 0)
                        <div class="card-internal p-0 overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-100">
                                <thead class="bg-gray-50/50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-500 uppercase tracking-widest">Tâche</th>
                                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-500 uppercase tracking-widest">Projet</th>
                                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-500 uppercase tracking-widest hidden sm:table-cell">Statut</th>
                                        <th class="px-6 py-4 text-right text-[10px] font-black text-gray-500 uppercase tracking-widest">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    @foreach($recentTasks as $task)
                                        <tr class="group hover:bg-gray-50/50 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="flex flex-col">
                                                    <span class="text-sm font-bold text-gray-900 truncate max-w-[150px]">{{ $task->title }}</span>
                                                    <span class="text-[10px] text-gray-400 font-bold mt-1">Echéance: {{ $task->due_date ? $task->due_date->format('d/m/Y') : '-' }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="text-xs font-bold text-gray-600 bg-gray-100 px-2 py-1 rounded-lg">{{ $task->project->title }}</span>
                                            </td>
                                            <td class="px-6 py-4 hidden sm:table-cell">
                                                <span class="px-2 py-1 rounded-lg text-[10px] font-bold uppercase tracking-tight
                                                    @if($task->status === 'done') bg-emerald-100 text-emerald-800
                                                    @elseif($task->status === 'in_progress') bg-blue-100 text-blue-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <a href="{{ route('tasks.show', $task->id) }}" class="p-2 text-gray-400 hover:text-blue-600 transition-colors inline-block group-hover:translate-x-1 transition-transform">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="card-internal border-dashed text-center py-16">
                            <p class="text-gray-400 font-medium italic">Aucune tâche active</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection