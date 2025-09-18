<?php

namespace Tests\Feature\Api;

use App\Exceptions\RepositoryException;
use App\Models\Project;
use App\Models\User;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_returns_401_when_user_not_authenticated(): void
    {
        $project = Project::factory()->create();

        $response = $this->getJson("/api/projects/{$project->id}");

        $response->assertStatus(401);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_returns_404_when_project_not_found(): void
    {
        $nonExistentId = 999;

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson("/api/projects/{$nonExistentId}");

        $response->assertStatus(404)
            ->assertJsonFragment(['error' => 'Resource not found']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_returns_422_when_creating_project_with_invalid_data(): void
    {
        $data = ['name' => ''];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/projects', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('name');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_returns_500_if_repository_fails_on_store(): void
    {
        $mock = $this->mock(ProjectRepositoryInterface::class, function ($mock) {
            $mock->shouldReceive('store')
                ->andThrow(new RepositoryException('DB failure', 500));
        });

        $this->app->instance(ProjectRepositoryInterface::class, $mock);

        $data = ['name' => 'Test Project', 'description' => 'Desc'];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/projects', $data);

        $response->assertStatus(500)
            ->assertJsonFragment(['error' => 'Repository Error']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_returns_500_if_repository_fails_on_update(): void
    {
        $project = Project::factory()->create(['user_id' => $this->user->id]);

        $mock = $this->mock(ProjectRepositoryInterface::class, function ($mock) {
            $mock->shouldReceive('update')
                ->once()
                ->andThrow(new RepositoryException('Repository Error'));
        });

        $this->app->instance(ProjectRepositoryInterface::class, $mock);

        $response = $this->actingAs($this->user, 'sanctum')
            ->putJson("/api/projects/{$project->id}", [
                'name' => 'Updated Project',
                'description' => 'New description',
            ]);

        $response->assertStatus(500)
            ->assertJsonFragment(['error' => 'Repository Error']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_returns_500_if_repository_fails_on_destroy(): void
    {
        $project = Project::factory()->create(['user_id' => $this->user->id]);

        $mock = $this->mock(ProjectRepositoryInterface::class, function ($mock) {
            $mock->shouldReceive('destroy')
                ->once()
                ->andThrow(new RepositoryException('Repository Error'));
        });

        $this->app->instance(ProjectRepositoryInterface::class, $mock);

        $response = $this->actingAs($this->user, 'sanctum')
            ->deleteJson("/api/projects/{$project->id}");

        $response->assertStatus(500)
            ->assertJsonFragment(['error' => 'Repository Error']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_lists_all_projects_for_authenticated_user(): void
    {
        Project::factory()->create(['user_id' => $this->user->id, 'name' => 'Project A']);
        Project::factory()->create(['user_id' => $this->user->id, 'name' => 'Project B']);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/projects');

        $response->assertOk()
            ->assertJsonFragment(['name' => 'Project A'])
            ->assertJsonFragment(['name' => 'Project B']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_creates_a_project(): void
    {
        $data = [
            'name' => 'My New Project',
            'description' => 'Test description',
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/projects', $data);

        $response->assertCreated()
            ->assertJsonFragment(['name' => 'My New Project']);

        $this->assertDatabaseHas('projects', [
            'name' => 'My New Project',
            'user_id' => $this->user->id,
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_shows_a_single_project(): void
    {
        $project = Project::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson("/api/projects/{$project->id}");

        $response->assertOk()
            ->assertJsonFragment(['id' => $project->id]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_updates_a_project(): void
    {
        $project = Project::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Old Project',
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->putJson("/api/projects/{$project->id}", [
                'name' => 'Updated Project',
                'description' => 'New description',
            ]);

        $response->assertOk()
            ->assertJsonFragment(['name' => 'Updated Project']);

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'name' => 'Updated Project',
            'description' => 'New description',
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_deletes_a_project(): void
    {
        $project = Project::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->deleteJson("/api/projects/{$project->id}");

        $response->assertNoContent();

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }
}
