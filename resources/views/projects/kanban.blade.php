@extends('layouts.app')

{{-- @var \App\Models\Project $project --}}
{{-- @var array $tasksByStatus --}}

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Kanban: {{ $project->title }}</h2>
                <div class="space-x-2">
                    <a href="{{ route('projects.show', $project->id) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded">
                        Retour
                    </a>
                    <a href="{{ route('tasks.create', $project->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Ajouter une tâche
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Colonne À faire -->
                <div class="bg-gray-100 p-4 rounded-lg shadow-inner min-h-[500px]">
                    <h3 class="font-bold text-gray-700 mb-4 flex justify-between items-center uppercase text-sm tracking-wide">
                        À faire
                        <span class="bg-gray-300 text-gray-700 text-xs font-bold px-2 py-1 rounded-full">{{ count($tasksByStatus['todo']) }}</span>
                    </h3>
                    <div class="space-y-3">
                        @foreach($tasksByStatus['todo'] as $task)
                            <a href="{{ route('tasks.edit', $task->id) }}" class="block bg-white p-4 rounded shadow hover:shadow-md transition duration-200 border-l-4 border-gray-400">
                                <h4 class="font-semibold text-gray-800">{{ $task->title }}</h4>
                                @if($task->description)
                                    <p class="text-xs text-gray-500 mt-2 line-clamp-2">{{ $task->description }}</p>
                                @endif
                                @if($task->due_date)
                                    <div class="mt-3 flex items-center text-xs text-gray-500">
                                        <span class="mr-1">📅</span>
                                        {{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}
                                    </div>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Colonne En cours -->
                <div class="bg-blue-50 p-4 rounded-lg shadow-inner min-h-[500px]">
                    <h3 class="font-bold text-blue-700 mb-4 flex justify-between items-center uppercase text-sm tracking-wide">
                        En cours
                        <span class="bg-blue-200 text-blue-700 text-xs font-bold px-2 py-1 rounded-full">{{ count($tasksByStatus['in_progress']) }}</span>
                    </h3>
                    <div class="space-y-3">
                        @foreach($tasksByStatus['in_progress'] as $task)
                            <a href="{{ route('tasks.edit', $task->id) }}" class="block bg-white p-4 rounded shadow hover:shadow-md transition duration-200 border-l-4 border-blue-500">
                                <h4 class="font-semibold text-gray-800">{{ $task->title }}</h4>
                                @if($task->description)
                                    <p class="text-xs text-gray-500 mt-2 line-clamp-2">{{ $task->description }}</p>
                                @endif
                                @if($task->due_date)
                                    <div class="mt-3 flex items-center text-xs text-gray-500">
                                        <span class="mr-1">📅</span>
                                        {{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}
                                    </div>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Colonne Terminée -->
                <div class="bg-green-50 p-4 rounded-lg shadow-inner min-h-[500px]">
                    <h3 class="font-bold text-green-700 mb-4 flex justify-between items-center uppercase text-sm tracking-wide">
                        Terminée
                        <span class="bg-green-200 text-green-700 text-xs font-bold px-2 py-1 rounded-full">{{ count($tasksByStatus['done']) }}</span>
                    </h3>
                    <div class="space-y-3">
                        @foreach($tasksByStatus['done'] as $task)
                            <a href="{{ route('tasks.edit', $task->id) }}" class="block bg-white p-4 rounded shadow hover:shadow-md transition duration-200 border-l-4 border-green-500 opacity-75">
                                <h4 class="font-semibold text-gray-800 line-through">{{ $task->title }}</h4>
                                @if($task->description)
                                    <p class="text-xs text-gray-500 mt-2 line-clamp-2">{{ $task->description }}</p>
                                @endif
                                @if($task->due_date)
                                    <div class="mt-3 flex items-center text-xs text-gray-500">
                                        <span class="mr-1">📅</span>
                                        {{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}
                                    </div>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection