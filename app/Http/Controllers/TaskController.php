<?php

namespace App\Http\Controllers;

use App\Helpers\NotificationHelper;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskAssignmentLog;
use App\Models\User;
use App\Services\SlackNotifier;
use App\Services\WebhookDispatcher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function create(string $projectId): View
    {
        $project = Project::findOrFail($projectId);
        $this->authorize('create', [Task::class, $project]);

        return view('tasks.create', compact('project'));
    }

    public function store(Request $request, string $projectId, WebhookDispatcher $webhookDispatcher, SlackNotifier $slackNotifier): RedirectResponse
    {
        $project = Project::findOrFail($projectId);
        $this->authorize('create', [Task::class, $project]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:todo,in_progress,done',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        if (isset($validated['assigned_to']) && $validated['assigned_to']) {
            $assignee = User::findOrFail((int) $validated['assigned_to']);
            abort_unless($this->canBeAssignedToProject($project, $assignee), 422);
            $validated['assigned_at'] = now();
        }

        $task = $project->tasks()->create($validated);
        $this->currentUser()->forgetStatsCache();

        if (! empty($validated['assigned_to'])) {
            TaskAssignmentLog::create([
                'task_id' => $task->id,
                'assigned_by' => Auth::id(),
                'assigned_to' => (int) $validated['assigned_to'],
                'assigned_at' => $task->assigned_at ?? now(),
            ]);
        }

        $task->load(['project.team', 'assignee']);

        NotificationHelper::createNotification(
            Auth::id(),
            'task_created',
            'Task created',
            'A new task has been created.',
            route('projects.show', $projectId)
        );

        $slackNotifier->notifyTaskCreated($task);
        $webhookDispatcher->dispatch('task.created', $task->toArray(), Auth::id(), false);

        if ($task->assignee) {
            $slackNotifier->notifyTaskAssigned($task, $task->assignee);
            $webhookDispatcher->dispatch('task.assigned', [
                'task_id' => (int) $task->id,
                'project_id' => (int) $task->project_id,
                'assigned_to' => (int) $task->assignee->id,
                'assigned_by' => (int) (Auth::id() ?? 0),
            ], Auth::id(), false);
        }

        return redirect()->route('projects.show', $projectId)->with('success', 'Task created');
    }

    public function show(string $id): View
    {
        $task = $this->getTaskForUser($id);
        $this->authorize('view', $task);

        return view('tasks.show', [
            'task' => $task,
            'comments' => $task->comments()->latest()->get(),
        ]);
    }

    public function edit(string $id): View
    {
        $task = $this->getTaskForUser($id);
        $this->authorize('update', $task);

        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, string $id, WebhookDispatcher $webhookDispatcher, SlackNotifier $slackNotifier): RedirectResponse
    {
        $task = $this->getTaskForUser($id);
        $this->authorize('update', $task);
        $before = $this->trackedTaskState($task);
        $previousAssigneeId = $before['assigned_to_id'];

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:todo,in_progress,done',
            'due_date' => 'nullable|date',
            'priority' => 'nullable|string|max:50',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        if (array_key_exists('assigned_to', $validated)) {
            if ($validated['assigned_to']) {
                $assignee = User::findOrFail((int) $validated['assigned_to']);
                abort_unless($this->canBeAssignedToProject($task->project, $assignee), 422);
            }

            $validated['assigned_at'] = $validated['assigned_to'] ? now() : null;
        }

        $task->update($validated);
        $this->currentUser()->forgetStatsCache();
        $task->load(['project.team', 'assignee']);

        $changes = $this->extractTaskChanges($before, $task);
        if ($changes !== []) {
            $slackNotifier->notifyTaskUpdated($task, $changes);
        }

        $webhookDispatcher->dispatch('task.updated', array_merge($task->toArray(), [
            'task_id' => (int) $task->id,
            'changes' => $changes,
        ]), Auth::id(), false);

        $currentAssigneeId = (int) ($task->assigned_to ?? 0);
        if ($currentAssigneeId && $currentAssigneeId !== $previousAssigneeId && $task->assignee) {
            $slackNotifier->notifyTaskAssigned($task, $task->assignee);
            $webhookDispatcher->dispatch('task.assigned', [
                'task_id' => (int) $task->id,
                'project_id' => (int) $task->project_id,
                'assigned_to' => $currentAssigneeId,
                'assigned_by' => (int) (Auth::id() ?? 0),
            ], Auth::id(), false);
        }

        return redirect()->route('projects.show', $task->project_id)->with('success', 'Task updated');
    }

    public function destroy(string $id): RedirectResponse
    {
        $task = $this->getTaskForUser($id);
        $this->authorize('delete', $task);

        $projectId = $task->project_id;
        $task->delete();
        $this->currentUser()->forgetStatsCache();

        return redirect()->route('projects.show', $projectId)->with('success', 'Task deleted');
    }

    public function search(Request $request): View
    {
        $validated = $request->validate(['query' => 'required|string|max:255']);

        $tasks = Task::query()
            ->whereIn('project_id', $this->accessibleProjectIds())
            ->where('title', 'like', '%' . $validated['query'] . '%')
            ->with('project')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tasks.index', compact('tasks'))->with('success', 'Search completed');
    }

    public function filter(Request $request): View
    {
        $validated = $request->validate([
            'status' => 'required|in:todo,in_progress,done',
            'priority' => 'nullable|string|max:50',
        ]);

        $query = Task::query()
            ->whereIn('project_id', $this->accessibleProjectIds())
            ->where('status', $validated['status']);

        if (Schema::hasColumn('tasks', 'priority') && ! empty($validated['priority'] ?? null)) {
            $query->where('priority', $validated['priority']);
        }

        $tasks = $query->with('project')->orderBy('created_at', 'desc')->get();

        return view('tasks.index', compact('tasks'))->with('success', 'Search completed');
    }

    public function updateStatus(Request $request, string $id, WebhookDispatcher $webhookDispatcher, SlackNotifier $slackNotifier): JsonResponse
    {
        $task = $this->getTaskForUser($id);
        $this->authorize('update', $task);
        $before = $this->trackedTaskState($task);

        $validated = $request->validate(['status' => 'required|in:todo,in_progress,done']);
        $task->update($validated);
        $this->currentUser()->forgetStatsCache();
        $task->load(['project.team', 'assignee']);

        $changes = $this->extractTaskChanges($before, $task);
        if ($changes !== []) {
            $slackNotifier->notifyTaskUpdated($task, $changes);
        }

        $webhookDispatcher->dispatch('task.updated', array_merge($task->toArray(), [
            'task_id' => (int) $task->id,
            'changes' => $changes,
        ]), Auth::id(), false);

        return response()->json($task);
    }

    public function assign(
        Request $request,
        string $taskId,
        string $userId,
        WebhookDispatcher $webhookDispatcher,
        SlackNotifier $slackNotifier
    ): JsonResponse|RedirectResponse
    {
        $task = Task::with('project.team')->findOrFail($taskId);
        $this->authorize('assign', $task);

        $assignee = User::findOrFail($userId);
        abort_unless($this->canBeAssigned($task, $assignee), 422);

        $assignedAt = now();

        $task->update([
            'assigned_to' => $assignee->id,
            'assigned_at' => $assignedAt,
        ]);

        TaskAssignmentLog::create([
            'task_id' => $task->id,
            'assigned_by' => (int) Auth::id(),
            'assigned_to' => $assignee->id,
            'assigned_at' => $assignedAt,
        ]);

        NotificationHelper::createNotification(
            $assignee->id,
            'task_assigned',
            'Task assigned',
            "You have been assigned to task: {$task->title}",
            route('tasks.show', $task->id)
        );

        $slackNotifier->notifyTaskAssigned($task, $assignee);
        $webhookDispatcher->dispatch('task.assigned', [
            'task_id' => (int) $task->id,
            'project_id' => (int) $task->project_id,
            'assigned_to' => $assignee->id,
            'assigned_by' => (int) (Auth::id() ?? 0),
        ], Auth::id(), false);

        return $this->assignmentResponse($request, $task, 'Task assigned successfully');
    }

    public function unassign(Request $request, string $taskId): JsonResponse|RedirectResponse
    {
        $task = Task::with(['project', 'assignee'])->findOrFail($taskId);
        $this->authorize('assign', $task);

        $previousAssignee = $task->assignee;

        $task->update([
            'assigned_to' => null,
            'assigned_at' => null,
        ]);

        if ($previousAssignee) {
            NotificationHelper::createNotification(
                $previousAssignee->id,
                'task_unassigned',
                'Task unassigned',
                "You have been unassigned from task: {$task->title}",
                route('tasks.show', $task->id)
            );
        }

        return $this->assignmentResponse($request, $task, 'Task unassigned successfully');
    }

    public function activity(Task $task): View
    {
        $this->authorize('view', $task);
        $logs = $task->activityLogs()->with('user')->paginate(20);

        return view('activity.index', [
            'entity' => $task,
            'entityType' => 'task',
            'logs' => $logs,
        ]);
    }

    private function getTaskForUser(string $taskId): Task
    {
        $task = Task::with('project')->findOrFail($taskId);
        $this->authorize('view', $task);

        return $task;
    }

    private function assignmentResponse(Request $request, Task $task, string $message): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'task' => $task->fresh(['assignee', 'project']),
            ]);
        }

        return redirect()->route('tasks.show', $task->id)->with('success', $message);
    }

    private function canBeAssigned(Task $task, User $assignee): bool
    {
        $project = $task->project;

        return $project ? $this->canBeAssignedToProject($project, $assignee) : false;
    }

    private function canBeAssignedToProject(Project $project, User $assignee): bool
    {
        if ((int) $project->created_by === (int) $assignee->id) {
            return true;
        }

        if (! $project->team_id) {
            return false;
        }

        return $project->team
            ? $project->team->members()->where('user_id', $assignee->id)->exists()
            : false;
    }

    private function trackedTaskState(Task $task): array
    {
        $task->loadMissing('assignee');

        return [
            'status' => $this->normalizeStatus((string) $task->status),
            'priority' => $this->normalizePriority($task->priority),
            'assignee' => $task->assignee?->name ?? 'Unassigned',
            'due_date' => $task->due_date ? $task->due_date->format('Y-m-d') : 'No due date',
            'assigned_to_id' => (int) ($task->assigned_to ?? 0),
        ];
    }

    private function extractTaskChanges(array $before, Task $task): array
    {
        $after = $this->trackedTaskState($task);
        $changes = [];

        foreach (['status', 'priority', 'assignee', 'due_date'] as $field) {
            if ($before[$field] !== $after[$field]) {
                $changes[$field] = [
                    'from' => $before[$field],
                    'to' => $after[$field],
                ];
            }
        }

        return $changes;
    }

    private function normalizeStatus(string $status): string
    {
        return strtoupper(str_replace(['-', ' '], '_', trim($status)));
    }

    private function normalizePriority(string|null $priority): string
    {
        $value = trim((string) $priority);

        return $value === '' ? 'N/A' : strtoupper(str_replace(['-', ' '], '_', $value));
    }

    private function accessibleProjectIds(): array
    {
        $user = $this->currentUser();

        $ownedIds = Project::query()
            ->where('created_by', $user->id)
            ->pluck('id');

        $teamIds = $user->teams()->pluck('id')
            ->merge($user->teamMemberships()->pluck('teams.id'))
            ->unique()
            ->values();

        $teamProjectIds = Project::query()
            ->whereIn('team_id', $teamIds)
            ->pluck('id');

        return $ownedIds
            ->merge($teamProjectIds)
            ->unique()
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();
    }

    private function currentUser(): \App\Models\User
    {
        $user = Auth::user();

        return $user;
    }
}
