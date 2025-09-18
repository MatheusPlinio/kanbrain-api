<?php

namespace Tests\Feature\Api;

use App\Models\Column;
use App\Models\Board;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ColumnControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_columns(): void
    {
        $board = Board::factory()->create();
        Column::factory()->count(3)->create(['board_id' => $board->id]);

        $response = $this->getJson("/api/boards/{$board->id}/columns");

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_store_column(): void
    {
        $board = Board::factory()->create();
        $data = [
            'title' => 'To Do',
            'order' => 1,
        ];

        $response = $this->postJson("/api/boards/{$board->id}/columns", $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['title' => 'To Do']);
    }

    public function test_show_column(): void
    {
        $column = Column::factory()->create();

        $response = $this->getJson("/api/columns/{$column->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $column->id]);
    }

    public function test_update_column(): void
    {
        $column = Column::factory()->create();

        $response = $this->putJson("/api/columns/{$column->id}", [
            'title' => 'In Progress',
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'In Progress']);
    }

    public function test_destroy_column(): void
    {
        $column = Column::factory()->create();

        $response = $this->deleteJson("/api/columns/{$column->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('columns', ['id' => $column->id]);
    }
}
