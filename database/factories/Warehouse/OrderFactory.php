<?php

namespace Database\Factories\Warehouse;

use App\Models\Category;
use App\Models\Company;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Warehouse\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [

            'year' => '2022',
            'number' => fake()->randomDigit(),
            'order_at' => fake()->date(),
            'status' => fake()->randomElement(['New', 'Close', 'Open', 'Draft']),
            'discount_currency' => fake()->randomElement(['%', '€']),
            'discount_price' => rand(1, 50),
            'currency' => fake()->randomElement(['€', '$', '£', '¥']),
            'total_price' => rand(100, 1000),

            'delivery_method' => fake()->word(),
            'delivery_note' => fake()->paragraph(),

            'trasport_method' => Category::where('collection_name', 'Warehouse-Trasport')->pluck('name', 'id'),
            'trasport_note' => fake()->paragraph(),

            'payment_method' => Category::where('collection_name', 'Warehouse-Payment')->pluck('name', 'id'),
            'payment_note' => fake()->paragraph(),

            'notes' => fake()->paragraph(),

            'company_id' => Company::inRandomOrder()->first()->id,
            'supplier_id' => Supplier::inRandomOrder()->first()->id,
        ];
    }
}
