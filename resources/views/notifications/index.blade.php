@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    {{-- Hero Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-[32px] font-bold text-gray-900 tracking-tight" style="letter-spacing: -0.5px;">Notifications</h1>
            <p class="text-sm text-gray-600 font-normal mt-1">Gérez vos notifications</p>
        </div>
        
        @if($notifications->where('read_at', null)->count() > 0)
            <form action="{{ route('notifications.readAll') }}" method="POST">
                @csrf
                <button type="submit" class="btn-secondary flex items-center gap-2 text-sm">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Marquer tout comme lu
                </button>
            </form>
        @endif
    </div>

    {{-- Notifications List --}}
    <div class="card-internal p-0 overflow-hidden shadow-sm">
        @forelse($notifications as $notif)
            <div class="group relative flex items-start gap-5 p-5 border-b border-gray-100 last:border-0 hover:bg-gray-50 transition-all duration-200 {{ !$notif->read_at ? 'bg-blue-50/40 border-l-2 border-l-blue-600' : '' }}">
                {{-- Icon Area --}}
                <div class="shrink-0 mt-1">
                    @php
                        $type = $notif->data['type'] ?? 'default';
                        $icons = [
                            'project_shared' => ['color' => 'blue', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>'],
                            'task_assigned' => ['color' => 'emerald', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>'],
                            'comment_added' => ['color' => 'gray', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>'],
                            'member_invited' => ['color' => 'purple', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>'],
                            'default' => ['color' => 'blue', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>']
                        ];
                        $config = $icons[$type] ?? $icons['default'];
                    @endphp
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center border {{ $config['color'] === 'blue' ? 'bg-blue-100/50 border-blue-200 text-blue-600' : ($config['color'] === 'emerald' ? 'bg-emerald-100/50 border-emerald-200 text-emerald-600' : ($config['color'] === 'purple' ? 'bg-purple-100/50 border-purple-200 text-purple-600' : 'bg-gray-100/50 border-gray-200 text-gray-600')) }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            {!! $config['icon'] !!}
                        </svg>
                    </div>
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0 pr-4">
                    <div class="flex items-center justify-between gap-4">
                        <h3 class="text-base font-bold text-gray-900 truncate tracking-tight">
                            {{ $notif->data['title'] ?? 'Notification' }}
                        </h3>
                        <span class="text-xs text-gray-500 font-medium whitespace-nowrap">
                            {{ $notif->created_at->diffForHumans() }}
                        </span>
                    </div>
                    <p class="text-[14px] text-gray-600 font-normal mt-0.5 leading-relaxed">
                        {{ $notif->data['message'] ?? 'Vous avez une nouvelle notification.' }}
                    </p>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col gap-2 items-end">
                    @if(!$notif->read_at)
                        <form action="{{ route('notifications.read', $notif->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs font-bold text-blue-600 hover:text-blue-800 hover:underline transition-colors whitespace-nowrap">
                                Marquer comme lu
                            </button>
                        </form>
                    @endif
                    @if(isset($notif->data['link']))
                        <a href="{{ $notif->data['link'] }}" class="text-[14px] font-bold text-blue-600 hover:text-blue-800 transition-colors flex items-center gap-1 group/link">
                            Voir
                            <svg class="w-4 h-4 group-hover/link:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="p-16 text-center">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Aucune notification</h3>
                <p class="text-gray-500 mb-8 max-w-xs mx-auto">Vous êtes à jour ! Repassez plus tard pour voir vos dernières activités.</p>
                <a href="{{ route('dashboard') }}" class="btn-secondary inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Retour au tableau de bord
                </a>
            </div>
        @endforelse
    </div>

    {{-- Pagination (Placeholder) --}}
    @if(method_exists($notifications, 'links') && $notifications->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection
