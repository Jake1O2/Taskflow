@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm shadow-gray-200 rounded-xl border border-gray-200 animate-slide-down">
                <div class="p-8">
                     <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                        <div class="bg-indigo-50 p-2 rounded-lg">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Créer une équipe</h1>
                            <p class="text-gray-500 text-sm">Rassemblez vos collaborateurs pour travailler ensemble.</p>
                        </div>
                    </div>

                    <form action="{{ route('teams.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="space-y-1">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nom de l'équipe <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-all duration-200" 
                                   placeholder="Ex: Marketing, Développeurs..." required autofocus />
                            @error('name')
                                <p class="text-red-500 text-xs mt-1 flex items-center gap-1 animate-shake">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="space-y-1">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description (optionnel)</label>
                            <textarea name="description" id="description" rows="4"
                                      class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-all duration-200"
                                      placeholder="Quel est le but de cette équipe ?">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1 flex items-center gap-1 animate-shake">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-100 mt-8">
                            <a href="{{ route('teams.index') }}" class="text-gray-600 hover:text-gray-900 font-medium text-sm transition-colors px-4 py-2 rounded-lg hover:bg-gray-100">
                                Annuler
                            </a>
                            <button type="submit" class="inline-flex justify-center items-center px-6 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-lg font-semibold text-sm shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                                Créer l'équipe
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
