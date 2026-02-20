<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\RolePermission;
use App\Models\Task;
use App\Models\TeamMember;
use App\Models\User;

class TaskPolicy
{
    public function view(User $user, Task $task): bool
    {
        $project = $task->project;

        if (! $project) {
            return false;
        }

        if ((int) $project->created_by === (int) $user->id) {
            return true;
        }

        return RolePermission::roleHasPermission($this->roleForProject($user, $project), RolePermission::PERMISSION_VIEW);
    }

    public function create(User $user, Project $project): bool
    {
        if ((int) $project->created_by === (int) $user->id) {
            return true;
        }

        return RolePermission::roleHasPermission($this->roleForProject($user, $project), RolePermission::PERMISSION_CREATE);
    }

    public function update(User $user, Task $task): bool
    {
        $project = $task->project;

        if (! $project) {
            return false;
        }

        if ((int) $project->created_by === (int) $user->id) {
            return true;
        }

        return RolePermission::roleHasPermission($this->roleForProject($user, $project), RolePermission::PERMISSION_EDIT);
    }

    public function delete(User $user, Task $task): bool
    {
        $project = $task->project;

        if (! $project) {
            return false;
        }

        if ((int) $project->created_by === (int) $user->id) {
            return true;
        }

        return RolePermission::roleHasPermission($this->roleForProject($user, $project), RolePermission::PERMISSION_DELETE);
    }

    public function assign(User $user, Task $task): bool
    {
        $project = $task->project;

        if (! $project) {
            return false;
        }

        if ((int) $project->created_by === (int) $user->id) {
            return true;
        }

        return RolePermission::roleHasPermission($this->roleForProject($user, $project), RolePermission::PERMISSION_ASSIGN);
    }

    public function comment(User $user, Task $task): bool
    {
        $project = $task->project;

        if (! $project) {
            return false;
        }

        if ((int) $project->created_by === (int) $user->id) {
            return true;
        }

        return RolePermission::roleHasPermission($this->roleForProject($user, $project), RolePermission::PERMISSION_COMMENT);
    }

    private function roleForProject(User $user, Project $project): ?string
    {
        if (! $project->team_id) {
            return null;
        }

        return TeamMember::query()
            ->where('team_id', $project->team_id)
            ->where('user_id', $user->id)
            ->value('role');
    }
}
