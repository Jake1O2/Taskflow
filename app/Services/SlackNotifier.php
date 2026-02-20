<?php

namespace App\Services;

use App\Helpers\SlackMessageFormatter;
use App\Models\Comment;
use App\Models\Project;
use App\Models\SlackWorkspace;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SlackNotifier
{
    public function notifyProjectCreated(Project $project): void
    {
        $project->loadMissing(['user', 'team']);
        $this->notifyWorkspace((int) $project->team_id, 'project.created', SlackMessageFormatter::project($project));
    }

    public function notifyTaskCreated(Task $task): void
    {
        $task->loadMissing(['project.team', 'assignee']);
        $this->notifyWorkspace((int) ($task->project?->team_id ?? 0), 'task.created', SlackMessageFormatter::task($task));
    }

    /**
     * @param array<string, array{from: string, to: string}> $changes
     */
    public function notifyTaskUpdated(Task $task, array $changes): void
    {
        if ($changes === []) {
            return;
        }

        $task->loadMissing(['project.team', 'assignee']);
        $this->notifyWorkspace((int) ($task->project?->team_id ?? 0), 'task.updated', SlackMessageFormatter::taskUpdated($task, $changes));
    }

    public function notifyCommentAdded(Comment $comment): void
    {
        $comment->loadMissing(['user', 'task.project.team']);
        $teamId = (int) ($comment->task?->project?->team_id ?? 0);
        $this->notifyWorkspace($teamId, 'comment.added', SlackMessageFormatter::comment($comment));
    }

    public function notifyMemberAdded(Team $team, User $member): void
    {
        $this->notifyWorkspace((int) $team->id, 'team.member.added', SlackMessageFormatter::memberAdded($team, $member));
    }

    public function notifyTaskAssigned(Task $task, User $assignee): void
    {
        $task->loadMissing('project.team');
        $teamId = (int) ($task->project?->team_id ?? 0);
        $this->notifyWorkspace($teamId, 'task.assigned', SlackMessageFormatter::taskAssigned($task, $assignee));
    }

    public function notifyEvent(string $event, array $payload): void
    {
        switch ($event) {
            case 'project.created':
                $projectId = (int) ($payload['id'] ?? $payload['project_id'] ?? 0);
                if (! $projectId) {
                    return;
                }

                $project = Project::with(['user', 'team'])->find($projectId);
                if ($project) {
                    $this->notifyProjectCreated($project);
                }
                return;

            case 'task.created':
                $taskId = (int) ($payload['id'] ?? $payload['task_id'] ?? 0);
                if (! $taskId) {
                    return;
                }

                $task = Task::with(['project.team', 'assignee'])->find($taskId);
                if ($task) {
                    $this->notifyTaskCreated($task);
                }
                return;

            case 'task.updated':
                $taskId = (int) ($payload['id'] ?? $payload['task_id'] ?? 0);
                if (! $taskId) {
                    return;
                }

                $task = Task::with(['project.team', 'assignee'])->find($taskId);
                $changes = is_array($payload['changes'] ?? null) ? $payload['changes'] : [];
                if ($task) {
                    $this->notifyTaskUpdated($task, $changes);
                }
                return;

            case 'comment.added':
                $commentId = (int) ($payload['id'] ?? $payload['comment_id'] ?? 0);
                if (! $commentId) {
                    return;
                }

                $comment = Comment::with(['user', 'task.project.team'])->find($commentId);
                if ($comment) {
                    $this->notifyCommentAdded($comment);
                }
                return;

            case 'team.member.added':
                $teamId = (int) ($payload['team_id'] ?? 0);
                $memberId = (int) ($payload['user_id'] ?? 0);
                if (! $teamId || ! $memberId) {
                    return;
                }

                $team = Team::find($teamId);
                $member = User::find($memberId);
                if ($team && $member) {
                    $this->notifyMemberAdded($team, $member);
                }
                return;

            case 'task.assigned':
                $taskId = (int) ($payload['task_id'] ?? 0);
                $assigneeId = (int) ($payload['assigned_to'] ?? $payload['assignee_id'] ?? 0);
                if (! $taskId || ! $assigneeId) {
                    return;
                }

                $task = Task::with('project.team')->find($taskId);
                $assignee = User::find($assigneeId);
                if ($task && $assignee) {
                    $this->notifyTaskAssigned($task, $assignee);
                }
                return;
        }
    }

    private function notifyWorkspace(int $teamId, string $event, array $message): void
    {
        if (! $teamId) {
            return;
        }

        $workspace = SlackWorkspace::where('team_id', $teamId)
            ->where('active', true)
            ->first();

        if (! $workspace || ! $workspace->slack_token || ! $workspace->slack_channel_id) {
            return;
        }

        $events = is_array($workspace->events) ? $workspace->events : [];
        if (! in_array($event, $events, true)) {
            return;
        }

        $response = Http::withToken($workspace->slack_token)
            ->asJson()
            ->timeout(15)
            ->post('https://slack.com/api/chat.postMessage', array_merge([
                'channel' => $workspace->slack_channel_id,
            ], $message));

        $payload = $response->json();
        if (! $response->ok() || ! ($payload['ok'] ?? false)) {
            Log::warning('Slack notify failed', [
                'workspace_id' => $workspace->id,
                'event' => $event,
                'error' => $payload['error'] ?? 'unknown_error',
            ]);
        }
    }
}
