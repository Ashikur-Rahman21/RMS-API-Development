<?php

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'customer_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignIdFor(Order::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->integer('order_number');
            $table->decimal('total_amount');
            $table->enum('payment_method', ['Cash', 'Card', 'Digital Wallet', 'Mobile Banking']);
            $table->enum('payment_status', ['Pending', 'Paid']);
            $table->foreignIdFor(User::class, 'created_by')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
