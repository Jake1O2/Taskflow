<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiTeamController extends Controller
{
    public function index(): JsonResponse
    {
        $teams = Auth::user()->teams()->withCount('members')->orderBy('created_at', 'desc')->get();
        return $this->success($teams, 'Liste des équipes');
    }

    public function show(string $id): JsonResponse
    {
        $team = Auth::user()->teams()->with(['owner', 'members.user'])->find($id);
        if (! $team) {
            return $this->notFound('Équipe');
        }
        return $this->success($team, 'Équipe récupérée');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $team = Auth::user()->teams()->create($validated);
        return $this->success($team->fresh(), 'Équipe créée', 201);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $team = Auth::user()->teams()->find($id);
        if (! $team) {
            return $this->notFound('Équipe');
        }
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
        ]);
        $team->update($validated);
        return $this->success($team->fresh(), 'Équipe mise à jour');
    }

    public function destroy(string $id): JsonResponse
    {
        $team = Auth::user()->teams()->find($id);
        if (! $team) {
            return $this->notFound('Équipe');
        }
        $team->delete();
        return $this->success(null, 'Équipe supprimée');
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
