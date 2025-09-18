<?php

namespace Tests\Feature\Api;

use App\Models\Task;
use App\Models\Column;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_tasks(): void
    {
        $column = Column::factory()->create();
        Task::factory()->count(3)->create(['column_id' => $column->id]);

        $response = $this->getJson("/api/columns/{$column->id}/tasks");

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_store_task(): void
    {
        $column = Column::factory()->create();
        $user = User::factory()->create();

        $data = [
            'title' => 'New Task',
            'created_by' => $user->id,
            'assigned_by' => $user->id,
        ];

        $response = $this->postJson("/api/columns/{$column->id}/tasks", $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['title' => 'New Task']);
    }

    public function test_show_task(): void
    {
        $task = Task::factory()->create();

        $response = $this->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $task->id]);
    }

    public function test_update_task(): void
    {
        $task = Task::factory()->create();

        $response = $this->putJson("/api/tasks/{$task->id}", [
            'title' => 'Updated Task',
            'is_completed' => true,
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Updated Task']);
    }

    public function test_destroy_task(): void
    {
        $task = Task::factory()->create();

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
