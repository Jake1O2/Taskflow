@extends('layouts.app')

@section('content')
    @php
        $hour = now()->hour;
        $greeting = $hour < 12 ? 'Bonjour' : ($hour < 18 ? 'Bon après-midi' : 'Bonsoir');
    @endphp
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

        {{-- Page Header --}}
        <div
            class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-primary via-[#1a68a8] to-[#0d9488] p-8 sm:p-10 text-white shadow-xl shadow-primary/20">
            <div class="absolute inset-0 grain-texture pointer-events-none opacity-[0.04]"></div>
            <div class="absolute -right-16 -top-16 h-56 w-56 rounded-full bg-white/5"></div>
            <div class="absolute -right-4 -bottom-8 h-36 w-36 rounded-full bg-white/5"></div>
            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <p class="text-white/60 text-sm font-medium tracking-wide mb-1">{{ $greeting }},</p>
                    <h1 class="text-3xl sm:text-4xl font-bold tracking-tight">{{ Auth::user()->name }}</h1>
                    <p class="text-white/70 mt-2 text-sm">Voici un aperçu de votre activité</p>
                </div>
                <div class="flex gap-3 shrink-0">
                    <a href="{{ route('projects.create') }}"
                        class="inline-flex items-center gap-2 bg-white/15 hover:bg-white/25 border border-white/20 text-white rounded-2xl px-5 py-2.5 font-bold text-sm transition-all hover:scale-105">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Nouveau projet
                    </a>
                </div>
            </div>
        </div>

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            {{-- Projects --}}
            <div class="card-internal flex flex-col justify-between">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium mb-1">Projets</p>
                        <p class="text-4xl font-bold text-gray-900 leading-none tracking-tight">{{ $stats['projects'] }}</p>
                    </div>
                    <div class="stat-icon-blue">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                </div>
                <div class="mt-5 pt-4 border-t border-gray-50">
                    <a href="{{ route('projects.index') }}"
                        class="text-blue-600 hover:text-blue-700 text-xs font-bold inline-flex items-center gap-1.5 group">
                        Voir tous les projets
                        <svg class="w-3 h-3 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Tasks --}}
            <div class="card-internal flex flex-col justify-between">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium mb-1">Tâches</p>
                        <p class="text-4xl font-bold text-gray-900 leading-none tracking-tight">{{ $stats['tasks'] }}</p>
                    </div>
                    <div class="stat-icon-green">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-5 pt-4 border-t border-gray-50">
                    @php $rate = $stats['tasks'] > 0 ? round(($completedTasks / $stats['tasks']) * 100) : 0; @endphp
                    <div class="flex items-center gap-2 mb-2">
                        <div class="flex-1 progress-bar-track">
                            <div class="progress-bar-fill" style="--progress-width: {{ $rate }}%; width: {{ $rate }}%;">
                            </div>
                        </div>
                        <span class="text-emerald-600 text-xs font-bold shrink-0">{{ $rate }}%</span>
                    </div>
                    <span class="text-xs text-gray-400 font-medium">{{ $completedTasks }} terminées sur
                        {{ $stats['tasks'] }}</span>
                </div>
            </div>

            {{-- Teams --}}
            <div class="card-internal flex flex-col justify-between">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium mb-1">Équipes</p>
                        <p class="text-4xl font-bold text-gray-900 leading-none tracking-tight">{{ $stats['teams'] }}</p>
                    </div>
                    <div class="stat-icon-purple">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-5 pt-4 border-t border-gray-50">
                    <a href="{{ route('teams.index') }}"
                        class="text-violet-600 hover:text-violet-700 text-xs font-bold inline-flex items-center gap-1.5 group">
                        Gérer les équipes
                        <svg class="w-3 h-3 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        {{-- Analytics Section --}}
        <div class="card-internal">
            <h2 class="text-xl font-bold text-gray-900 mb-6 tracking-tight flex items-center gap-2">
                <span class="w-1 h-6 rounded-full bg-gradient-to-b from-primary to-cyan-vibrant inline-block"></span>
                Activité & Statistiques
            </h2>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- 1. Completion Rate --}}
                <div class="p-5 rounded-2xl border border-gray-100 bg-gradient-to-br from-gray-50/80 to-white">
                    <h3 class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-5 flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Taux de complétion
                    </h3>
                    <div class="flex flex-col items-center justify-center">
                        <div class="relative w-32 h-32 flex items-center justify-center">
                            @php
                                $circ = 2 * 3.14159 * 56;
                                $offs = $circ * (1 - $taskCompletionRate / 100);
                            @endphp
                            <svg class="w-full h-full transform -rotate-90" viewBox="0 0 128 128">
                                <circle cx="64" cy="64" r="56" stroke="#eef2f6" stroke-width="8" fill="transparent">
                                </circle>
                                <circle cx="64" cy="64" r="56" stroke="url(#grad-circle)" stroke-width="8"
                                    fill="transparent" stroke-dasharray="{{ $circ }}" stroke-dashoffset="{{ $offs }}"
                                    stroke-linecap="round">
                                </circle>
                                <defs>
                                    <linearGradient id="grad-circle" x1="0%" y1="0%" x2="100%" y2="0%">
                                        <stop offset="0%" stop-color="#0f4c81" />
                                        <stop offset="100%" stop-color="#0d9488" />
                                    </linearGradient>
                                </defs>
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="text-2xl font-black text-gray-900">{{ $taskCompletionRate }}%</span>
                                <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Fait</span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 mt-4 font-medium">{{ $completedTasks }} tâches sur {{ $totalTasks }}
                        </p>
                    </div>
                </div>

                {{-- 2. Projects by Status --}}
                <div class="p-5 rounded-2xl border border-gray-100 bg-gradient-to-br from-gray-50/80 to-white">
                    <h3 class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-5 flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                        Projets par statut
                    </h3>
                    <div class="space-y-3">
                        <div
                            class="flex items-center justify-between p-3 bg-white rounded-xl border border-gray-100 hover:shadow-sm transition-all">
                            <div class="flex items-center gap-3">
                                <span class="w-2 h-2 rounded-full bg-amber-400 shrink-0"></span>
                                <span class="text-sm font-semibold text-gray-600">Préparation</span>
                            </div>
                            <span
                                class="text-sm font-black text-gray-900 bg-amber-50 px-2.5 py-0.5 rounded-lg border border-amber-100">{{ $projectsByStatus['preparation'] ?? 0 }}</span>
                        </div>
                        <div
                            class="flex items-center justify-between p-3 bg-white rounded-xl border border-gray-100 hover:shadow-sm transition-all">
                            <div class="flex items-center gap-3">
                                <span class="w-2 h-2 rounded-full bg-blue-500 shrink-0"></span>
                                <span class="text-sm font-semibold text-gray-600">En cours</span>
                            </div>
                            <span
                                class="text-sm font-black text-gray-900 bg-blue-50 px-2.5 py-0.5 rounded-lg border border-blue-100">{{ $projectsByStatus['in_progress'] ?? 0 }}</span>
                        </div>
                        <div
                            class="flex items-center justify-between p-3 bg-white rounded-xl border border-gray-100 hover:shadow-sm transition-all">
                            <div class="flex items-center gap-3">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                                <span class="text-sm font-semibold text-gray-600">Complété</span>
                            </div>
                            <span
                                class="text-sm font-black text-gray-900 bg-emerald-50 px-2.5 py-0.5 rounded-lg border border-emerald-100">{{ $projectsByStatus['completed'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>

                {{-- 3. Weekly Activity --}}
                <div class="p-5 rounded-2xl border border-gray-100 bg-gradient-to-br from-gray-50/80 to-white">
                    <h3 class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-5 flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                        </svg>
                        Dernière semaine
                    </h3>
                    <div class="h-32 flex items-end justify-between gap-2 px-1">
                        @php $maxActivity = max(array_merge([1], array_values($activityData))); @endphp
                        @foreach($activityData as $day => $count)
                            <div class="flex-1 flex flex-col items-center gap-2 group/bar cursor-help relative">
                                <div
                                    class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-[10px] px-2 py-1 rounded-lg opacity-0 group-hover/bar:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-10 shadow-lg">
                                    {{ $count }} tâche{{ $count !== 1 ? 's' : '' }}
                                </div>
                                <div class="w-full rounded-lg overflow-hidden"
                                    style="height: {{ max(8, intval(($count / $maxActivity) * 100)) }}%">
                                    <div
                                        class="w-full h-full bg-gradient-to-t from-primary/80 to-primary/40 group-hover/bar:from-primary group-hover/bar:to-primary/60 transition-all duration-300 rounded-lg">
                                    </div>
                                </div>
                                <span
                                    class="text-[10px] font-bold text-gray-400 group-hover/bar:text-primary transition-colors">{{ $day }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div>
            <h2 class="text-xl font-bold text-gray-900 mb-4 tracking-tight flex items-center gap-2">
                <span class="w-1 h-6 rounded-full bg-gradient-to-b from-violet-500 to-blue-500 inline-block"></span>
                Accès rapide
            </h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <a href="{{ route('projects.create') }}"
                    class="group relative overflow-hidden rounded-2xl p-5 font-bold transition-all duration-300 hover:-translate-y-1 hover:shadow-xl"
                    style="background: linear-gradient(135deg, #0f4c81, #1a68a8);">
                    <div class="relative z-10 flex flex-col gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                        </div>
                        <span class="text-white text-sm leading-tight">Nouveau Projet</span>
                    </div>
                </a>

                <a href="{{ route('teams.create') }}"
                    class="group relative overflow-hidden rounded-2xl p-5 font-bold transition-all duration-300 hover:-translate-y-1 hover:shadow-xl"
                    style="background: linear-gradient(135deg, #7c3aed, #6d28d9);">
                    <div class="relative z-10 flex flex-col gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                                </path>
                            </svg>
                        </div>
                        <span class="text-white text-sm leading-tight">Créer Équipe</span>
                    </div>
                </a>

                <a href="{{ route('projects.index') }}" class="group card-internal p-5 hover:-translate-y-1">
                    <div class="flex flex-col gap-3">
                        <div
                            class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                </path>
                            </svg>
                        </div>
                        <span class="font-bold text-gray-700 text-sm">Voir Projets</span>
                    </div>
                </a>

                <a href="{{ route('teams.index') }}" class="group card-internal p-5 hover:-translate-y-1">
                    <div class="flex flex-col gap-3">
                        <div
                            class="w-10 h-10 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center group-hover:bg-violet-600 group-hover:text-white transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z">
                                </path>
                            </svg>
                        </div>
                        <span class="font-bold text-gray-700 text-sm">Voir Équipes</span>
                    </div>
                </a>
            </div>
        </div>

        {{-- Recent Projects & Tasks --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 pb-4">
            {{-- Recent Projects --}}
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-900 tracking-tight">Projets récents</h2>
                    <a href="{{ route('projects.index') }}"
                        class="text-xs font-bold text-primary hover:text-primary-dark transition-colors">Tout voir →</a>
                </div>

                @if($recentProjects->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentProjects as $project)
                            @php
                                $statusMap = [
                                    'completed' => ['label' => 'Complété', 'class' => 'pill-success'],
                                    'in_progress' => ['label' => 'En cours', 'class' => 'pill-primary'],
                                    'preparation' => ['label' => 'Préparation', 'class' => 'pill-warning'],
                                ];
                                $statusInfo = $statusMap[$project->status] ?? $statusMap['preparation'];
                            @endphp
                            <div class="card-internal group p-5">
                                <div class="flex items-start justify-between mb-2">
                                    <div class="flex-1 min-w-0 pr-3">
                                        <h3
                                            class="text-base font-bold text-gray-900 group-hover:text-primary transition-colors truncate">
                                            {{ $project->title }}</h3>
                                        <p class="text-gray-400 text-xs mt-0.5 line-clamp-1">
                                            {{ \Illuminate\Support\Str::limit($project->description, 70) }}</p>
                                    </div>
                                    <span class="{{ $statusInfo['class'] }} shrink-0">{{ $statusInfo['label'] }}</span>
                                </div>
                                <div class="flex items-center justify-between pt-3 border-t border-gray-50">
                                    <span
                                        class="text-[11px] font-bold text-gray-400">{{ $project->created_at->translatedFormat('j M Y') }}</span>
                                    <a href="{{ route('projects.show', $project->id) }}"
                                        class="text-primary hover:text-primary-dark text-sm font-bold flex items-center gap-1 group/link transition-colors">
                                        Accéder
                                        <svg class="w-4 h-4 group-hover/link:translate-x-1 transition-transform" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center mb-4 text-blue-300">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                </path>
                            </svg>
                        </div>
                        <p class="text-gray-500 font-bold mb-3">Aucun projet pour le moment</p>
                        <a href="{{ route('projects.create') }}" class="btn-primary text-sm px-5 py-2">Créer un projet</a>
                    </div>
                @endif
            </div>

            {{-- Recent Tasks --}}
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-900 tracking-tight">Tâches à faire</h2>
                    <a href="{{ route('tasks.filter', ['status' => 'todo', 'priority' => '']) }}"
                        class="text-xs font-bold text-primary hover:text-primary-dark transition-colors">Tout voir →</a>
                </div>

                @if($recentTasks->count() > 0)
                    <div class="card-internal p-0 overflow-hidden">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b border-gray-100 bg-gray-50/60">
                                    <th
                                        class="px-5 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                        Tâche</th>
                                    <th
                                        class="px-5 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                        Projet</th>
                                    <th
                                        class="px-5 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest hidden sm:table-cell">
                                        Statut</th>
                                    <th
                                        class="px-5 py-3.5 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($recentTasks as $task)
                                    <tr class="group hover:bg-blue-50/30 transition-colors">
                                        <td class="px-5 py-3.5">
                                            <div class="flex flex-col">
                                                <span
                                                    class="text-sm font-bold text-gray-900 truncate max-w-[140px]">{{ $task->title }}</span>
                                                @if($task->due_date)
                                                    <span
                                                        class="text-[10px] font-semibold mt-0.5 {{ $task->due_date->isPast() && $task->status !== 'done' ? 'text-danger' : 'text-gray-400' }}">
                                                        {{ $task->due_date->format('d/m/Y') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-5 py-3.5">
                                            <span
                                                class="text-xs font-bold text-gray-500 bg-gray-100 px-2.5 py-1 rounded-lg truncate max-w-[90px] inline-block">{{ $task->project->title }}</span>
                                        </td>
                                        <td class="px-5 py-3.5 hidden sm:table-cell">
                                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-tight
                                                            @if($task->status === 'done') bg-emerald-100 text-emerald-700
                                                            @elseif($task->status === 'in_progress') bg-blue-100 text-blue-700
                                                            @else bg-gray-100 text-gray-600
                                                            @endif">
                                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-3.5 text-right">
                                            <a href="{{ route('tasks.show', $task->id) }}"
                                                class="text-gray-300 hover:text-primary transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center mb-4 text-emerald-300">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-gray-500 font-bold">Aucune tâche active 🎉</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection