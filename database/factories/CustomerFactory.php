<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
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
            'lastname' => fake()->lastname(),
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'date_birth' => fake()->date(),
            'logo' => fake()->imageUrl(),
            'vat' => fake()->randomDigit(),
            'fiscal_code' => fake()->randomDigit(),
            'invoice_code' => strtoupper(substr(fake()->word(), 0, 6)),
            'is_person' => rand(true, false),
            'is_activated' => rand(true, false),
        ];
    }
}
