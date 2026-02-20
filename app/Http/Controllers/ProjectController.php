<?php

namespace App\Http\Controllers;

use App\Helpers\NotificationHelper;
use App\Models\Project;
use App\Services\WebhookDispatcher;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * API stats for dashboard widgets.
     */
    public function getStats(): JsonResponse
    {
        return response()->json(Auth::user()->getCachedStats());
    }

    /**
     * Show projects visible to the authenticated user.
     */
    public function index(): View
    {
        $projects = $this->accessibleProjectsQuery()
            ->withCount('tasks')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('projects.index', compact('projects'));
    }

    /**
     * Show project creation form.
     */
    public function create(): View
    {
        $teams = Auth::user()->teams()->get();

        return view('projects.create', compact('teams'));
    }

    /**
     * Validate and create a project.
     */
    public function store(Request $request, WebhookDispatcher $webhookDispatcher): RedirectResponse
    {
        $this->authorize('create', Project::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:preparation,in_progress,completed',
        ]);

        /** @var \App\Models\Project $project */
        $project = $this->currentUser()->projects()->create($validated);
        $this->currentUser()->forgetStatsCache();

        NotificationHelper::createNotification(
            Auth::id(),
            'project_created',
            'Project created',
            'A new project has been created.',
            route('projects.show', $project->id)
        );

        $webhookDispatcher->dispatch('project.created', $project->toArray(), Auth::id());

        return redirect()->route('projects.show', $project->id)->with('success', 'Project created');
    }

    /**
     * Show project with tasks.
     */
    public function show(string $id): View
    {
        $project = Project::with('tasks')->findOrFail($id);
        $this->authorize('view', $project);

        return view('projects.show', compact('project'));
    }

    /**
     * Show project edit form.
     */
    public function edit(string $id): View
    {
        $project = Project::findOrFail($id);
        $this->authorize('update', $project);

        $teams = Auth::user()->teams()->get();

        return view('projects.edit', compact('project', 'teams'));
    }

    /**
     * Validate and update project.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $project = Project::findOrFail($id);
        $this->authorize('update', $project);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:preparation,in_progress,completed',
        ]);

        $project->update($validated);
        $this->currentUser()->forgetStatsCache();

        return redirect()->route('projects.show', $project->id)->with('success', 'Project updated');
    }

    /**
     * Delete project.
     */
    public function destroy(string $id): RedirectResponse
    {
        $project = Project::findOrFail($id);
        $this->authorize('delete', $project);

        $project->delete();
        $this->currentUser()->forgetStatsCache();

        return redirect()->route('projects.index')->with('success', 'Project deleted');
    }

    /**
     * Show project Kanban board.
     */
    public function kanban(string $id): View
    {
        $project = Project::with('tasks')->findOrFail($id);
        $this->authorize('view', $project);

        $tasksByStatus = [
            'todo' => $project->tasks->where('status', 'todo'),
            'in_progress' => $project->tasks->where('status', 'in_progress'),
            'done' => $project->tasks->where('status', 'done'),
        ];

        return view('projects.kanban', compact('project', 'tasksByStatus'));
    }

    /**
     * Search projects by title among visible projects.
     */
    public function search(Request $request): View
    {
        $validated = $request->validate([
            'query' => 'required|string|max:255',
            'team_id' => 'nullable|exists:teams,id',
        ]);

        $projects = $this->accessibleProjectsQuery()
            ->where('title', 'like', '%' . $validated['query'] . '%')
            ->when(
                $validated['team_id'] ?? null,
                fn (Builder $builder, $teamId) => $builder->whereTeam((int) $teamId)
            )
            ->orderBy('created_at', 'desc')
            ->get();

        return view('projects.index', compact('projects'))->with('success', 'Search completed');
    }

    /**
     * Show project calendar.
     */
    public function calendar(Request $request, string $id): View
    {
        $project = Project::with('tasks')->findOrFail($id);
        $this->authorize('view', $project);

        $date = $request->has('date')
            ? \Carbon\Carbon::parse($request->query('date'))->startOfMonth()
            : now()->startOfMonth();

        return view('projects.calendar', [
            'project' => $project,
            'tasks' => $project->tasks,
            'date' => $date,
        ]);
    }

    /**
     * Show activity history for a project.
     */
    public function activity(Project $project): View
    {
        $this->authorize('view', $project);
        $logs = $project->activityLogs()->with('user')->paginate(20);

        return view('activity.index', [
            'entity' => $project,
            'entityType' => 'project',
            'logs' => $logs,
        ]);
    }

    /**
     * Get authenticated user with concrete model type.
     */
    private function currentUser(): \App\Models\User
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return $user;
    }

    /**
     * Projects visible to owner or same-team members.
     */
    private function accessibleProjectsQuery(): Builder
    {
        $user = $this->currentUser();
        $teamIds = $user->teams()->pluck('id')
            ->merge($user->teamMemberships()->pluck('teams.id'))
            ->unique()
            ->values();

        return Project::query()->where(function (Builder $query) use ($user, $teamIds) {
            $query->where('created_by', $user->id);

            if ($teamIds->isNotEmpty()) {
                $query->orWhereIn('team_id', $teamIds);
            }
        });
    }
}
