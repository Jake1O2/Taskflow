<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
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
            'todo' => (clone $tasksQuery)->where('status', 'todo')->count(),
            'in_progress' => (clone $tasksQuery)->where('status', 'in_progress')->count(),
            'completed' => (clone $tasksQuery)->where('status', 'done')->count(),
        ];

        // Récupération des 5 prochaines tâches (non terminées, avec date d'échéance, triées par date)
        $upcomingTasks = (clone $tasksQuery)
            ->where('status', '!=', 'done')
            ->whereNotNull('due_date')
            ->orderBy('due_date', 'asc')
            ->with('project') // Chargement du projet associé pour l'affichage
            ->take(5)
            ->get();

        return view('dashboard', compact('projects', 'stats', 'upcomingTasks'));
    }
}