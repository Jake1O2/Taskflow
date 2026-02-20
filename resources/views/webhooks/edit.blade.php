@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Modifier le webhook</h1>
    @if(session('success'))
        <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm">{{ session('success') }}</div>
    @endif
    <form action="{{ route('webhooks.update', $webhook) }}" method="POST" class="space-y-5 bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">URL du endpoint <span class="text-red-500">*</span></label>
            <input type="url" name="url" value="{{ old('url', $webhook->url) }}" required
                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 @error('url') border-red-400 @enderror">
            @error('url')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Événements</label>
            @php
                $allEvents = ['project.created', 'task.created', 'task.updated', 'task.assigned', 'comment.added', 'team.member.added', 'payment.received'];
                $current = old('events', $webhook->events ?? []);
            @endphp
            @foreach($allEvents as $ev)
                <label class="flex items-center gap-2 py-1.5">
                    <input type="checkbox" name="events[]" value="{{ $ev }}" {{ in_array($ev, $current) ? 'checked' : '' }} class="rounded text-blue-600 border-gray-300">
                    <span class="text-sm text-gray-700">{{ $ev }}</span>
                </label>
            @endforeach
        </div>
        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
            <span class="text-sm font-bold text-gray-800">Actif</span>
            <label class="inline-flex items-center cursor-pointer">
                <input type="hidden" name="active" value="0">
                <input type="checkbox" name="active" value="1" {{ old('active', $webhook->active) ? 'checked' : '' }} class="rounded text-blue-600 border-gray-300">
            </label>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('webhooks.index') }}" class="px-4 py-2.5 border border-gray-200 rounded-xl font-semibold text-sm text-gray-700 hover:bg-gray-50">Annuler</a>
            <button type="submit" class="px-4 py-2.5 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700">Enregistrer</button>
        </div>
    </form>
</div>
@endsection
