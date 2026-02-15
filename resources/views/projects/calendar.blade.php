@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4 animate-slide-down">
                <div>
                     <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                        <span class="text-teal-600 bg-teal-50 p-1.5 rounded-lg border border-teal-100">📅</span> 
                        Calendrier: <span class="text-gray-600 ml-1">{{ $project->title }}</span>
                    </h2>
                     <p class="text-sm text-gray-500 mt-1 ml-11">Vue mensuelle des tâches</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('projects.show', $project->id) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-medium text-sm text-gray-700 shadow-sm hover:bg-gray-50 hover:text-gray-900 transition-all duration-200">
                        &larr; Retour au projet
                    </a>
                    <a href="{{ route('tasks.create', ['projectId' => $project->id]) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-medium text-sm text-white shadow-sm hover:bg-blue-700 hover:shadow-md transform hover:-translate-y-0.5 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Ajouter une tâche
                    </a>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm shadow-gray-200 rounded-xl border border-gray-200 animate-fade-in" style="animation-delay: 100ms;">
                {{-- Navigation Mois --}}
                <div class="flex justify-between items-center p-6 border-b border-gray-100">
                    <a href="{{ route('projects.calendar', [$project->id, 'date' => $date->copy()->subMonth()->format('Y-m-d')]) }}" class="p-2 rounded-lg hover:bg-gray-100 text-gray-600 transition-colors" title="Mois précédent">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                    <h3 class="text-xl font-bold text-gray-900 capitalize flex items-center gap-2">
                        {{ $date->isoFormat('MMMM YYYY') }}
                    </h3>
                    <a href="{{ route('projects.calendar', [$project->id, 'date' => $date->copy()->addMonth()->format('Y-m-d')]) }}" class="p-2 rounded-lg hover:bg-gray-100 text-gray-600 transition-colors" title="Mois suivant">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>

                <div class="p-6">
                    {{-- Grille Calendrier --}}
                    <div class="grid grid-cols-7 border-t border-l border-gray-200 bg-gray-200 rounded-lg overflow-hidden gap-px">
                        {{-- En-têtes Jours --}}
                        @foreach(['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'] as $dayName)
                            <div class="bg-gray-50 py-3 text-center font-bold text-gray-500 uppercase text-xs tracking-wider border-b border-r border-gray-100">{{ $dayName }}</div>
                        @endforeach

                        {{-- Cases vides début de mois --}}
                        @for ($i = 1; $i < $date->startOfMonth()->dayOfWeekIso; $i++)
                            <div class="bg-gray-50/30 h-32 p-2 relative"></div>
                        @endfor

                        {{-- Jours du mois --}}
                        @for ($day = 1; $day <= $date->daysInMonth; $day++)
                            @php
                                $currentDate = $date->copy()->day($day);
                                $dayTasks = $tasks->filter(function($task) use ($currentDate) {
                                    return \Carbon\Carbon::parse($task->due_date)->isSameDay($currentDate);
                                });
                                $isToday = $currentDate->isToday();
                            @endphp
                            <div class="bg-white h-32 p-2 hover:bg-gray-50 transition-colors relative overflow-y-auto group {{ $isToday ? 'bg-blue-50/20' : '' }}">
                                <div class="flex justify-between items-start mb-2">
                                     <span class="text-sm font-semibold {{ $isToday ? 'bg-blue-600 text-white w-7 h-7 flex items-center justify-center rounded-full shadow-sm' : 'text-gray-700' }}">
                                        {{ $day }}
                                    </span>
                                </div>
                                
                                <div class="space-y-1">
                                    @foreach($dayTasks as $task)
                                        @php
                                            $colors = [
                                                'todo' => 'bg-gray-100 text-gray-700 border-gray-200 hover:border-gray-300',
                                                'in_progress' => 'bg-blue-50 text-blue-700 border-blue-100 hover:border-blue-300',
                                                'done' => 'bg-emerald-50 text-emerald-700 border-emerald-100 hover:border-emerald-300 line-through opacity-70',
                                            ];
                                            $colorClass = $colors[$task->status] ?? $colors['todo'];
                                        @endphp
                                        <a href="{{ route('tasks.edit', $task->id) }}" class="block text-[10px] px-1.5 py-1 rounded border {{ $colorClass }} transition-all truncate font-medium leading-tight" title="{{ $task->title }}">
                                            {{ $task->title }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endfor
                        
                         {{-- Cases vides fin de mois pour compléter la grille --}}
                        @php
                             $remainingCells = (7 - (($date->startOfMonth()->dayOfWeekIso - 1 + $date->daysInMonth) % 7)) % 7;
                        @endphp
                         @for ($i = 0; $i < $remainingCells; $i++)
                            <div class="bg-gray-50/30 h-32 p-2"></div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection