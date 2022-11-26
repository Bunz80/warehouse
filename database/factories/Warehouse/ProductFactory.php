<?php

namespace Database\Factories\Warehouse;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Warehouse\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $items = ['pz', 'Kg', 'mt', ''];

        $customerable = $this->customerable();

        return [
            'name' => fake()->name(),
            'description' => fake()->paragraph(),
            'brand' => fake()->name(),
            'category' => fake()->word(),

            'unit' => $items[array_rand($items)],
            'tax' => rand(1, 30),
            'currency' => rand(1, 1000),
            'price' => rand(1, 1000),

            'productable_id' => $customerable::factory(),
            'productable_type' => $customerable,
        ];
    }

    public function customerable()
    {
        return $this->faker->randomElement([
            Customer::class,
        ]);
    }
}
