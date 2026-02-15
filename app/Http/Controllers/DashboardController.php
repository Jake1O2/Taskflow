<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord avec les statistiques et les tâches à venir.
     */
    public function index(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Récupération des projets de l'utilisateur
        $projects = $user->projects()->get();

        // Requête de base pour les tâches appartenant aux projets de l'utilisateur
        $tasksQuery = Task::whereHas('project', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        });

        // Calcul des statistiques
        $stats = [
            'total_projects' => $projects->count(),
            'total_tasks' => (clone $tasksQuery)->count(),
            'total_teams' => $user->teams()->count() + $user->teamMemberships()->count(),
            'projects' => $projects->count(),
            'todo' => (clone $tasksQuery)->where('status', 'todo')->count(),
            'in_progress' => (clone $tasksQuery)->where('status', 'in_progress')->count(),
            'completed' => (clone $tasksQuery)->where('status', 'done')->count(),
            'teams' => $user->teams()->count() + $user->teamMemberships()->count(),
        ];

        // 5 derniers projets
        $recentProjects = $user->projects()
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        // 5 prochaines tâches (non terminées, avec date d'échéance)
        $upcomingTasks = (clone $tasksQuery)
            ->where('status', '!=', 'done')
            ->whereNotNull('due_date')
            ->orderBy('due_date', 'asc')
            ->with('project')
            ->take(5)
            ->get();

        // 5 tâches récemment modifiées
        $recentTasks = (clone $tasksQuery)
            ->orderBy('updated_at', 'desc')
            ->with('project')
            ->take(5)
            ->get();

        return view('dashboard', compact('projects', 'stats', 'upcomingTasks', 'recentProjects', 'recentTasks'));
    }
}