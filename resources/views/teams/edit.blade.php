@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-100 animate-slide-down">
                <div class="p-8">
                    <div class="flex justify-between items-center mb-6 border-b pb-4 border-gray-100">
                        <h1 class="text-3xl font-bold text-gray-900">Modifier l'équipe</h1>
                        <span class="text-sm text-gray-400">ID: {{ $team->id }}</span>
                    </div>

                    <form action="{{ route('teams.update', $team->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom de l'équipe</label>
                            <x-text-input type="text" name="name" id="name" value="{{ old('name', $team->name) }}" class="w-full" required />
                            @error('name')
                                <p class="text-red-500 text-xs mt-1 flex items-center gap-1 animate-shake">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" id="description" rows="4"
                                      class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm transition-all duration-200 focus:scale-[1.01]">{{ old('description', $team->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1 flex items-center gap-1 animate-shake">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100">
                            <a href="{{ route('teams.index') }}" class="text-gray-600 hover:text-gray-900 font-medium text-sm transition-colors">
                                Annuler
                            </a>
                            <x-primary-button>
                                Mettre à jour
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
