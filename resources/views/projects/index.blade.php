@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 animate-slide-up">
        <header class="flex flex-col sm:row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Projets</h1>
                <p class="text-gray-500 font-medium mt-1">Gérez et suivez l'avancement de vos projets.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('projects.create') }}"
                    class="px-5 py-2.5 bg-primary text-white rounded-2xl font-bold text-sm shadow-lg shadow-primary/20 hover:scale-105 transition-all">
                    + Nouveau Projet
                </a>
            </div>
        </header>

        @if(session('success'))
            <div class="glass p-4 rounded-2xl border-l-4 border-success flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-success/20 text-success flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <p class="text-sm font-bold text-gray-900">{{ session('success') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($projects as $project)
                <div class="card-premium group relative flex flex-col">
                    <div class="flex items-center gap-4 mb-5">
                        <div
                            class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center font-bold text-xl shadow-lg group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shrink-0">
                            {{ substr($project->title, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-base font-bold text-gray-900 truncate group-hover:text-primary transition-colors">
                                {{ $project->title }}
                            </h3>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span
                                    class="px-2 py-0.5 rounded-lg bg-gray-100 text-[10px] font-bold text-gray-500 uppercase tracking-wider">
                                    {{ $project->status }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <p class="text-xs text-gray-500 line-clamp-2 mb-6 leading-relaxed flex-1">
                        {{ $project->description ?: 'Aucune description fournie.' }}
                    </p>

                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-2">
                            <div class="flex -space-x-2">
                                <div
                                    class="w-6 h-6 rounded-lg bg-indigo-50 border-2 border-white flex items-center justify-center text-[10px] font-bold text-indigo-400">
                                    ?</div>
                            </div>
                            <span class="text-[10px] font-bold text-gray-400">
                                {{ $project->tasks_count ?? 0 }} tâches
                            </span>
                        </div>
                        <div class="text-[10px] font-bold text-gray-400 italic">
                            {{ $project->created_at->diffForHumans() }}
                        </div>
                    </div>

                    <div class="flex items-center gap-2 border-t border-gray-50 pt-5 mt-auto">
                        <a href="{{ route('projects.show', $project->id) }}"
                            class="flex-1 flex items-center justify-center gap-1.5 py-2.5 rounded-xl bg-gray-50 text-gray-700 font-bold text-sm hover:bg-primary/8 hover:text-primary transition-all">
                            Voir détails
                        </a>
                        <a href="{{ route('projects.kanban', $project->id) }}"
                            class="p-2.5 text-gray-400 hover:text-primary hover:bg-indigo-50 rounded-xl transition-all"
                            title="Tableau Kanban">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 002-2h-2a2 2 0 00-2 2">
                                </path>
                            </svg>
                        </a>
                        <a href="{{ route('projects.edit', $project->id) }}"
                            class="p-2.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all"
                            title="Modifier">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                        </a>
                    </div>
                </div>
            @empty
                <div
                    class="col-span-full py-20 card-premium text-center border-dashed border-2 flex flex-col items-center justify-center gap-4">
                    <div class="w-16 h-16 rounded-3xl bg-gray-50 text-gray-300 flex items-center justify-center">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-400 font-bold mb-1">Aucun projet trouvé</p>
                        <p class="text-xs text-gray-400">Commencez par créer votre premier projet pour organiser vos tâches.</p>
                    </div>
                    <a href="{{ route('projects.create') }}" class="mt-2 btn-primary">
                        Créer un projet
                    </a>
                </div>
            @endforelse
        </div>
    </div>
@endsection