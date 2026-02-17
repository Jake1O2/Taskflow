<?php

namespace App\Http\Controllers;

use App\Helpers\NotificationHelper;
use App\Services\WebhookDispatcher;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class TaskController extends Controller
{
    /**
     * Affiche le formulaire de création de tâche.
     */
    public function create(string $projectId): View
    {
        $project = $this->currentUser()->projects()->findOrFail($projectId);
        return view('tasks.create', compact('project'));
    }

    /**
     * Valide et crée une tâche.
     */
    public function store(Request $request, string $projectId, WebhookDispatcher $webhookDispatcher): RedirectResponse
    {
        $project = $this->currentUser()->projects()->findOrFail($projectId);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:todo,in_progress,done',
            'due_date' => 'nullable|date',
        ]);

        $task = $project->tasks()->create($validated);
        $this->currentUser()->forgetStatsCache();

        NotificationHelper::createNotification(
            Auth::id(),
            'task_assigned',
            "Tâche créée",
            "Une nouvelle tâche a été créée",
            route('projects.show', $projectId)
        );

        $webhookDispatcher->dispatch('task.created', $task->toArray(), Auth::id());

        return redirect()->route('projects.show', $projectId)->with('success', 'Tâche créée');
    }

    /**
     * Affiche les détails d'une tâche avec ses commentaires.
     */
    public function show(string $id): View
    {
        $task = Task::findOrFail($id);
        abort_if($task->project->user_id !== Auth::id(), 403);
        return view('tasks.show', ['task' => $task, 'comments' => $task->comments()->latest()->get()]);
    }

    /**
     * Affiche le formulaire d'édition.
     */
    public function edit(string $id): View
    {
        $task = $this->getTaskForUser($id);
        return view('tasks.edit', compact('task'));
    }

    /**
     * Valide et modifie la tâche.
     */
    public function update(Request $request, string $id, WebhookDispatcher $webhookDispatcher): RedirectResponse
    {
        $task = $this->getTaskForUser($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:todo,in_progress,done',
            'due_date' => 'nullable|date',
        ]);

        $task->update($validated);
        $this->currentUser()->forgetStatsCache();

        $webhookDispatcher->dispatch('task.updated', $task->toArray(), Auth::id());

        return redirect()->route('projects.show', $task->project_id)->with('success', 'Tâche modifiée');
    }

    /**
     * Supprime la tâche.
     */
    public function destroy(string $id): RedirectResponse
    {
        $task = $this->getTaskForUser($id);
        $projectId = $task->project_id;
        $task->delete();
        $this->currentUser()->forgetStatsCache();

        return redirect()->route('projects.show', $projectId)->with('success', 'Tâche supprimée');
    }

    /**
     * Recherche des tâches par titre (user owns project).
     */
    public function search(Request $request): View
    {
        $validated = $request->validate(['query' => 'required|string|max:255']);
        $tasks = Task::whereHas('project', function ($q) {
            $q->where('user_id', Auth::id());
        })
            ->where('title', 'like', '%' . $validated['query'] . '%')
            ->with('project')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('tasks.index', compact('tasks'))->with('success', 'Recherche effectuée');
    }

    /**
     * Filtre les tâches par status (et optionnellement priority).
     */
    public function filter(Request $request): View
    {
        $validated = $request->validate([
            'status' => 'required|in:todo,in_progress,done',
            'priority' => 'nullable|string|max:50',
        ]);
        $query = Task::whereHas('project', function ($q) {
            $q->where('user_id', Auth::id());
        })->where('status', $validated['status']);

        if (Schema::hasColumn('tasks', 'priority') && !empty($validated['priority'] ?? null)) {
            $query->where('priority', $validated['priority']);
        }

        $tasks = $query->with('project')->orderBy('created_at', 'desc')->get();
        return view('tasks.index', compact('tasks'))->with('success', 'Recherche effectuée');
    }

    /**
     * API: met à jour le statut d'une tâche (PATCH), retourne la tâche pour animations.
     */
    public function updateStatus(Request $request, string $id, WebhookDispatcher $webhookDispatcher): JsonResponse
    {
        $task = $this->getTaskForUser($id);
        $validated = $request->validate(['status' => 'required|in:todo,in_progress,done']);
        $task->update($validated);
        $this->currentUser()->forgetStatsCache();
        $task->load('project');
        $webhookDispatcher->dispatch('task.updated', $task->toArray(), Auth::id());
        return response()->json($task);
    }

    /**
     * Récupère une tâche appartenant à un projet de l'utilisateur connecté.
     */
    private function getTaskForUser(string $taskId): Task
    {
        return Task::whereHas('project', function ($query) {
            $query->where('user_id', Auth::id());
        })->findOrFail($taskId);
    }

    /**
     * Helper pour récupérer l'utilisateur connecté avec le bon type pour l'IDE.
     */
    private function currentUser(): \App\Models\User
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return $user;
    }
}
