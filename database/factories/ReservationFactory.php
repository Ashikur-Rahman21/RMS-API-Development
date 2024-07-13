<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Table;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = Carbon::createFromTimeStamp(fake()->dateTimeBetween('-3 days', '+3 days')->getTimestamp());        
        $startTime = Carbon::createFromFormat('Y-m-d H:i:s', $date);
        $endTime = Carbon::createFromFormat('Y-m-d H:i:s', $date)->addHour();
    
        return [
            'user_id' => User::factory(),
            'table_id' => Table::factory(),
            'status' => fake()->randomElement(['open', 'booked', 'hold']),
            'date' => $date->format('Y-m-d'),
            'start_time' => $startTime->format('H:i'),
            'end_time' => $endTime->format('H:i'),
            'num_guest' => fake()->numberBetween(1, 10)
        ];
    }
}
