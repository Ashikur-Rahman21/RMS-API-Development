<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $customers = User::whereUserType('customer')->pluck('id')->toArray();
        $employee = User::whereIn('user_type', ['admin', 'employee'])->pluck('id')->toArray();

        return [
            'order_number' => fake()->unique()->numberBetween(1, 100),
            'reservation_id' => Reservation::factory(),
            'customer_id' => fake()->randomElement($customers),
            'status' => fake()->randomElement(['Pending', 'In Preparation', 'Ready', 'Delivered']),
            'total' => fake()->numberBetween(100, 500),
            'paid_amount' => fake()->numberBetween(100, 500),
            'payment_method' => fake()->randomElement(['Cash', 'Card', 'Digital Wallet', 'Mobile Banking']),
            'created_by' => fake()->randomElement($employee),
        ];
    }
}
