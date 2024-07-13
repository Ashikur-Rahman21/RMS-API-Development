<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
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
            'customer_id' => fake()->randomElement($customers),
            'order_id' => Order::factory(),
            'order_number' => $this->faker->numberBetween(1, 100),
            'total_amount' => $this->faker->numberBetween(100, 1000),
            'payment_method' => $this->faker->randomElement(['Cash', 'Card', 'Digital Wallet', 'Mobile Banking']),
            'payment_status' => $this->faker->randomElement(['Pending', 'Paid']),
            'created_by' => fake()->randomElement($employee),
        ];
    }
}
