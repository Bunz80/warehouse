<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
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
            'logo' => fake()->imageUrl(),
            'vat' => fake()->randomDigit(),
            'fiscal_code' => fake()->randomDigit(),
            'invoice_code' => strtoupper(substr(fake()->word(), 0, 6)),
            'is_activated' => rand(true, false),
        ];
    }
}
