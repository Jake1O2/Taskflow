<?php

namespace App\Http\Controllers;

use App\Models\Webhook;
use App\Services\WebhookDispatcher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class WebhookController extends Controller
{
    public function index()
    {
        $webhooks = Auth::user()->webhooks()->latest()->get();
        return view('webhooks.index', compact('webhooks'));
    }

    public function create()
    {
        return view('webhooks.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'url' => 'required|url:https',
            'events' => 'required|array',
            'events.*' => 'string',
        ]);

        $webhook = Auth::user()->webhooks()->create([
            'url' => $validated['url'],
            'events' => $validated['events'],
            'active' => true,
            'secret' => Str::random(32),
        ]);

        return redirect()->route('webhooks.index')
            ->with('success', 'Webhook créé avec succès. Voici votre secret, conservez-le précieusement, il ne sera plus affiché :')
            ->with('webhook_secret', $webhook->secret);
    }

    public function edit(Webhook $webhook)
    {
        $this->authorize('update', $webhook);
        return view('webhooks.edit', compact('webhook'));
    }

    public function update(Request $request, Webhook $webhook)
    {
        $this->authorize('update', $webhook);

        $validated = $request->validate([
            'url' => 'required|url:https',
            'events' => 'required|array',
            'events.*' => 'string',
        ]);
        $validated['active'] = $request->boolean('active');

        $webhook->update($validated);

        return redirect()->route('webhooks.index')->with('success', 'Webhook mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $webhook = Auth::user()->webhooks()->findOrFail($id);
        $webhook->delete();

        return redirect()->route('webhooks.index')->with('success', 'Webhook supprimé.');
    }

    public function toggle(Request $request, $id)
    {
        $webhook = Auth::user()->webhooks()->findOrFail($id);
        $active = $request->boolean('active');
        $webhook->update(['active' => $active]);

        return response()->json(['ok' => true]);
    }

    public function testWebhook(Webhook $webhook, WebhookDispatcher $dispatcher)
    {
        $this->authorize('view', $webhook);

        $testPayload = [
            'message' => 'Ceci est un événement de test envoyé depuis TaskFlow.',
            'timestamp' => now()->toIso8601String(),
        ];

        $dispatcher->dispatch('project.created', array_merge($testPayload, ['project_id' => 0, 'name' => 'Test webhook']), $webhook->user_id);

        return redirect()->back()->with('success', 'Événement de test envoyé au webhook. Vérifiez vos logs pour le résultat.');
    }

    public function showLogs(Webhook $webhook)
    {
        $this->authorize('view', $webhook);
        $logs = $webhook->logs()->latest()->paginate(20);
        return view('webhooks.logs', compact('webhook', 'logs'));
    }
}
