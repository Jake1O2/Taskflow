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
    public function index(): View
    {
        $user = Auth::user();
        $projectIds = $user->projects()->pluck('id');

        $stats = [
            'projects' => $user->projects()->count(),
            'tasks' => Task::whereIn('project_id', $projectIds)->count(),
            'teams' => $user->teams()->count(),
        ];
        
        $recentProjects = $user->projects()->orderBy('created_at', 'desc')->limit(5)->get();
        
        $recentTasks = Task::whereIn('project_id', $projectIds)
            ->where('status', '!=', 'done')
            ->with('project')
            ->orderBy('due_date', 'asc')
            ->limit(5)
            ->get();

        $totalTasks = $stats['tasks'];
        $completedTasks = Task::whereIn('project_id', $projectIds)->where('status', 'done')->count();
        $taskCompletionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        $projectsByStatus = $user->projects()
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $activityData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dayLabel = $date->format('D'); // Mon, Tue...
            
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