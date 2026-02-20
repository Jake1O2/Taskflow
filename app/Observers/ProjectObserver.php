<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class ProjectObserver
{
    public function created(Project $project): void
    {
        $this->log($project, 'created', $this->snapshot($project));
    }

    public function updated(Project $project): void
    {
        $changes = [];

        foreach ($project->getChanges() as $field => $newValue) {
            if ($field === 'updated_at') {
                continue;
            }

            $changes[$field] = [
                'old' => $project->getOriginal($field),
                'new' => $newValue,
            ];
        }

        if ($changes !== []) {
            $this->log($project, 'updated', $changes);
        }
    }

    public function deleted(Project $project): void
    {
        $this->log($project, 'deleted', $this->snapshot($project));
    }

    private function log(Project $project, string $action, array $changes = []): void
    {
        ActivityLog::create([
            'user_id' => Auth::id() ?? $project->created_by,
            'loggable_type' => Project::class,
            'loggable_id' => $project->id,
            'action' => $action,
            'changes' => $changes,
            'created_at' => now(),
        ]);
    }

    private function snapshot(Project $project): array
    {
        return collect($project->getAttributes())
            ->except(['created_at', 'updated_at'])
            ->toArray();
    }
}
