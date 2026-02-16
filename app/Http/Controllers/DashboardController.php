<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord avec les statistiques et les tâches à venir.
     */
    public function index(): View
    {
        $projectIds = Auth::user()->projects()->pluck('id');

        $stats = [
            'projects' => Auth::user()->projects()->count(),
            'tasks' => Task::whereIn('project_id', $projectIds)->count(),
            'teams' => Auth::user()->teams()->count(),
        ];
        
        // Task Completion Rate pour affichage
        $totalTasks = Task::whereIn('project_id', $projectIds)->count();
        $completedTasks = Task::whereIn('project_id', $projectIds)->where('status', 'done')->count();
        $completionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
        
        $recentProjects = Auth::user()->projects()->orderBy('created_at', 'desc')->limit(5)->get();
        
        $recentTasks = Task::whereIn('project_id', $projectIds)->orderBy('created_at', 'desc')->limit(5)->get();
        
        return view('dashboard', compact('stats', 'recentProjects', 'recentTasks', 'completionRate', 'totalTasks', 'completedTasks'));
    }

    /**
     * API: Stats avancées pour les graphiques.
     */
    public function getAnalytics(): JsonResponse
    {
        $user = Auth::user();
        $projectIds = $user->projects()->pluck('id');

        // Task Completion Rate
        $totalTasks = Task::whereIn('project_id', $projectIds)->count();
        $completedTasks = Task::whereIn('project_id', $projectIds)->where('status', 'done')->count();
        $taskCompletionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 1) : 0;

        // Projects by Status
        $projectsByStatus = $user->projects()
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        // Tasks Over Time (Last Month, per week)
        $tasksOverTime = Task::whereIn('project_id', $projectIds)
            ->where('created_at', '>=', now()->subMonth())
            ->get()
            ->groupBy(fn($task) => $task->created_at->format('W'))
            ->map(fn($tasks) => $tasks->count());

        // Team Activity
        $teamActivity = $user->teams()->with(['projects' => fn($q) => $q->latest('updated_at')])->get()
            ->map(fn($team) => [
                'name' => $team->name,
                'last_activity' => $team->projects->first()?->updated_at?->diffForHumans() ?? 'Aucune'
            ]);

        return response()->json(compact('taskCompletionRate', 'projectsByStatus', 'tasksOverTime', 'teamActivity'));
    }
}