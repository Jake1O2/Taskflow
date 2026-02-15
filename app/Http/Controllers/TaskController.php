<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    public function store(Request $request, string $projectId): RedirectResponse
    {
        $project = $this->currentUser()->projects()->findOrFail($projectId);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:todo,in_progress,done',
            'due_date' => 'nullable|date',
        ]);

        $project->tasks()->create($validated);

        return redirect()->route('projects.show', $projectId)->with('success', 'Tâche créée');
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
    public function update(Request $request, string $id): RedirectResponse
    {
        $task = $this->getTaskForUser($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:todo,in_progress,done',
            'due_date' => 'nullable|date',
        ]);

        $task->update($validated);

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

        return redirect()->route('projects.show', $projectId)->with('success', 'Tâche supprimée');
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