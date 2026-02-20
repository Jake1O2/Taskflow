@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 animate-slide-in-up">
        @php
            $taskCollection = $project->tasks; // already loaded via with('tasks')
            $total = $taskCollection->count();
            $done = $taskCollection->where('status', 'done')->count();
            $pct = $total > 0 ? round(($done / $total) * 100) : 0;

            $statusLabels = [
                'preparation' => ['label' => 'En préparation', 'class' => 'pill-warning'],
                'in_progress' => ['label' => 'En cours', 'class' => 'pill-primary'],
                'completed' => ['label' => 'Terminé', 'class' => 'pill-success'],
            ];
            $statusInfo = $statusLabels[$project->status] ?? ['label' => ucfirst($project->status), 'class' => 'pill-neutral'];
        @endphp

        {{-- Project Header --}}
        <header class="relative overflow-hidden rounded-3xl p-8 sm:p-10 text-white shadow-2xl shadow-indigo-500/15"
            style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #1e3a5f 100%);">
            <div class="absolute inset-0 grain-texture pointer-events-none opacity-[0.04]"></div>
            <div class="absolute -right-24 -top-24 h-72 w-72 rounded-full bg-white/5"></div>
            <div class="absolute right-12 -bottom-12 h-48 w-48 rounded-full bg-indigo-400/10"></div>

            <div class="relative z-10 flex flex-col md:flex-row justify-between items-start gap-6">
                <div class="flex items-center gap-5">
                    <div
                        class="w-16 h-16 rounded-3xl bg-white/10 backdrop-blur-sm flex items-center justify-center text-3xl font-bold shrink-0 border border-white/20">
                        {{ strtoupper(substr($project->title, 0, 1)) }}
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight">{{ $project->title }}</h1>
                        <div class="flex flex-wrap items-center gap-2.5 mt-3">
                            <span class="{{ $statusInfo['class'] }}">{{ $statusInfo['label'] }}</span>
                            <span class="text-xs text-white/50 font-medium">
                                {{ $project->team ? $project->team->name : 'Individuel' }}
                            </span>
                            @if($project->end_date)
                                <span class="text-xs text-white/50 font-medium flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ \Carbon\Carbon::parse($project->end_date)->translatedFormat('j F Y') }}
                                </span>
                            @endif
                        </div>
                        <div class="mt-4 flex items-center gap-3">
                            <div class="w-40 h-1.5 rounded-full bg-white/20 overflow-hidden">
                                <div class="h-full rounded-full bg-white transition-all duration-1000"
                                    style="width: {{ $pct }}%;"></div>
                            </div>
                            <span class="text-xs font-bold text-white/70">{{ $pct }}% complété</span>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('projects.kanban', $project->id) }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-indigo-900 rounded-2xl font-bold text-sm shadow-lg transition-all hover:scale-105">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 002-2h-2a2 2 0 00-2 2">
                            </path>
                        </svg>
                        Kanban
                    </a>
                    <a href="{{ route('projects.calendar', $project->id) }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white rounded-2xl font-bold text-sm transition-all border border-white/15">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        Calendrier
                    </a>
                    <a href="{{ route('projects.edit', $project->id) }}"
                        class="inline-flex items-center justify-center w-10 h-10 bg-white/8 hover:bg-white/15 text-white rounded-2xl transition-all border border-white/10">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                    </a>
                </div>
            </div>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Main Column --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Description --}}
                <section class="card-premium">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <span
                            class="w-1 h-5 rounded-full bg-gradient-to-b from-indigo-500 to-violet-500 inline-block"></span>
                        Description
                    </h2>
                    <p class="text-gray-500 leading-relaxed {{ !$project->description ? 'italic' : '' }}">
                        {{ $project->description ?: 'Aucune description fournie pour ce projet.' }}
                    </p>
                </section>

                {{-- Tasks --}}
                <section class="card-premium">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <span
                                class="w-1 h-5 rounded-full bg-gradient-to-b from-teal-500 to-emerald-500 inline-block"></span>
                            Tâches
                            <span
                                class="ml-1 text-sm font-semibold text-gray-400 bg-gray-100 px-2.5 py-0.5 rounded-lg">{{ $total }}</span>
                        </h2>
                        <a href="{{ route('tasks.create', $project->id) }}"
                            class="text-sm font-bold text-primary hover:text-primary-dark transition-colors flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Ajouter
                        </a>
                    </div>

                    <div class="space-y-2">
                        @forelse($taskCollection as $task)
                            @php $taskDone = $task->status === 'done'; @endphp
                            <div
                                class="flex items-center gap-4 p-4 rounded-2xl bg-gray-50/60 hover:bg-white border border-transparent hover:border-gray-100 hover:shadow-sm transition-all group">
                                <div
                                    class="shrink-0 w-2 h-10 rounded-full {{ $taskDone ? 'bg-gradient-to-b from-emerald-400 to-green-600' : ($task->status === 'in_progress' ? 'bg-gradient-to-b from-blue-400 to-blue-600' : 'bg-gray-200') }}">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4
                                        class="font-bold text-gray-900 {{ $taskDone ? 'line-through opacity-40' : 'group-hover:text-primary transition-colors' }}">
                                        {{ $task->title }}</h4>
                                    @if($task->due_date)
                                        <p
                                            class="text-[10px] font-bold {{ \Carbon\Carbon::parse($task->due_date)->isPast() && !$taskDone ? 'text-danger' : 'text-gray-400' }} uppercase tracking-widest mt-0.5">
                                            {{ \Carbon\Carbon::parse($task->due_date)->translatedFormat('j M Y') }}
                                        </p>
                                    @endif
                                </div>
                                @if($taskDone)
                                    <span class="pill-success shrink-0">Fait</span>
                                @elseif($task->status === 'in_progress')
                                    <span class="pill-primary shrink-0">En cours</span>
                                @else
                                    <span class="pill-neutral shrink-0">À faire</span>
                                @endif
                                <a href="{{ route('tasks.show', $task->id) }}"
                                    class="shrink-0 p-2 text-gray-300 hover:text-primary transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                        </path>
                                    </svg>
                                </a>
                            </div>
                        @empty
                            <div class="empty-state py-12">
                                <p class="text-gray-400 font-medium italic mb-3">Aucune tâche assignée.</p>
                                <a href="{{ route('tasks.create', $project->id) }}" class="btn-secondary text-sm px-4 py-2">+
                                    Créer une tâche</a>
                            </div>
                        @endforelse
                    </div>
                </section>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-5">
                {{-- Stats Card --}}
                <div class="relative overflow-hidden rounded-3xl p-6 text-white shadow-xl"
                    style="background: linear-gradient(135deg, #0f4c81 0%, #0d9488 100%);">
                    <div class="absolute -right-8 -top-8 w-32 h-32 rounded-full bg-white/5"></div>
                    <h3 class="font-bold text-white/80 text-sm mb-4 uppercase tracking-widest">Progression</h3>
                    <div class="flex items-end justify-between mb-4">
                        <div>
                            <div class="text-4xl font-black tracking-tight">{{ $pct }}%</div>
                            <p class="text-sm font-medium text-white/60 mt-1">{{ $done }}/{{ $total }} tâches</p>
                        </div>
                        @php
                            $circ = 2 * 3.14159 * 26;
                            $offset = $circ * (1 - $pct / 100);
                        @endphp
                        <div class="relative w-16 h-16">
                            <svg class="w-full h-full transform -rotate-90" viewBox="0 0 64 64">
                                <circle cx="32" cy="32" r="26" stroke="white" stroke-opacity="0.2" stroke-width="5"
                                    fill="none" />
                                <circle cx="32" cy="32" r="26" stroke="white" stroke-width="5" fill="none"
                                    stroke-dasharray="{{ $circ }}" stroke-dashoffset="{{ $offset }}"
                                    stroke-linecap="round" />
                            </svg>
                        </div>
                    </div>
                    <div class="w-full h-1.5 rounded-full bg-white/20 overflow-hidden">
                        <div class="h-full rounded-full bg-white" style="width: {{ $pct }}%;"></div>
                    </div>
                </div>

                {{-- Info Card --}}
                <div class="card-premium">
                    <h2 class="text-base font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Informations
                    </h2>
                    <div class="space-y-0">
                        <div class="flex items-center justify-between py-3 border-b border-gray-50">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Créé le</span>
                            <span
                                class="text-sm font-bold text-gray-900">{{ $project->created_at->translatedFormat('j F Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-gray-50">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Responsable</span>
                            <span
                                class="text-sm font-bold text-gray-900">{{ $project->user ? $project->user->name : 'N/A' }}</span>
                        </div>
                        @if($project->end_date)
                            <div class="flex items-center justify-between py-3">
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Échéance</span>
                                <span
                                    class="text-sm font-bold {{ \Carbon\Carbon::parse($project->end_date)->isPast() ? 'text-danger' : 'text-gray-900' }}">
                                    {{ \Carbon\Carbon::parse($project->end_date)->translatedFormat('j F Y') }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection