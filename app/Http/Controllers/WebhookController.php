<?php

namespace App\Http\Controllers;

use App\Models\Webhook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class WebhookController extends Controller
{
    public function index()
    {
        $webhooks = Auth::user()->webhooks()->latest()->get();

        if ($webhooks->isEmpty()) {
            $webhooks = collect([
                (object)[
                    'id' => 1,
                    'url' => 'https://api.example.com/webhooks/demo',
                    'events' => ['task.created', 'project.created'],
                    'status' => 'inactive',
                    'created_at' => now()->subMonths(1)
                ]
            ]);
        }

        return view('webhooks.index', compact('webhooks'));
    }

    public function create()
    {
        return view('webhooks.index'); // Form is integrated in index usually, but route exists
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'url' => 'required|url',
            'events' => 'required|array',
            'events.*' => 'string',
        ]);

        Auth::user()->webhooks()->create([
            'url' => $validated['url'],
            'events' => $validated['events'],
            'status' => 'active',
        ]);

        return redirect()->route('webhooks.index')->with('success', 'Webhook créé avec succès');
    }

    public function destroy($id)
    {
        $webhook = Auth::user()->webhooks()->findOrFail($id);
        $webhook->delete();

        return redirect()->route('webhooks.index')->with('success', 'Webhook supprimé');
    }

    public function testWebhook($id)
    {
        $webhook = Auth::user()->webhooks()->findOrFail($id);

        try {
            /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::timeout(5)->post($webhook->url, [
                'event' => 'webhook.test',
                'timestamp' => now()->toIso8601String(),
                'data' => ['message' => 'Test de connexion réussi']
            ]);

            if ($response->successful()) {
                return redirect()->back()->with('success', 'Test réussi ! Le endpoint a répondu 200 OK.');
            }

            return redirect()->back()->with('error', 'Le endpoint a répondu avec une erreur : ' . $response->status());
        }
        catch (\Exception $e) {
            return redirect()->back()->with('error', 'Échec de la connexion : ' . $e->getMessage());
        }
    }
}
