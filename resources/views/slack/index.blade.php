@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-primary">Intégrations</p>
                <h1 class="text-3xl font-bold text-gray-900">Slack Workspace</h1>
                <p class="mt-1 text-sm text-gray-600">Connecte un workspace Slack à une équipe et choisis les événements à publier.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <section class="card-internal mb-8">
            <h2 class="text-lg font-bold text-gray-900">Connecter une équipe</h2>
            <p class="mt-1 text-sm text-gray-600">Lance l’OAuth Slack pour l’équipe concernée.</p>

            <div class="mt-5 grid grid-cols-1 gap-3 md:grid-cols-2 xl:grid-cols-3">
                @forelse($teams as $team)
                    <div class="rounded-xl border border-gray-200 bg-white px-4 py-3">
                        <p class="text-sm font-semibold text-gray-900">{{ $team->name }}</p>
                        <p class="mt-1 text-xs text-gray-500">{{ $team->description ?: 'Aucune description' }}</p>
                        <a href="{{ route('slack.connect', $team->id) }}"
                           class="mt-3 inline-flex rounded-lg bg-primary px-3 py-2 text-xs font-semibold text-white hover:bg-primary-dark">
                            Connecter à Slack
                        </a>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Aucune équipe disponible.</p>
                @endforelse
            </div>
        </section>

        <section class="space-y-5">
            @forelse($integrations as $integration)
                <article class="card-internal">
                    <div class="mb-5 flex flex-col gap-3 border-b border-gray-100 pb-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ $integration->team?->name ?? 'Équipe supprimée' }}</h3>
                            <p class="text-sm text-gray-600">
                                Workspace: <span class="font-semibold">{{ $integration->slack_team_id }}</span>
                                · Channel actuel:
                                <span class="font-semibold">{{ $integration->channel_name ?: 'Non défini' }}</span>
                            </p>
                        </div>
                        <span class="inline-flex w-fit rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wider {{ $integration->active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $integration->active ? 'Actif' : 'Inactif' }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                        <form method="POST" action="{{ route('slack.selectChannel', $integration->id) }}" class="rounded-xl border border-gray-200 bg-gray-50/50 p-4">
                            @csrf
                            <h4 class="text-sm font-bold text-gray-900">Channel Slack</h4>
                            <p class="mt-1 text-xs text-gray-500">Sélectionne le channel où publier les notifications.</p>

                            <select name="channel_id" class="mt-3 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm">
                                <option value="">-- Choisir un channel --</option>
                                @foreach($channelsByIntegration[$integration->id] ?? [] as $channel)
                                    <option value="{{ $channel['id'] }}" {{ $integration->slack_channel_id === $channel['id'] ? 'selected' : '' }}>
                                        #{{ $channel['name'] }}
                                    </option>
                                @endforeach
                            </select>

                            @error('channel_id')
                                <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                            @enderror

                            <button type="submit" class="mt-4 rounded-lg bg-primary px-3 py-2 text-xs font-semibold text-white hover:bg-primary-dark">
                                Mettre à jour le channel
                            </button>
                        </form>

                        <form method="POST" action="{{ route('slack.updateEvents', $integration->id) }}" class="rounded-xl border border-gray-200 bg-gray-50/50 p-4">
                            @csrf
                            @method('PUT')
                            <h4 class="text-sm font-bold text-gray-900">Événements envoyés</h4>
                            <p class="mt-1 text-xs text-gray-500">Choisis les événements à notifier sur Slack.</p>

                            <div class="mt-3 grid grid-cols-1 gap-2 sm:grid-cols-2">
                                @foreach($availableEvents as $event)
                                    <label class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-2 py-2 text-xs text-gray-700">
                                        <input type="checkbox" name="events[]" value="{{ $event }}" class="rounded border-gray-300 text-primary"
                                               {{ in_array($event, $integration->events ?? [], true) ? 'checked' : '' }}>
                                        <span>{{ $event }}</span>
                                    </label>
                                @endforeach
                            </div>

                            <button type="submit" class="mt-4 rounded-lg bg-primary px-3 py-2 text-xs font-semibold text-white hover:bg-primary-dark">
                                Mettre à jour les événements
                            </button>
                        </form>
                    </div>

                    <div class="mt-5 border-t border-gray-100 pt-4">
                        <form method="POST" action="{{ route('slack.disconnect', $integration->id) }}"
                              onsubmit="return confirm('Supprimer cette intégration Slack ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 hover:bg-red-100">
                                Déconnecter Slack
                            </button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="card-internal text-center">
                    <p class="text-sm text-gray-600">Aucune intégration Slack connectée pour le moment.</p>
                </div>
            @endforelse
        </section>
    </div>
@endsection
