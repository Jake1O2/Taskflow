@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    {{-- Hero Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-[32px] font-bold text-gray-900 tracking-tight leading-tight">Notifications</h1>
            <p class="text-sm text-gray-500 font-normal mt-1">Gérez vos notifications</p>
        </div>
        
        @php $unreadCount = Auth::user()->unreadNotifications->count(); @endphp
        @if($unreadCount > 0)
            <form action="{{ route('notifications.readAll') }}" method="POST">
                @csrf
                <button type="submit" class="btn-secondary flex items-center gap-2 text-sm px-5 py-2.5 rounded-xl">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Marquer tout comme lu
                </button>
            </form>
        @endif
    </div>

    {{-- Notifications List --}}
    <div class="card-internal !p-0 overflow-hidden shadow-sm border-gray-200 rounded-[12px]">
        @forelse($notifications as $notif)
            @php
                $type = $notif->data['type'] ?? 'default';
                $isUnread = !$notif->read_at;
                $link = $notif->data['link'] ?? '#';
                
                $icons = [
                    'project_shared' => ['color' => 'text-blue-600', 'bg' => 'bg-blue-100/50', 'border' => 'border-blue-200', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>'],
                    'task_assigned' => ['color' => 'text-emerald-600', 'bg' => 'bg-emerald-100/50', 'border' => 'border-emerald-200', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>'],
                    'comment_added' => ['color' => 'text-gray-600', 'bg' => 'bg-gray-100/50', 'border' => 'border-gray-200', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>'],
                    'member_invited' => ['color' => 'text-purple-600', 'bg' => 'bg-purple-100/50', 'border' => 'border-purple-200', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>'],
                    'default' => ['color' => 'text-blue-600', 'bg' => 'bg-blue-100/50', 'border' => 'border-blue-200', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>']
                ];
                $config = $icons[$type] ?? $icons['default'];
            @endphp
            <div onclick="window.location='{{ $link }}'" 
                 class="group relative flex items-start gap-4 p-4 border-b border-gray-100 last:border-0 hover:bg-gray-50 transition-all duration-200 cursor-pointer 
                 {{ $isUnread ? 'bg-blue-50/40 border-l-[3px] border-l-blue-600' : '' }}">
                
                {{-- Icon Area --}}
                <div class="shrink-0">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center border {{ $config['bg'] }} {{ $config['border'] }} {{ $config['color'] }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            {!! $config['svg'] !!}
                        </svg>
                    </div>
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-baseline justify-between gap-4">
                        <h3 class="text-[16px] font-bold text-gray-900 truncate tracking-tight">
                            {{ $notif->data['title'] ?? 'Notification' }}
                        </h3>
                        <span class="text-[12px] text-gray-500 font-medium whitespace-nowrap">
                            {{ $notif->created_at->diffForHumans() }}
                        </span>
                    </div>
                    <p class="text-[14px] text-gray-600 font-normal mt-0.5 leading-relaxed">
                        {{ $notif->data['message'] ?? 'Vous avez une nouvelle activité.' }}
                    </p>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col gap-2 items-end shrink-0" onclick="event.stopPropagation()">
                    @if($isUnread)
                        <form action="{{ route('notifications.read', $notif->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs font-bold text-blue-600 hover:text-blue-800 hover:underline transition-colors whitespace-nowrap">
                                Marquer comme lu
                            </button>
                        </form>
                    @endif
                    <a href="{{ $link }}" class="text-[14px] font-bold text-blue-600 hover:text-blue-800 transition-colors flex items-center gap-1 group/link">
                        Voir
                        <svg class="w-4 h-4 group-hover/link:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>
        @empty
            <div class="p-16 text-center">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2 leading-tight">Aucune notification</h3>
                <p class="text-gray-500 mb-8 max-w-xs mx-auto">Vous êtes à jour ! Repassez plus tard pour voir vos dernières activités.</p>
                <a href="{{ route('dashboard') }}" class="text-blue-600 font-bold hover:underline inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Retour au tableau de bord
                </a>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if(isset($notifications) && method_exists($notifications, 'links') && $notifications->total() > 20)
        <div class="mt-8 flex justify-center">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection
