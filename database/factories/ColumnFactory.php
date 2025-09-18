<?php

namespace Database\Factories;

use App\Models\Column;
use App\Models\Board;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Column>
 */
class ColumnFactory extends Factory
{
    protected $model = Column::class;

    public function definition(): array
    {
        return [
            'board_id' => Board::factory(),
            'title' => $this->faker->word(),
            'order' => $this->faker->numberBetween(1, 10),
        ];
    }
}
