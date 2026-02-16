<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord.
     */
    public function index(): View
    {
        $user = Auth::user();
        $projectIds = $user->projects()->pluck('id');

        // 1. Stats de base
        $stats = [
            'projects' => $user->projects()->count(),
            'tasks' => Task::whereIn('project_id', $projectIds)->count(),
            'teams' => $user->teams()->count(),
        ];
        
        // 2. Activité récente
        $recentProjects = $user->projects()->orderBy('created_at', 'desc')->limit(5)->get();
        
        $recentTasks = Task::whereIn('project_id', $projectIds)
            ->where('status', '!=', 'done')
            ->with('project')
            ->orderBy('due_date', 'asc')
            ->limit(5)
            ->get();

        // 3. Analytics pour la vue
        $totalTasks = $stats['tasks'];
        $completedTasks = Task::whereIn('project_id', $projectIds)->where('status', 'done')->count();
        $taskCompletionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        // Projets par statut
        $projectsByStatus = $user->projects()
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // Données d'activité (7 derniers jours)
        $activityData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dayLabel = $date->format('D'); // Mon, Tue...
            
            // Compte les tâches créées ou complétées ce jour-là
            $count = Task::whereIn('project_id', $projectIds)
                ->whereDate('updated_at', $date)
                ->count();
                
            $activityData[$dayLabel] = $count;
        }

        return view('dashboard', compact(
            'stats', 
            'recentProjects', 
            'recentTasks',
            'totalTasks',
            'completedTasks',
            'taskCompletionRate',
            'projectsByStatus',
            'activityData'
        ));
    }

    /**
     * API: Stats avancées pour les graphiques (si utilisé via AJAX).
     */
    public function getAnalytics(): JsonResponse
    {
        $user = Auth::user();
        $projectIds = $user->projects()->pluck('id');

        $totalTasks = Task::whereIn('project_id', $projectIds)->count();
        $completedTasks = Task::whereIn('project_id', $projectIds)->where('status', 'done')->count();
        $taskCompletionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 1) : 0;

        $projectsByStatus = $user->projects()
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $tasksOverTime = Task::whereIn('project_id', $projectIds)
            ->where('created_at', '>=', now()->subMonth())
            ->get()
            ->groupBy(fn($task) => $task->created_at->format('W'))
            ->map(fn($tasks) => $tasks->count());

        $teamActivity = $user->teams()->with(['projects' => fn($q) => $q->latest('updated_at')])->get()
            ->map(fn($team) => [
                'name' => $team->name,
                'last_activity' => $team->projects->first()?->updated_at?->diffForHumans() ?? 'Aucune'
            ]);

        return response()->json(compact('taskCompletionRate', 'projectsByStatus', 'tasksOverTime', 'teamActivity'));
    }
}