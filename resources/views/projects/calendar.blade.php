@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4 animate-slide-down">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    <span class="text-teal-600">📅</span> Calendrier: <span class="text-gray-600">{{ $project->title }}</span>
                </h2>
                <div class="flex gap-3">
                    <a href="{{ route('projects.show', $project->id) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition ease-in-out duration-150">
                        &larr; Retour
                    </a>
                    <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:from-blue-700 hover:to-blue-800 shadow-md transform hover:-translate-y-0.5 transition-all duration-200">
                        + Ajouter une tâche
                    </a>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 border border-gray-100 animate-fade" style="animation-delay: 0.1s">
                {{-- Navigation Mois --}}
                <div class="flex justify-between items-center mb-6 bg-gray-50 p-4 rounded-lg">
                    <a href="{{ route('projects.calendar', [$project->id, 'date' => $date->copy()->subMonth()->format('Y-m-d')]) }}" class="text-gray-600 hover:text-blue-600 font-bold px-4 py-2 rounded hover:bg-white transition-colors shadow-sm">
                        &larr; Précédent
                    </a>
                    <h3 class="text-xl font-bold text-gray-800 capitalize flex items-center gap-2">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        {{ $date->isoFormat('MMMM YYYY') }}
                    </h3>
                    <a href="{{ route('projects.calendar', [$project->id, 'date' => $date->copy()->addMonth()->format('Y-m-d')]) }}" class="text-gray-600 hover:text-blue-600 font-bold px-4 py-2 rounded hover:bg-white transition-colors shadow-sm">
                        Suivant &rarr;
                    </a>
                </div>

                {{-- Grille Calendrier --}}
                <div class="grid grid-cols-7 gap-px bg-gray-200 border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                    {{-- En-têtes Jours --}}
                    @foreach(['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'] as $dayName)
                        <div class="bg-gray-100 py-3 text-center font-bold text-gray-600 uppercase text-xs tracking-wider">{{ $dayName }}</div>
                    @endforeach

                    {{-- Cases vides début de mois --}}
                    @for ($i = 1; $i < $date->startOfMonth()->dayOfWeekIso; $i++)
                        <div class="bg-white h-32 p-2 bg-gray-50/50"></div>
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
                        <div class="bg-white h-32 p-2 border-t border-gray-100 hover:bg-gray-50 transition relative overflow-y-auto group {{ $isToday ? 'bg-blue-50/30 ring-inset ring-2 ring-blue-200' : '' }}">
                            <div class="font-bold mb-2 text-sm flex justify-between items-center">
                                <span class="{{ $isToday ? 'bg-blue-600 text-white w-6 h-6 rounded-full flex items-center justify-center shadow-sm' : 'text-gray-700' }}">{{ $day }}</span>
                                @if($dayTasks->count() > 0)
                                    <span class="text-xs text-gray-400 group-hover:text-blue-500">{{ $dayTasks->count() }} tache(s)</span>
                                @endif
                            </div>
                            <div class="space-y-1.5">
                                @foreach($dayTasks as $task)
                                    @php
                                        $colors = [
                                            'todo' => 'bg-gray-100 text-gray-700 hover:bg-gray-200 border-gray-200',
                                            'in_progress' => 'bg-blue-100 text-blue-700 hover:bg-blue-200 border-blue-200',
                                            'done' => 'bg-green-100 text-green-700 hover:bg-green-200 border-green-200',
                                        ];
                                        $colorClass = $colors[$task->status] ?? $colors['todo'];
                                    @endphp
                                    <a href="{{ route('tasks.edit', $task->id) }}" class="block text-xs px-2 py-1 rounded truncate border {{ $colorClass }} transition-colors shadow-sm" title="{{ $task->title }} ({{ ucfirst($task->status) }})">
                                        {{ $task->title }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
@endsection