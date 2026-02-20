<?php

namespace App\Helpers;

use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;

class SlackMessageFormatter
{
    public static function project(Project $project): array
    {
        $projectUrl = route('projects.show', $project->id);
        $description = trim((string) $project->description) !== '' ? (string) $project->description : 'No description';
        $status = strtoupper(str_replace(' ', '_', str_replace('_', ' ', (string) $project->status)));
        $createdBy = $project->user?->name ?? 'Unknown';

        return [
            'text' => "Project: {$project->title}",
            'attachments' => [
                [
                    'color' => '#2563eb',
                    'title' => 'New Project Created',
                    'text' => "Project: {$project->title}",
                    'fields' => [
                        ['title' => 'Description', 'value' => $description, 'short' => false],
                        ['title' => 'Status', 'value' => $status, 'short' => true],
                        ['title' => 'Created by', 'value' => $createdBy, 'short' => true],
                        ['title' => 'Link', 'value' => "<{$projectUrl}|Open project>", 'short' => false],
                    ],
                ],
            ],
        ];
    }

    public static function task(Task $task): array
    {
        $projectName = $task->project?->title ?? 'Unknown project';
        $description = trim((string) $task->description) !== '' ? (string) $task->description : 'No description';
        $assignee = $task->assignee?->name ?? 'Unassigned';
        $dueDate = $task->due_date ? $task->due_date->format('Y-m-d') : 'No due date';

        return [
            'text' => "Task: {$task->title}",
            'attachments' => [
                [
                    'color' => '#059669',
                    'title' => 'New Task Created',
                    'text' => "Task: {$task->title}",
                    'fields' => [
                        ['title' => 'Task title', 'value' => $task->title, 'short' => false],
                        ['title' => 'Project name', 'value' => $projectName, 'short' => true],
                        ['title' => 'Description', 'value' => $description, 'short' => false],
                        ['title' => 'Assignee', 'value' => $assignee, 'short' => true],
                        ['title' => 'Due date', 'value' => $dueDate, 'short' => true],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param array<string, array{from: string, to: string}> $changes
     */
    public static function taskUpdated(Task $task, array $changes): array
    {
        $changeText = self::formatTaskChanges($changes);

        return [
            'text' => "Task updated: {$task->title}",
            'attachments' => [
                [
                    'color' => '#f59e0b',
                    'title' => 'Task Updated',
                    'text' => "Task: {$task->title}",
                    'fields' => [
                        ['title' => 'Changes', 'value' => $changeText, 'short' => false],
                    ],
                ],
            ],
        ];
    }

    public static function comment(Comment $comment): array
    {
        $author = $comment->user?->name ?? 'Unknown';
        $taskName = $comment->task?->title ?? 'Unknown task';
        $taskUrl = $comment->task ? route('tasks.show', $comment->task->id) : null;

        return [
            'text' => "Comment on {$taskName}",
            'attachments' => [
                [
                    'color' => '#8b5cf6',
                    'title' => 'New Comment Added',
                    'text' => (string) $comment->content,
                    'fields' => [
                        ['title' => 'Author', 'value' => $author, 'short' => true],
                        ['title' => 'Task name', 'value' => $taskName, 'short' => true],
                        ['title' => 'Link', 'value' => $taskUrl ? "<{$taskUrl}|Open task>" : 'N/A', 'short' => false],
                    ],
                ],
            ],
        ];
    }

    public static function memberAdded(Team $team, User $member): array
    {
        return [
            'text' => "{$member->name} has been added to {$team->name}",
            'attachments' => [
                [
                    'color' => '#10b981',
                    'title' => 'Team Member Added',
                    'text' => "{$member->name} has been added to {$team->name}",
                    'fields' => [
                        ['title' => 'Member', 'value' => $member->name, 'short' => true],
                        ['title' => 'Team', 'value' => $team->name, 'short' => true],
                    ],
                ],
            ],
        ];
    }

    public static function taskAssigned(Task $task, User $assignee): array
    {
        return [
            'text' => "{$assignee->name} has been assigned to {$task->title}",
            'attachments' => [
                [
                    'color' => '#3b82f6',
                    'title' => 'Task Assigned',
                    'text' => "{$assignee->name} has been assigned to {$task->title}",
                    'fields' => [
                        ['title' => 'Task', 'value' => $task->title, 'short' => true],
                        ['title' => 'Assignee', 'value' => $assignee->name, 'short' => true],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param array<string, array{from: string, to: string}> $changes
     */
    private static function formatTaskChanges(array $changes): string
    {
        $labels = [
            'status' => 'Status',
            'priority' => 'Priority',
            'assignee' => 'Assignee',
            'due_date' => 'Due date',
        ];

        $lines = [];
        foreach ($changes as $field => $values) {
            $label = $labels[$field] ?? ucfirst(str_replace('_', ' ', $field));
            $from = $values['from'] ?? 'N/A';
            $to = $values['to'] ?? 'N/A';
            $lines[] = "{$label} changed from {$from} to {$to}";
        }

        return $lines === [] ? 'No tracked field changed.' : implode("\n", $lines);
    }
}
