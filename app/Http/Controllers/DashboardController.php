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
            'projects' => $projects->count(),
            'tasks' => (clone $tasksQuery)->count(),
            'teams' => $user->teams()->count() + $user->teamMemberships()->count(),
        ];

        // 5 derniers projets
        $recentProjects = $user->projects()
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        // 5 tâches récentes
        $recentTasks = (clone $tasksQuery)
            ->orderBy('created_at', 'desc')
            ->with('project')
            ->take(5)
            ->get();

        // Timestamps pour animations au chargement
        $animationTimestamps = [
            'loadedAt' => now()->toIso8601String(),
            'loadedAtUnix' => now()->timestamp,
        ];

        return view('dashboard', compact('projects', 'stats', 'recentProjects', 'recentTasks', 'animationTimestamps'));
    }
}