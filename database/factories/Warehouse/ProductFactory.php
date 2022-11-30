<?php

namespace Database\Factories\Warehouse;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Warehouse\Product>
 */
class ProductFactory extends Factory
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
            'category' => fake()->words(5),

            'code' => fake()->word(),
            'unit' => fake()->randomElement(['pz', 'Kg', 'Mt', 'Lt']),
            'tax' => rand(1, 30),
            'currency' => fake()->randomElement(['€', '$', '£', '¥']),
            'price' => rand(1, 1000),

            'supplier_id' => Supplier::inRandomOrder()->first()->id,
        ];
    }
}
