<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\TeamMember;
use Illuminate\Support\Facades\Auth;

class TeamMemberObserver
{
    public function created(TeamMember $teamMember): void
    {
        $this->log($teamMember, 'created', $this->snapshot($teamMember));
    }

    public function updated(TeamMember $teamMember): void
    {
        $changes = [];

        foreach ($teamMember->getChanges() as $field => $newValue) {
            if ($field === 'updated_at') {
                continue;
            }

            $changes[$field] = [
                'old' => $teamMember->getOriginal($field),
                'new' => $newValue,
            ];
        }

        if ($changes !== []) {
            $this->log($teamMember, 'updated', $changes);
        }
    }

    public function deleted(TeamMember $teamMember): void
    {
        $this->log($teamMember, 'deleted', $this->snapshot($teamMember));
    }

    private function log(TeamMember $teamMember, string $action, array $changes = []): void
    {
        ActivityLog::create([
            'user_id' => Auth::id() ?? $teamMember->team?->user_id,
            'loggable_type' => TeamMember::class,
            'loggable_id' => $teamMember->id,
            'action' => $action,
            'changes' => $changes,
            'created_at' => now(),
        ]);
    }

    private function snapshot(TeamMember $teamMember): array
    {
        return collect($teamMember->getAttributes())
            ->except(['created_at', 'updated_at'])
            ->toArray();
    }
}
