<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Inventory;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\Table;
use App\Models\User;
use App\Models\WaitingList;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PermissionsTableSeeder::class);
        $this->call(AdminTableSeeder::class);
        $users = User::factory(100)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        $tables = Table::factory(10)->create();
        $categories = Category::factory(10)->create();

        $reservation = Reservation::factory(100)->recycle($users)->recycle($tables)->create();
        MenuItem::factory(20)->recycle($categories)->create();
        Inventory::factory(10)->recycle($users)->recycle($categories)->create();
        $orders = Order::factory(10)->recycle($reservation)->create();
        OrderItem::factory(100)->recycle($orders)->create();
        Payment::factory(100)->recycle($orders)->create();
        WaitingList::factory(20)->recycle($users)->create();
    }
}
