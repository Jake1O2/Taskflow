<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function index()
    {
        $webhooks = collect([
            (object)[
                'id' => 1,
                'url' => 'https://api.example.com/webhooks/taskflow',
                'events' => ['task.created', 'task.updated'],
                'status' => 'active',
                'created_at' => now()->subMonths(1)
            ]
        ]);

        return view('webhooks.index', compact('webhooks'));
    }

    public function store(Request $request)
    {
        return redirect()->back()->with('success', 'Webhook créé');
    }

    public function destroy($id)
    {
        return redirect()->back()->with('success', 'Webhook supprimé');
    }
}
