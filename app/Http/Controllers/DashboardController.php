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
        $user = Auth::user();

        // Stats en cache (TTL 120s), requêtes optimisées côté User::computeStats()
        $stats = $user->getCachedStats();

        // 5 derniers projets (une requête)
        $recentProjects = $user->projects()
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        // 5 tâches récentes avec projet en eager loading (évite N+1)
        $recentTasks = Task::whereHas('project', function ($query) use ($user) {
            $query->where('created_by', $user->id); // clé projet = created_by (User model)
        })
            ->with('project')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $animationTimestamps = [
            'loadedAt' => now()->toIso8601String(),
            'loadedAtUnix' => now()->timestamp,
        ];

        return view('dashboard', compact('stats', 'recentProjects', 'recentTasks', 'animationTimestamps'));
    }
}