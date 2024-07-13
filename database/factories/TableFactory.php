<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Table>
 */
class TableFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'table_name' => fake()->randomElement(['Rose', 'Tulip', 'Lily', 'Daisy', 'Sunflower']),
            'table_number' => fake()->numberBetween(1, 5),
            'seats' => fake()->numberBetween(2, 8),
            'status' =>fake()->randomElement(['available', 'reserved', 'occupied'])
        ];
    }
}
