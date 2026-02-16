@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 animate-slide-up">
        <header class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h1 class="text-2xl font-extrabold text-gray-900 flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-teal-600 text-white flex items-center justify-center flex-shrink-0 shadow-lg shadow-teal-500/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </span>
                Calendrier : <span class="text-gray-500 font-medium">{{ $project->title }}</span>
            </h1>
            <div class="flex gap-2">
                <a href="{{ route('projects.show', $project->id) }}" class="px-5 py-2.5 bg-white border border-gray-100 text-gray-700 rounded-2xl font-bold text-sm shadow-sm hover:bg-gray-50 transition-all">
                    Retour
                </a>
            </div>
        </header>

        <div class="card-premium !p-0 overflow-hidden shadow-2xl border-none">
            {{-- Navigation Mois --}}
            <div class="flex justify-between items-center p-6 bg-gray-900 text-white">
                <a href="{{ route('projects.calendar', [$project->id, 'date' => $date->copy()->subMonth()->format('Y-m-d')]) }}" class="p-2 hover:bg-white/10 rounded-xl transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h3 class="text-xl font-extrabold capitalize">{{ $date->isoFormat('MMMM YYYY') }}</h3>
                <a href="{{ route('projects.calendar', [$project->id, 'date' => $date->copy()->addMonth()->format('Y-m-d')]) }}" class="p-2 hover:bg-white/10 rounded-xl transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>

            {{-- Grille Calendrier --}}
            <div class="grid grid-cols-7 bg-gray-50">
                {{-- En-têtes Jours --}}
                @foreach(['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'] as $dayName)
                    <div class="py-4 text-center font-extrabold text-gray-400 uppercase text-[10px] tracking-widest border-b border-gray-100">{{ $dayName }}</div>
                @endforeach

                {{-- Cases vides début de mois --}}
                @for ($i = 1; $i < $date->startOfMonth()->dayOfWeekIso; $i++)
                    <div class="h-32 border-r border-b border-gray-100"></div>
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
                    <div class="h-32 p-3 border-r border-b border-gray-100 bg-white hover:bg-gray-50 transition-colors relative group {{ $isToday ? 'bg-primary/5' : '' }}">
                        <div class="font-extrabold text-xs mb-2 flex justify-between items-center">
                            <span class="{{ $isToday ? 'w-6 h-6 bg-primary text-white rounded-lg flex items-center justify-center shadow-lg shadow-primary/20' : 'text-gray-400' }}">{{ $day }}</span>
                        </div>
                        <div class="space-y-1.5 overflow-hidden">
                            @foreach($dayTasks as $task)
                                <a href="{{ route('tasks.show', $task->id) }}" class="block px-2 py-1 rounded-lg text-[9px] font-bold truncate bg-indigo-50 text-indigo-700 border border-indigo-100 hover:scale-105 transition-transform" title="{{ $task->title }}">
                                    {{ $task->title }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
@endsection