<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskObserver
{
    public function created(Task $task): void
    {
        $this->log($task, 'created', $this->snapshot($task));
    }

    public function updated(Task $task): void
    {
        $changes = [];

        foreach ($task->getChanges() as $field => $newValue) {
            if ($field === 'updated_at') {
                continue;
            }

            $changes[$field] = [
                'old' => $task->getOriginal($field),
                'new' => $newValue,
            ];
        }

        if ($changes !== []) {
            $this->log($task, 'updated', $changes);
        }
    }

    public function deleted(Task $task): void
    {
        $this->log($task, 'deleted', $this->snapshot($task));
    }

    private function log(Task $task, string $action, array $changes = []): void
    {
        ActivityLog::create([
            'user_id' => Auth::id() ?? $task->project?->created_by,
            'loggable_type' => Task::class,
            'loggable_id' => $task->id,
            'action' => $action,
            'changes' => $changes,
            'created_at' => now(),
        ]);
    }

    private function snapshot(Task $task): array
    {
        return collect($task->getAttributes())
            ->except(['created_at', 'updated_at'])
            ->toArray();
    }
}
