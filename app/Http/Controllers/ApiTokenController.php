<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiTokenController extends Controller
{
    public function index()
    {
        $tokens = collect([
            (object)[
                'id' => 1,
                'name' => 'Main App Token',
                'created_at' => now()->subDays(10),
                'last_used_at' => now()->subHours(2),
                'token' => 'sk_live_51P...x9z8'
            ]
        ]);

        return view('api.tokens', compact('tokens'));
    }

    public function store(Request $request)
    {
        return redirect()->back()->with('success', 'Token créé avec succès : sk_live_new_token_example_123');
    }

    public function destroy($id)
    {
        return redirect()->back()->with('success', 'Token révoqué');
    }
}
