<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiTaskController extends Controller
{
    public function index(string $projectId): JsonResponse
    {
        $project = Auth::user()->projects()->find($projectId);
        if (! $project) {
            return $this->notFound('Projet');
        }
        $tasks = $project->tasks()->orderBy('created_at', 'desc')->get();
        return $this->success($tasks, 'Liste des tâches');
    }

    public function show(string $id): JsonResponse
    {
        $task = Task::whereHas('project', fn ($q) => $q->where('created_by', Auth::id()))->find($id);
        if (! $task) {
            return $this->notFound('Tâche');
        }
        return $this->success($task->load('project'), 'Tâche récupérée');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:todo,in_progress,done',
            'due_date' => 'nullable|date',
        ]);
        $project = Auth::user()->projects()->find($validated['project_id']);
        if (! $project) {
            return $this->notFound('Projet');
        }
        unset($validated['project_id']);
        $task = $project->tasks()->create($validated);
        return $this->success($task->fresh(), 'Tâche créée', 201);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $task = Task::whereHas('project', fn ($q) => $q->where('created_by', Auth::id()))->find($id);
        if (! $task) {
            return $this->notFound('Tâche');
        }
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|in:todo,in_progress,done',
            'due_date' => 'nullable|date',
        ]);
        $task->update($validated);
        return $this->success($task->fresh(), 'Tâche mise à jour');
    }

    public function destroy(string $id): JsonResponse
    {
        $task = Task::whereHas('project', fn ($q) => $q->where('created_by', Auth::id()))->find($id);
        if (! $task) {
            return $this->notFound('Tâche');
        }
        $task->delete();
        return $this->success(null, 'Tâche supprimée');
    }

    public function updateStatus(Request $request, string $id): JsonResponse
    {
        $task = Task::whereHas('project', fn ($q) => $q->where('created_by', Auth::id()))->find($id);
        if (! $task) {
            return $this->notFound('Tâche');
        }
        $validated = $request->validate(['status' => 'required|in:todo,in_progress,done']);
        $task->update($validated);
        return $this->success($task->fresh(), 'Statut mis à jour');
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
