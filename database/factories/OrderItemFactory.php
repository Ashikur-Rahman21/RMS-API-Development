<?php

namespace Database\Factories;

use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderItem>
 */
class OrderItemFactory extends Factory
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
            'order_id' => Order::factory(),
            'menu_item_id' => MenuItem::factory(),
            'quantity' => $this->faker->numberBetween(1, 10),
            'unit_price' => $this->faker->numberBetween(100, 500),
            'sub_total' => $this->faker->numberBetween(100, 500),
            'customer_id' => $this->faker->randomElement($customers),
            'created_by' => $this->faker->randomElement($employee),
            'order_number' => $this->faker->numberBetween(1, 100),
        ];
    }
}
