@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 animate-slide-in-up">
        {{-- Header --}}
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Équipes</h1>
                <p class="text-gray-400 font-medium mt-1">Gérez vos membres et collaborations.</p>
            </div>
            <a href="{{ route('teams.create') }}"
                class="btn-primary inline-flex items-center gap-2 px-5 py-2.5 text-sm rounded-2xl shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                Nouvelle Équipe
            </a>
        </header>

        @if(session('success'))
            <div
                class="flex items-center gap-3 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 animate-fade-in">
                <div class="shrink-0 w-8 h-8 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <span class="font-semibold text-sm">{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($teams as $team)
                @php
                    $teamGradients = [
                        'from-blue-400 to-indigo-600',
                        'from-violet-400 to-purple-600',
                        'from-teal-400 to-cyan-600',
                        'from-rose-400 to-pink-600',
                        'from-amber-400 to-orange-600',
                        'from-emerald-400 to-green-600',
                    ];
                    $tGrad = $teamGradients[crc32($team->name) % count($teamGradients)];
                    $memberCount = $team->members_count ?? 0;

                    // Member avatars (up to 3)
                    $memberColors = ['#0f4c81', '#7c3aed', '#059669', '#d97706', '#dc2626', '#0284c7'];
                    $members = $team->members ?? collect();
                    $displayMembers = $members->take(3);
                    $extraCount = max(0, $memberCount - 3);
                @endphp
                <div class="card-premium group relative flex flex-col">
                    {{-- Top --}}
                    <div class="flex items-center gap-4 mb-5">
                        <div
                            class="w-12 h-12 rounded-2xl bg-gradient-to-br {{ $tGrad }} text-white flex items-center justify-center font-bold text-xl shadow-lg group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shrink-0">
                            {{ strtoupper(substr($team->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-base font-bold text-gray-900 truncate group-hover:text-primary transition-colors">
                                {{ $team->name }}</h3>
                            @if($team->description ?? false)
                                <p class="text-xs text-gray-400 truncate mt-0.5">{{ $team->description }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- Owner Badge --}}
                    @if($team->owner)
                        <div class="flex items-center gap-2 mb-5">
                            <span
                                class="flex items-center gap-2 text-xs font-semibold text-amber-700 bg-amber-50 px-3 py-1.5 rounded-xl border border-amber-100">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                {{ $team->owner->name }}
                            </span>
                        </div>
                    @endif

                    {{-- Member Avatars + Count --}}
                    <div class="flex items-center gap-3 mb-5 flex-1">
                        @if($displayMembers->count() > 0)
                            <div class="flex -space-x-2">
                                @foreach($displayMembers as $i => $member)
                                    @php
                                        $initial = strtoupper(substr($member->user->name ?? $member->name ?? '?', 0, 1));
                                        $mc = $memberColors[$i % count($memberColors)];
                                    @endphp
                                    <div class="w-8 h-8 rounded-full border-2 border-white flex items-center justify-center text-[11px] font-bold text-white shadow-sm"
                                        style="background: {{ $mc }};" title="{{ $member->user->name ?? $member->name ?? '' }}">
                                        {{ $initial }}
                                    </div>
                                @endforeach
                                @if($extraCount > 0)
                                    <div
                                        class="w-8 h-8 rounded-full border-2 border-white bg-gray-100 flex items-center justify-center text-[10px] font-bold text-gray-500 shadow-sm">
                                        +{{ $extraCount }}
                                    </div>
                                @endif
                            </div>
                        @endif
                        <span class="text-xs font-semibold text-gray-400">{{ $memberCount }}
                            membre{{ $memberCount !== 1 ? 's' : '' }}</span>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-2 border-t border-gray-50 pt-5">
                        <a href="{{ route('teams.show', $team->id) }}"
                            class="flex-1 flex items-center justify-center gap-1.5 py-2.5 rounded-xl bg-gray-50 text-gray-700 font-bold text-sm hover:bg-primary/8 hover:text-primary transition-all">
                            Voir détails
                        </a>
                        <a href="{{ route('teams.edit', $team->id) }}"
                            class="p-2.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all"
                            title="Modifier">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                        </a>
                        <form action="{{ route('teams.destroy', $team->id) }}" method="POST"
                            onsubmit="return confirm('Confirmer la suppression ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="p-2.5 text-gray-400 hover:text-danger hover:bg-red-50 rounded-xl transition-all"
                                title="Supprimer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="empty-state">
                        <div class="w-16 h-16 rounded-3xl bg-violet-50 flex items-center justify-center mb-4 text-violet-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z">
                                </path>
                            </svg>
                        </div>
                        <p class="text-gray-600 font-bold mb-1">Aucune équipe disponible</p>
                        <p class="text-gray-400 text-sm mb-4">Commencez par en créer une pour collaborer.</p>
                        <a href="{{ route('teams.create') }}" class="btn-primary text-sm px-5 py-2.5">Créer une équipe</a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection