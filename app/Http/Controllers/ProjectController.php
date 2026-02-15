<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * API: statistiques (projets, tâches, équipes) pour animations (avec cache).
     */
    public function getStats(): JsonResponse
    {
        return response()->json(Auth::user()->getCachedStats());
    }

    /**
     * Affiche la liste des projets de l'utilisateur connecté.
     */
    public function index(): View
    {
        $projects = Auth::user()->projects()->withCount('tasks')->orderBy('created_at', 'desc')->get();
        return view('projects.index', compact('projects'));
    }

    /**
     * Affiche le formulaire de création.
     */
    public function create(): View
    {
        return view('projects.create');
    }

    /**
     * Valide et crée un projet.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:preparation,in_progress,completed',
        ]);

        $project = $this->currentUser()->projects()->create($validated);
        $this->currentUser()->forgetStatsCache();

        return redirect()->route('projects.show', $project->id)->with('success', 'Projet créé');
    }

    /**
     * Affiche les détails du projet + ses tâches.
     */
    public function show(string $id): View
    {
        // Utilisation de with('tasks') pour charger les tâches associées
        $project = $this->currentUser()->projects()->with('tasks')->findOrFail($id);
        return view('projects.show', compact('project'));
    }

    /**
     * Affiche le formulaire d'édition.
     */
    public function edit(string $id): View
    {
        $project = $this->currentUser()->projects()->findOrFail($id);
        $teams = Auth::user()->teams()->get();
        return view('projects.edit', compact('project', 'teams'));
    }

    /**
     * Valide et modifie le projet.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $project = $this->currentUser()->projects()->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:preparation,in_progress,completed',
        ]);

        $project->update($validated);
        $this->currentUser()->forgetStatsCache();

        return redirect()->route('projects.show', $project->id)->with('success', 'Projet modifié');
    }

    /**
     * Supprime le projet.
     */
    public function destroy(string $id): RedirectResponse
    {
        $project = $this->currentUser()->projects()->findOrFail($id);
        $project->delete();
        $this->currentUser()->forgetStatsCache();

        return redirect()->route('projects.index')->with('success', 'Projet supprimé');
    }

    /**
     * Affiche la vue Kanban du projet.
     */
    public function kanban(string $id): View
    {
        $project = $this->currentUser()->projects()->with('tasks')->findOrFail($id);
        $tasksByStatus = [
            'todo' => $project->tasks->where('status', 'todo'),
            'in_progress' => $project->tasks->where('status', 'in_progress'),
            'done' => $project->tasks->where('status', 'done'),
        ];
        return view('projects.kanban', compact('project', 'tasksByStatus'));
    }

    /**
     * Recherche des projets par titre (user owns).
     */
    public function search(Request $request): View
    {
        $validated = $request->validate([
            'query' => 'required|string|max:255',
            'team_id' => 'nullable|exists:teams,id',
        ]);
        $query = Project::where('user_id', Auth::id())
            ->where('title', 'like', '%' . $validated['query'] . '%')
            ->whereTeam($request->input('team_id'))
            ->orderBy('created_at', 'desc');
        $projects = $query->get();
        return view('projects.index', compact('projects'))->with('success', 'Recherche effectuée');
    }

    /**
     * Affiche la vue Calendrier du projet.
     */
    public function calendar(Request $request, string $id): View
    {
        $project = $this->currentUser()->projects()->with('tasks')->findOrFail($id);
        $date = $request->has('date')
            ?\Carbon\Carbon::parse($request->query('date'))->startOfMonth()
            : now()->startOfMonth();

        return view('projects.calendar', [
            'project' => $project,
            'tasks' => $project->tasks,
            'date' => $date,
        ]);
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