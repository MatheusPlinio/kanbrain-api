<?php

namespace Tests\Feature\Api;

use App\Models\Board;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BoardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_boards(): void
    {
        $project = Project::factory()->create();
        Board::factory()->count(3)->create(['project_id' => $project->id]);

        $response = $this->getJson("/api/projects/{$project->id}/boards");

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_store_board(): void
    {
        $project = Project::factory()->create();
        $data = [
            'title' => 'New Board',
        ];

        $response = $this->postJson("/api/projects/{$project->id}/boards", $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['title' => 'New Board']);
    }

    public function test_show_board(): void
    {
        $board = Board::factory()->create();

        $response = $this->getJson("/api/boards/{$board->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $board->id]);
    }

    public function test_update_board(): void
    {
        $board = Board::factory()->create();

        $response = $this->putJson("/api/boards/{$board->id}", [
            'title' => 'Updated Board',
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Updated Board']);
    }

    public function test_destroy_board(): void
    {
        $board = Board::factory()->create();

        $response = $this->deleteJson("/api/boards/{$board->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('boards', ['id' => $board->id]);
    }
}
