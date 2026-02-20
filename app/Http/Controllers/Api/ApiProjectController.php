<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiProjectController extends Controller
{
    public function index(): JsonResponse
    {
        $projects = Auth::user()->projects()->withCount('tasks')->orderBy('created_at', 'desc')->get();
        return $this->success($projects, 'Liste des projets');
    }

    public function show(string $id): JsonResponse
    {
        $project = Auth::user()->projects()->with('tasks')->find($id);
        if (! $project) {
            return $this->notFound('Projet');
        }
        return $this->success($project, 'Projet récupéré');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:preparation,in_progress,completed',
        ]);

        $project = Auth::user()->projects()->create($validated);
        return $this->success($project->fresh(), 'Projet créé', 201);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $project = Auth::user()->projects()->find($id);
        if (! $project) {
            return $this->notFound('Projet');
        }
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'sometimes|in:preparation,in_progress,completed',
        ]);
        $project->update($validated);
        return $this->success($project->fresh(), 'Projet mis à jour');
    }

    public function destroy(string $id): JsonResponse
    {
        $project = Auth::user()->projects()->find($id);
        if (! $project) {
            return $this->notFound('Projet');
        }
        $project->delete();
        return $this->success(null, 'Projet supprimé');
    }

    private function success(mixed $data, string $message = 'OK', int $status = 200): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $data, 'message' => $message], $status);
    }

    private function notFound(string $resource): JsonResponse
    {
        return response()->json([
            'success' => false,
            'error' => "{$resource} non trouvé.",
            'code' => 'not_found',
        ], 404);
    }
}
