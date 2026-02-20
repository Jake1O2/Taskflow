@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 animate-slide-in-up">
        {{-- Header --}}
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Projets</h1>
                <p class="text-gray-400 font-medium mt-1">Suivez l'avancement de vos travaux.</p>
            </div>
            <a href="{{ route('projects.create') }}"
                class="btn-primary inline-flex items-center gap-2 px-5 py-2.5 text-sm rounded-2xl shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                Nouveau Projet
            </a>
        </header>

        @if(session('success'))
            <div
                class="flex items-center gap-3 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 animate-fade-in">
                <div class="shrink-0 w-8 h-8 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <span class="font-semibold text-sm">{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($projects as $project)
                @php
                    $gradients = [
                        'from-indigo-400 to-violet-600',
                        'from-blue-400 to-cyan-600',
                        'from-teal-400 to-emerald-600',
                        'from-rose-400 to-pink-600',
                        'from-orange-400 to-amber-600',
                        'from-purple-400 to-indigo-600',
                    ];
                    $gradClass = $gradients[abs(crc32($project->title)) % count($gradients)];

                    $statusMap = [
                        'preparation' => ['label' => 'Préparation', 'class' => 'pill-warning'],
                        'in_progress' => ['label' => 'En cours', 'class' => 'pill-primary'],
                        'completed' => ['label' => 'Terminé', 'class' => 'pill-success'],
                    ];
                    $statusInfo = $statusMap[$project->status] ?? ['label' => ucfirst($project->status), 'class' => 'pill-neutral'];

                    // tasks_count is loaded via withCount('tasks')
                    $taskCount = $project->tasks_count ?? 0;
                @endphp
                <div class="card-premium group relative flex flex-col">
                    {{-- Card Top --}}
                    <div class="flex items-center gap-4 mb-5">
                        <div
                            class="w-12 h-12 rounded-2xl bg-gradient-to-br {{ $gradClass }} text-white flex items-center justify-center font-bold text-xl shadow-lg group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shrink-0">
                            {{ strtoupper(substr($project->title, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-base font-bold text-gray-900 truncate group-hover:text-primary transition-colors">
                                {{ $project->title }}</h3>
                            <p class="text-[11px] text-gray-400 font-semibold uppercase tracking-widest mt-0.5">
                                {{ $project->team ? $project->team->name : 'Personnel' }}
                            </p>
                        </div>
                    </div>

                    <p class="text-sm text-gray-500 line-clamp-2 mb-5 flex-1 leading-relaxed">
                        {{ $project->description ?: 'Aucune description.' }}
                    </p>

                    {{-- Status & Task count --}}
                    <div class="flex items-center justify-between mb-4">
                        <span class="{{ $statusInfo['class'] }}">{{ $statusInfo['label'] }}</span>
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                            {{ $taskCount }} tâche{{ $taskCount !== 1 ? 's' : '' }}
                        </span>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-2 border-t border-gray-50 pt-5">
                        <a href="{{ route('projects.show', $project->id) }}"
                            class="flex-1 flex items-center justify-center gap-1.5 py-2.5 rounded-xl bg-gray-50 text-gray-700 font-bold text-sm hover:bg-blue-50 hover:text-primary transition-all">
                            Ouvrir
                        </a>
                        <a href="{{ route('projects.kanban', $project->id) }}"
                            class="p-2.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all"
                            title="Kanban">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 002-2h-2a2 2 0 00-2 2">
                                </path>
                            </svg>
                        </a>
                        <a href="{{ route('projects.calendar', $project->id) }}"
                            class="p-2.5 text-gray-400 hover:text-teal-600 hover:bg-teal-50 rounded-xl transition-all"
                            title="Calendrier">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="empty-state">
                        <div class="w-16 h-16 rounded-3xl bg-blue-50 flex items-center justify-center mb-4 text-blue-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                </path>
                            </svg>
                        </div>
                        <p class="text-gray-600 font-bold mb-1">Aucun projet trouvé</p>
                        <p class="text-gray-400 text-sm mb-4">Commencez par créer votre premier projet.</p>
                        <a href="{{ route('projects.create') }}" class="btn-primary text-sm px-5 py-2.5">Créer votre premier
                            projet</a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection