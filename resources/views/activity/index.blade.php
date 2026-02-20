@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Activity History</h1>
                <p class="text-sm text-gray-500">
                    {{ ucfirst($entityType) }} #{{ $entity->id }}
                </p>
            </div>
            <a href="{{ url()->previous() }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800">
                Back
            </a>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500">User</th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Action</th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Changes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($logs as $log)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $log->created_at?->format('Y-m-d H:i:s') }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $log->user?->name ?? 'System' }}</td>
                            <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ $log->action }}</td>
                            <td class="px-4 py-3 text-xs text-gray-600">
                                <pre class="whitespace-pre-wrap">{{ json_encode($log->changes, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500">No activity yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $logs->links() }}
        </div>
    </div>
@endsection
