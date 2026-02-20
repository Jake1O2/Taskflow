<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;

class ApiProjectController extends ApiController
{
    public function index()
    {
        $projects = Auth::user()->projects()->latest()->get();
        return $this->sendSuccess($projects, 'Projects retrieved successfully.');
    }

    // Implémentez show(), store(), update(), destroy() en suivant ce modèle.
    // N'oubliez pas la validation et l'autorisation !
    // Exemple pour store():
    // $validated = $request->validate([...]);
    // $project = Auth::user()->projects()->create($validated);
    // return $this->sendSuccess($project, 'Project created.', 201);
}
