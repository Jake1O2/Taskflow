@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Calendrier: {{ $project->title }}</h2>
                <div class="space-x-2">
                    <a href="{{ route('projects.show', $project->id) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded">
                        Retour
                    </a>
                    <a href="{{ route('tasks.create', $project->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Ajouter une tâche
                    </a>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                {{-- Navigation Mois --}}
                <div class="flex justify-between items-center mb-6">
                    <a href="{{ route('projects.calendar', [$project->id, 'date' => $date->copy()->subMonth()->format('Y-m-d')]) }}" class="text-gray-600 hover:text-gray-900 font-bold px-4 py-2 rounded hover:bg-gray-100">
                        &larr; Précédent
                    </a>
                    <h3 class="text-xl font-bold text-gray-800 capitalize">
                        {{ $date->format('F Y') }}
                    </h3>
                    <a href="{{ route('projects.calendar', [$project->id, 'date' => $date->copy()->addMonth()->format('Y-m-d')]) }}" class="text-gray-600 hover:text-gray-900 font-bold px-4 py-2 rounded hover:bg-gray-100">
                        Suivant &rarr;
                    </a>
                </div>

                {{-- Grille Calendrier --}}
                <div class="grid grid-cols-7 gap-px bg-gray-200 border border-gray-200">
                    {{-- En-têtes Jours --}}
                    @foreach(['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'] as $dayName)
                        <div class="bg-gray-50 py-2 text-center font-bold text-gray-700">{{ $dayName }}</div>
                    @endforeach

                    {{-- Cases vides début de mois --}}
                    @for ($i = 1; $i < $date->startOfMonth()->dayOfWeekIso; $i++)
                        <div class="bg-white h-32 p-2"></div>
                    @endfor

                    {{-- Jours du mois --}}
                    @for ($day = 1; $day <= $date->daysInMonth; $day++)
                        @php
                            $currentDate = $date->copy()->day($day);
                            $dayTasks = $tasks->filter(function($task) use ($currentDate) {
                                return \Carbon\Carbon::parse($task->due_date)->isSameDay($currentDate);
                            });
                        @endphp
                        <div class="bg-white h-32 p-2 border-t border-gray-100 hover:bg-gray-50 transition relative overflow-y-auto">
                            <div class="font-bold text-gray-700 mb-1 text-sm">{{ $day }}</div>
                            <div class="space-y-1">
                                @foreach($dayTasks as $task)
                                    @php
                                        $colors = [
                                            'todo' => 'bg-gray-200 text-gray-800 hover:bg-gray-300',
                                            'in_progress' => 'bg-blue-100 text-blue-800 hover:bg-blue-200',
                                            'done' => 'bg-green-100 text-green-800 hover:bg-green-200',
                                        ];
                                        $colorClass = $colors[$task->status] ?? $colors['todo'];
                                    @endphp
                                    <a href="{{ route('tasks.edit', $task->id) }}" class="block text-xs px-2 py-1 rounded truncate {{ $colorClass }}" title="{{ $task->title }}">
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