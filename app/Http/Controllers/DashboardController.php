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
        $projectIds = Auth::user()->projects()->pluck('id');

        $stats = [
            'projects' => Auth::user()->projects()->count(),
            'tasks' => Task::whereIn('project_id', $projectIds)->count(),
            'teams' => Auth::user()->teams()->count(),
        ];
        
        $recentProjects = Auth::user()->projects()->orderBy('created_at', 'desc')->limit(5)->get();
        
        $recentTasks = Task::whereIn('project_id', $projectIds)->orderBy('created_at', 'desc')->limit(5)->get();
        
        return view('dashboard', compact('stats', 'recentProjects', 'recentTasks'));
    }
}