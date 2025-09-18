<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\Column;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'column_id' => Column::factory(),
            'parent_id' => null,
            'created_by' => User::factory(),
            'assigned_by' => User::factory(),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->optional()->paragraph(),
            'order' => $this->faker->numberBetween(1, 20),
            'is_completed' => $this->faker->boolean(20)
        ];
    }
}
