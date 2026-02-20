@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 animate-slide-up">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="text-xs text-gray-400 font-bold uppercase tracking-widest">Tâche #{{ $task->id }}</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase border bg-gray-100 text-gray-500 border-gray-200">
                        {{ $task->status }}
                    </span>
                </div>
                <h1 class="text-2xl font-extrabold text-gray-900">{{ $task->title }}</h1>
            </div>
            <a href="{{ route('tasks.show', $task->id) }}" class="text-sm font-bold text-gray-500 hover:text-gray-900">
                &larr; Retour
            </a>
        </div>

        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <a href="{{ route('tasks.show', $task->id) }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Détails
                </a>
                <a href="#" class="border-primary text-primary whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm" aria-current="page">
                    Activité
                </a>
            </nav>
        </div>

        <div class="flow-root mt-8">
            <ul role="list" class="-mb-8">
                @foreach($activities as $activity)
                    <li>
                        <div class="relative pb-8">
                            @if(!$loop->last)
                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                            @endif
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center ring-8 ring-white">
                                        <span class="text-xs font-bold text-gray-600">{{ substr($activity->user->name, 0, 2) }}</span>
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                    <div>
                                        <p class="text-sm text-gray-500">
                                            <span class="font-bold text-gray-900">{{ $activity->user->name }}</span>
                                            {{ $activity->description }}
                                        </p>
                                    </div>
                                    <div class="text-right text-sm whitespace-nowrap text-gray-400">
                                        <time datetime="{{ $activity->created_at }}">{{ $activity->created_at->diffForHumans() }}</time>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection