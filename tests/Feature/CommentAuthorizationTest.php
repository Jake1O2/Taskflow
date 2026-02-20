<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_comment_on_a_task_they_do_not_own(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();

        $project = Project::create([
            'created_by' => $owner->id,
            'title' => 'Owner project',
            'description' => 'Project description',
            'status' => 'preparation',
        ]);

        $task = $project->tasks()->create([
            'title' => 'Owner task',
            'description' => 'Task description',
            'status' => 'todo',
        ]);

        $response = $this->actingAs($intruder)->post(route('comments.store', ['id' => $task->id]), [
            'content' => 'I should not be able to post this.',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseCount('comments', 0);
    }

    public function test_owner_can_comment_on_their_task(): void
    {
        $owner = User::factory()->create();

        $project = Project::create([
            'created_by' => $owner->id,
            'title' => 'Owner project',
            'description' => 'Project description',
            'status' => 'preparation',
        ]);

        $task = $project->tasks()->create([
            'title' => 'Owner task',
            'description' => 'Task description',
            'status' => 'todo',
        ]);

        $response = $this->actingAs($owner)->post(route('comments.store', ['id' => $task->id]), [
            'content' => 'This comment is allowed.',
        ]);

        $response->assertRedirect(route('tasks.show', ['id' => $task->id]));
        $this->assertDatabaseHas('comments', [
            'task_id' => $task->id,
            'user_id' => $owner->id,
            'content' => 'This comment is allowed.',
        ]);
    }
}
