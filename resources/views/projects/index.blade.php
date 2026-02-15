@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-8 animate-slide-down">
                <h1 class="text-[32px] font-bold text-gray-900 tracking-tight">
                    Tous les projets
                </h1>
                <a href="{{ route('projects.create') }}" class="inline-flex items-center px-5 py-2.5 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Nouveau projet
                </a>
            </div>

            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg mb-6 shadow-sm animate-fade-in flex items-center justify-between" role="alert">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm border border-gray-200 sm:rounded-xl animate-fade-in" style="animation-delay: 100ms;">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Titre</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Statut</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Échéance</th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($projects as $project)
                                <tr class="hover:bg-gray-50 transition-colors duration-200 group">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="shrink-0 h-10 w-10 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 font-bold text-lg shadow-sm border border-blue-100">
                                                {{ substr($project->title, 0, 1) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-gray-900 group-hover:text-blue-600 transition-colors">{{ $project->title }}</div>
                                                <div class="text-sm text-gray-500 truncate max-w-xs">{{ $project->description }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusStyles = [
                                                'preparation' => 'bg-gray-100 text-gray-700',
                                                'in_progress' => 'bg-blue-100 text-blue-700',
                                                'completed' => 'bg-emerald-100 text-emerald-700',
                                            ];
                                            $statusLabels = [
                                                'preparation' => 'En préparation',
                                                'in_progress' => 'En cours',
                                                'completed' => 'Terminé',
                                            ];
                                        @endphp
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusStyles[$project->status] ?? 'bg-gray-100 text-gray-700' }}">
                                            {{ $statusLabels[$project->status] ?? ucfirst($project->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">
                                        {{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('d/m/Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('projects.show', $project->id) }}" class="text-gray-400 hover:text-blue-600 p-1.5 hover:bg-blue-50 rounded-md transition-colors" title="Voir">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>
                                            <a href="{{ route('projects.edit', $project->id) }}" class="text-gray-400 hover:text-indigo-600 p-1.5 hover:bg-indigo-50 rounded-md transition-colors" title="Éditer">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <form action="{{ route('projects.destroy', $project->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-gray-400 hover:text-red-600 p-1.5 hover:bg-red-50 rounded-md transition-colors" title="Supprimer">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-16 text-center text-gray-500">
                                        <div class="flex flex-col items-center">
                                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path></svg>
                                            </div>
                                            <p class="text-lg font-medium text-gray-900">Aucun projet trouvé</p>
                                            <p class="text-sm mt-1 text-gray-500 mb-4">Démarrez votre premier projet dès maintenant.</p>
                                            <a href="{{ route('projects.create') }}" class="text-blue-600 hover:text-blue-800 font-medium hover:underline text-sm">Créer un nouveau projet &rarr;</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection