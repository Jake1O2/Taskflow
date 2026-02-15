<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SearchController extends Controller
{
    /**
     * Recherche globale dans les projets et tâches.
     */
    public function index(Request $request): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $query = $request->input('q', '');
        $type = $request->input('type', 'all');

        $projects = collect();
        $tasks = collect();

        if ($query) {
            if ($type === 'all' || $type === 'projects') {
                $projects = $user->projects()
                    ->where(function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%");
                })
                    ->orderBy('updated_at', 'desc')
                    ->get();
            }

            if ($type === 'all' || $type === 'tasks') {
                $tasks = Task::whereHas('project', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                    ->where(function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%");
                })
                    ->with('project')
                    ->orderBy('updated_at', 'desc')
                    ->get();
            }
        }

        return view('search', compact('query', 'type', 'projects', 'tasks'));
    }
}
