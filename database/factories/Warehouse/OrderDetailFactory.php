<?php

namespace Database\Factories\Warehouse;

use App\Models\Warehouse\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Warehouse\OrderDetail>
 */
class OrderDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [

            'name' => fake()->name(),
            'description' => fake()->paragraph(),
            'brand' => fake()->name(),
            'code' => fake()->word(),

            'currency' => fake()->randomElement(['€', '$', '£', '¥']),
            'tax' => rand(5, 30),
            'unit' => fake()->randomElement(['pz', 'Kg', 'Mt', 'Lt']),
            'quantity' => rand(1, 20),
            'price_unit' => rand(1, 1000),
            'total_price' => rand(1, 1000),

            'discount_currency' => fake()->randomElement(['%', '€', '$', '£', '¥']),
            'discount_price' => rand(1, 1000),

            'order_id' => Order::inRandomOrder()->first()->id,
        ];
    }
}
