@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 animate-slide-up">
        <!-- Dashboard Header -->
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 leading-tight">Bonjour, {{ Auth::user()->name }}</h1>
                <p class="text-gray-500 font-medium">Voici l'aperçu de votre activité aujourd'hui.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('projects.create') }}" class="px-5 py-2.5 bg-primary text-white rounded-2xl font-semibold shadow-lg shadow-primary/20 hover:scale-105 transition-all text-sm">
                    + Nouveau Projet
                </a>
            </div>
        </header>

        <!-- Bento Grid Layout -->
        <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-6 gap-6">
            
            <!-- Stats Section (Spans 1/2 of row) -->
            <div class="md:col-span-2 lg:col-span-3 grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-blue-600 rounded-3xl p-6 text-white flex flex-col justify-between shadow-xl shadow-blue-500/20 group">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mb-4 transition-transform group-hover:rotate-12">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                    </div>
                    <div>
                        <span class="text-sm font-medium opacity-80 uppercase tracking-wider">Projets</span>
                        <div class="text-4xl font-bold mt-1">{{ $projectsCount }}</div>
                    </div>
                </div>
                
                <div class="bg-gray-900 rounded-3xl p-6 text-white flex flex-col justify-between shadow-xl shadow-gray-900/10 group">
                    <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center mb-4 transition-transform group-hover:rotate-12">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                    <div>
                        <span class="text-sm font-medium opacity-80 uppercase tracking-wider">Tâches</span>
                        <div class="text-4xl font-bold mt-1">{{ $tasksCount }}</div>
                    </div>
                </div>

                <div class="bg-indigo-600 rounded-3xl p-6 text-white flex flex-col justify-between shadow-xl shadow-indigo-500/20 group">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mb-4 transition-transform group-hover:rotate-12">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <div>
                        <span class="text-sm font-medium opacity-80 uppercase tracking-wider">Équipes</span>
                        <div class="text-4xl font-bold mt-1">{{ $teamsCount }}</div>
                    </div>
                </div>
            </div>

            <!-- Quick Access / Actions (Spans 1/2 of row) -->
            <div class="md:col-span-2 lg:col-span-3 card-premium flex flex-col justify-center">
                <h3 class="text-lg font-bold mb-4">Actions Rapides</h3>
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('tasks.create') }}" class="flex items-center gap-3 p-4 rounded-2xl bg-gray-50 border border-gray-100 hover:border-primary/30 hover:bg-primary/5 transition-all group">
                        <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </div>
                        <span class="font-semibold text-gray-700 text-sm">Nouvelle Tâche</span>
                    </a>
                    <a href="{{ route('search') }}" class="flex items-center gap-3 p-4 rounded-2xl bg-gray-50 border border-gray-100 hover:border-primary/30 hover:bg-primary/5 transition-all group">
                        <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-gray-500 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <span class="font-semibold text-gray-700 text-sm">Rechercher</span>
                    </a>
                </div>
            </div>

            <!-- Recent Projects (Large Section) -->
            <div class="md:col-span-4 lg:col-span-4 card-premium overflow-hidden">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold">Projets Récents</h3>
                    <a href="{{ route('projects.index') }}" class="text-sm font-semibold text-primary hover:underline">Tout voir</a>
                </div>
                <div class="space-y-4">
                    @forelse($recentProjects as $project)
                        <div class="flex items-center gap-4 p-4 rounded-2xl bg-gray-50/50 border border-gray-100 hover:bg-white hover:shadow-lg transition-all group">
                            <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-lg group-hover:rotate-6 transition-transform">
                                {{ substr($project->title, 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-bold text-gray-900 truncate">{{ $project->title }}</h4>
                                <p class="text-xs text-gray-500 truncate">{{ $project->description }}</p>
                            </div>
                            <div class="text-right">
                                <span class="px-3 py-1 bg-white border border-gray-200 rounded-full text-[10px] font-bold uppercase tracking-wider text-gray-600">
                                    {{ $project->status }}
                                </span>
                            </div>
                            <a href="{{ route('projects.show', $project->id) }}" class="p-2 text-gray-400 hover:text-primary transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    @empty
                        <div class="text-center py-8 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                            <p class="text-gray-400 text-sm italic">Aucun projet récemment modifié.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Tasks (Small/Side Section) -->
            <div class="md:col-span-2 lg:col-span-2 card-premium">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold">Tâches</h3>
                    <a href="{{ route('tasks.index') }}" class="text-sm font-semibold text-gray-400 hover:text-primary">Liste</a>
                </div>
                <div class="space-y-3">
                    @forelse($recentTasks as $task)
                        <div class="flex items-start gap-3">
                            <div class="mt-1">
                               @if($task->status === 'done')
                                   <div class="w-5 h-5 rounded-full bg-success/20 text-success flex items-center justify-center">
                                       <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                   </div>
                               @else
                                   <div class="w-5 h-5 rounded-full border-2 border-gray-200"></div>
                               @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('tasks.show', $task->id) }}" class="text-sm font-semibold text-gray-900 hover:text-primary block truncate">{{ $task->title }}</a>
                                <p class="text-[10px] uppercase font-bold text-gray-400 mt-0.5">{{ $task->project ? $task->project->title : 'Sans projet' }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-center py-4 text-gray-400 text-sm italic">Pas de tâches.</p>
                    @endforelse
                </div>
                <div class="mt-6 pt-6 border-t border-gray-50">
                    <div class="bg-gray-50 rounded-2xl p-4 flex items-center justify-between">
                        <span class="text-sm font-bold text-gray-700">Progression globale</span>
                        <div class="w-12 h-12 rounded-full border-4 border-primary border-t-transparent flex items-center justify-center text-[10px] font-bold text-primary">
                            65%
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection