@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex items-center justify-between mb-6">
        <div>
            <a href="{{ route('webhooks.index') }}" class="text-sm text-gray-500 hover:text-gray-700">&larr; Retour aux webhooks</a>
            <h1 class="text-2xl font-bold text-gray-900 mt-1">Logs : {{ $webhook->url }}</h1>
        </div>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Événement</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Réponse</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($logs as $log)
                    <tr class="hover:bg-gray-50/50">
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 text-sm font-mono text-gray-800">{{ $log->event }}</td>
                        <td class="px-6 py-4">
                            @if($log->status_code >= 200 && $log->status_code < 300)
                                <span class="px-2 py-0.5 text-xs font-bold text-emerald-700 bg-emerald-50 rounded">{{ $log->status_code }}</span>
                            @else
                                <span class="px-2 py-0.5 text-xs font-bold text-red-700 bg-red-50 rounded">{{ $log->status_code ?? 'Erreur' }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-500 max-w-xs truncate" title="{{ $log->response }}">{{ Str::limit($log->response, 80) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">Aucun log pour l’instant.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-3 border-t border-gray-100">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
