<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
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
                    $q->where('created_by', $user->id);
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

    public function searchUsers(Request $request): JsonResponse
    {
        $user = Auth::user();
        $validated = $request->validate([
            'q' => 'nullable|string|max:255',
        ]);

        $q = trim((string) ($validated['q'] ?? ''));
        $teamIds = $user->teams()->pluck('id')
            ->merge($user->teamMemberships()->pluck('teams.id'))
            ->unique()
            ->values();

        $collaboratorIds = TeamMember::query()
            ->whereIn('team_id', $teamIds)
            ->pluck('user_id')
            ->merge(Team::query()->whereIn('id', $teamIds)->pluck('user_id'))
            ->push($user->id)
            ->unique()
            ->values();

        $users = User::query()
            ->whereIn('id', $collaboratorIds)
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($qBuilder) use ($q) {
                    $qBuilder->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->orderBy('name')
            ->limit(15)
            ->get(['id', 'name', 'email'])
            ->map(fn (User $mentionedUser) => [
                'id' => $mentionedUser->id,
                'name' => $mentionedUser->name,
                'email' => $mentionedUser->email,
                'mention' => '@' . preg_replace('/\W+/', '', $mentionedUser->name),
            ]);

        return response()->json([
            'success' => true,
            'data' => $users,
        ]);
    }
}
