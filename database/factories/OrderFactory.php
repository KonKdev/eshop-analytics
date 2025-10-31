<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            // WooCommerce order ID
            'order_id' => $this->faker->randomNumber(5, true),

            // Order belongs to some store
            'store_id' => 1,

            'status' => $this->faker->randomElement(['pending', 'processing', 'completed', 'cancelled']),
            'total' => $this->faker->randomFloat(2, 10, 500),
            'currency' => 'EUR',
            'customer_name' => $this->faker->name(),
            'order_date' => now(),
        ];
    }
}
