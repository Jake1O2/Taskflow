@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 animate-slide-up">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Intégrations</h1>
            <p class="text-gray-600 mt-2">Connectez TaskFlow à vos outils préférés</p>
        </div>

        <!-- Slack Card -->
        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
                <div class="flex items-center gap-4">
                    <!-- Slack Logo -->
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-8 h-8" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M5.042 15.165a2.528 2.528 0 0 1-2.52 2.523A2.528 2.528 0 0 1 0 15.165a2.527 2.527 0 0 1 2.522-2.52h2.52v2.52zM6.313 15.165a2.527 2.527 0 0 1 2.521-2.52 2.527 2.527 0 0 1 2.521 2.52v6.313A2.528 2.528 0 0 1 8.834 24a2.528 2.528 0 0 1-2.521-2.52v-6.315zM8.834 5.042a2.528 2.528 0 0 1-2.521-2.52A2.528 2.528 0 0 1 8.834 0a2.528 2.528 0 0 1 2.521 2.522v2.52h-2.521zM8.834 6.313a2.528 2.528 0 0 1 2.521 2.521 2.528 2.528 0 0 1-2.521 2.521H2.522A2.528 2.528 0 0 1 0 8.834a2.528 2.528 0 0 1 2.522-2.521h6.312zM18.956 8.834a2.528 2.528 0 0 1 2.522-2.521A2.528 2.528 0 0 1 24 8.834a2.528 2.528 0 0 1-2.522 2.521h-2.522V8.834zM17.688 8.834a2.528 2.528 0 0 1-2.522 2.521 2.527 2.527 0 0 1-2.522-2.521V2.522A2.527 2.527 0 0 1 15.165 0a2.528 2.528 0 0 1 2.523 2.522v6.312zM15.165 18.956a2.528 2.528 0 0 1 2.523 2.522A2.528 2.528 0 0 1 15.165 24a2.527 2.527 0 0 1-2.52-2.522v-2.522h2.52zM15.165 17.688a2.527 2.527 0 0 1-2.52-2.522 2.527 2.527 0 0 1 2.52-2.52h6.313A2.527 2.527 0 0 1 24 15.165a2.528 2.528 0 0 1-2.522 2.523h-6.313z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Slack</h2>
                        <p class="text-gray-600">Recevez les notifications TaskFlow directement dans Slack</p>
                    </div>
                </div>
                
                @if(isset($slackIntegration) && $slackIntegration->is_connected)
                    <span class="bg-green-100 text-green-800 text-xs font-bold px-3 py-1 rounded-full flex items-center gap-1 uppercase tracking-wider">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Connecté
                    </span>
                @endif
            </div>

            <div class="mt-8">
                @if(isset($slackIntegration) && $slackIntegration->is_connected)
                    <!-- Connected State -->
                    <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
                        <div class="flex items-center gap-2 text-green-800 font-bold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Connecté à {{ $slackIntegration->workspace_name }}
                        </div>
                        <div class="text-green-700 text-sm mt-1 ml-7 font-medium">
                            Channel: #{{ $slackIntegration->channel_name }}
                        </div>
                    </div>

                    <div class="flex gap-3" x-data="{ configOpen: false }">
                        <button type="button" @click="configOpen = !configOpen" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-lg font-bold text-sm hover:bg-gray-50 transition-colors shadow-sm">
                            Configuration
                        </button>
                        <form action="#" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir déconnecter Slack ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-50 text-red-600 rounded-lg font-bold text-sm hover:bg-red-100 transition-colors">
                                Déconnecter
                            </button>
                        </form>
                    </div>

                    <!-- Configuration Form -->
                    <div x-show="configOpen" x-transition class="mt-6 border-t border-gray-100 pt-6" style="display: none;">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Configuration Slack</h3>
                        <form action="#" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Channel</label>
                                <select name="channel_id" class="w-full rounded-xl border-gray-300 focus:border-primary focus:ring-primary bg-gray-50">
                                    <option value="{{ $slackIntegration->channel_id }}">#{{ $slackIntegration->channel_name }} (Actuel)</option>
                                    <option value="general">#general</option>
                                    <option value="random">#random</option>
                                    <option value="dev">#dev</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-3">Événements à envoyer</label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                        <input type="checkbox" name="events[]" value="project_events" class="rounded border-gray-300 text-primary focus:ring-primary w-5 h-5" checked>
                                        <span class="text-sm font-medium text-gray-700">Projets (Création, MAJ)</span>
                                    </label>
                                    <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                        <input type="checkbox" name="events[]" value="task_events" class="rounded border-gray-300 text-primary focus:ring-primary w-5 h-5" checked>
                                        <span class="text-sm font-medium text-gray-700">Tâches (Assignation, État)</span>
                                    </label>
                                    <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                        <input type="checkbox" name="events[]" value="comment_events" class="rounded border-gray-300 text-primary focus:ring-primary w-5 h-5" checked>
                                        <span class="text-sm font-medium text-gray-700">Commentaires</span>
                                    </label>
                                    <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                        <input type="checkbox" name="events[]" value="team_events" class="rounded border-gray-300 text-primary focus:ring-primary w-5 h-5">
                                        <span class="text-sm font-medium text-gray-700">Équipe (Nouveaux membres)</span>
                                    </label>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <label class="flex items-center cursor-pointer">
                                    <div class="relative">
                                        <input type="checkbox" name="is_active" class="sr-only peer" checked>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                    </div>
                                    <span class="ml-3 text-sm font-bold text-gray-700">Activer les notifications</span>
                                </label>
                                
                                <div class="flex gap-3">
                                    <button type="button" class="px-4 py-2 text-sm font-bold text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">Envoyer un test</button>
                                    <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg font-bold shadow-lg hover:scale-105 transition-all">Enregistrer</button>
                                </div>
                            </div>
                        </form>
                    </div>
                @else
                    <!-- Not Connected State -->
                    <div class="mt-6">
                        <a href="#" class="inline-flex items-center gap-3 px-8 py-4 bg-gray-900 text-white rounded-xl font-bold text-lg hover:bg-gray-800 transition-all shadow-xl hover:scale-105">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M5.042 15.165a2.528 2.528 0 0 1-2.52 2.523A2.528 2.528 0 0 1 0 15.165a2.527 2.527 0 0 1 2.522-2.52h2.52v2.52zM6.313 15.165a2.527 2.527 0 0 1 2.521-2.52 2.527 2.527 0 0 1 2.521 2.52v6.313A2.528 2.528 0 0 1 8.834 24a2.528 2.528 0 0 1-2.521-2.52v-6.315zM8.834 5.042a2.528 2.528 0 0 1-2.521-2.52A2.528 2.528 0 0 1 8.834 0a2.528 2.528 0 0 1 2.521 2.522v2.52h-2.521zM8.834 6.313a2.528 2.528 0 0 1 2.521 2.521 2.528 2.528 0 0 1-2.521 2.521H2.522A2.528 2.528 0 0 1 0 8.834a2.528 2.528 0 0 1 2.522-2.521h6.312zM18.956 8.834a2.528 2.528 0 0 1 2.522-2.521A2.528 2.528 0 0 1 24 8.834a2.528 2.528 0 0 1-2.522 2.521h-2.522V8.834zM17.688 8.834a2.528 2.528 0 0 1-2.522 2.521 2.527 2.527 0 0 1-2.522-2.521V2.522A2.527 2.527 0 0 1 15.165 0a2.528 2.528 0 0 1 2.523 2.522v6.312zM15.165 18.956a2.528 2.528 0 0 1 2.523 2.522A2.528 2.528 0 0 1 15.165 24a2.527 2.527 0 0 1-2.52-2.522v-2.522h2.52zM15.165 17.688a2.527 2.527 0 0 1-2.52-2.522 2.527 2.527 0 0 1 2.52-2.52h6.313A2.527 2.527 0 0 1 24 15.165a2.528 2.528 0 0 1-2.522 2.523h-6.313z"/>
                            </svg>
                            Connecter à Slack
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection