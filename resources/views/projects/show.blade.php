@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 animate-slide-up">
        <!-- Project Header -->
        <header class="card-premium bg-gradient-to-br from-indigo-900 to-purple-950 text-white border-none p-8 flex flex-col md:flex-row justify-between items-start gap-6 shadow-2xl shadow-indigo-500/10">
            <div class="flex items-center gap-6">
                <div class="w-20 h-20 rounded-3xl bg-white/10 flex items-center justify-center text-4xl font-bold shadow-soft shrink-0">
                    {{ substr($project->title, 0, 1) }}
                </div>
                <div>
                    <h1 class="text-3xl font-bold">{{ $project->title }}</h1>
                    <div class="flex flex-wrap items-center gap-3 mt-3">
                        @php
                            $statusLabels = [
                                'preparation' => 'En préparation',
                                'en_cours' => 'En cours',
                                'termine' => 'Terminé',
                            ];
                        @endphp
                        <span class="px-3 py-1 bg-white/10 rounded-full text-[10px] font-bold uppercase tracking-widest border border-white/20">
                            {{ $statusLabels[$project->status] ?? $project->status }}
                        </span>
                        <span class="text-xs opacity-60 font-medium">Équipe : {{ $project->team ? $project->team->name : 'Individuel' }}</span>
                        <span class="text-xs opacity-60 font-medium">Echéance : {{ $project->due_date ? \Carbon\Carbon::parse($project->due_date)->format('d/m/Y') : 'Non définie' }}</span>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('projects.kanban', $project->id) }}" class="px-5 py-2.5 bg-white text-indigo-900 rounded-2xl font-bold text-sm shadow-xl transition-all hover:scale-105 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 002-2h-2a2 2 0 00-2 2"></path></svg>
                    Mode Kanban
                </a>
                <a href="{{ route('projects.calendar', $project->id) }}" class="px-5 py-2.5 bg-white text-teal-700 rounded-2xl font-bold text-sm shadow-xl transition-all hover:scale-105 border border-white flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Calendrier
                </a>
                
                <a href="{{ route('projects.export.pdf', $project->id) }}" class="px-5 py-2.5 bg-white/10 hover:bg-white/20 text-white rounded-2xl font-bold text-sm transition-all border border-white/10 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.707 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    Exporter PDF
                </a>
                <a href="{{ route('projects.export.csv', $project->id) }}" class="px-5 py-2.5 bg-white/10 hover:bg-white/20 text-white rounded-2xl font-bold text-sm transition-all border border-white/10 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 00-2 2v1M9 3h6m-6 4h6m2 10H7a2 2 0 00-2 2v1"></path></svg>
                    Exporter CSV
                </a>

                <a href="{{ route('projects.edit', $project->id) }}" class="p-2.5 bg-white/5 hover:bg-white/10 text-white rounded-2xl transition-all border border-white/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </a>
            </div>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Info (Spans 2) -->
            <div class="lg:col-span-2 space-y-8">
                <section class="card-premium">
                    <h2 class="text-xl font-bold mb-4">Description</h2>
                    <p class="text-gray-600 leading-relaxed text-lg italic">
                        "{{ $project->description ?: 'Aucune description fournie pour ce projet.' }}"
                    </p>
                </section>

                <section class="card-premium">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold">Tâches</h2>
                        <a href="{{ route('tasks.create', ['projectId' => $project->id]) }}" class="text-sm font-bold text-primary hover:underline">+ Ajouter une tâche</a>
                    </div>
                    
                    <div class="space-y-3">
                        @forelse($project->tasks as $task)
                            <div class="flex items-center gap-4 p-4 rounded-2xl bg-gray-50/50 hover:bg-white border border-transparent hover:border-gray-100 transition-all group">
                                <div class="w-2 h-8 rounded-full {{ $task->status === 'done' ? 'bg-success' : 'bg-gray-200' }}"></div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-gray-900 {{ $task->status === 'done' ? 'line-through opacity-50' : '' }}">{{ $task->title }}</h4>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') : 'Pas de date' }}</p>
                                </div>
                                <a href="{{ route('tasks.show', $task->id) }}" class="p-2 text-gray-400 hover:text-primary transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </a>
                            </div>
                        @empty
                            <div class="text-center py-12 border-2 border-dashed border-gray-100 rounded-3xl">
                                <p class="text-gray-400 font-medium italic">Aucune tâche assignée.</p>
                            </div>
                        @endforelse
                    </div>
                </section>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <div class="card-premium">
                    <h2 class="text-xl font-bold mb-4">Informations</h2>
                    <div class="space-y-4">
                        <div>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Créé le</span>
                            <p class="text-sm font-bold text-gray-900">{{ $project->created_at->format('d F Y') }}</p>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Responsable</span>
                            <p class="text-sm font-bold text-gray-900">{{ $project->creator ? $project->creator->name : 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <div class="card-premium bg-blue-600 text-white border-none shadow-xl shadow-blue-500/10">
                    <h3 class="font-bold text-lg mb-2">Statistiques</h3>
                    <div class="flex items-end justify-between">
                        <div>
                            <div class="text-3xl font-bold">{{ count($project->tasks->where('status', 'done')) }} / {{ count($project->tasks) }}</div>
                            <p class="text-xs font-medium opacity-80">Tâches terminées</p>
                        </div>
                        <div class="w-12 h-12 rounded-full border-4 border-white/20 border-t-white flex items-center justify-center text-[10px] font-bold">
                            {{ count($project->tasks) > 0 ? round((count($project->tasks->where('status', 'done')) / count($project->tasks)) * 100) : 0 }}%
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection