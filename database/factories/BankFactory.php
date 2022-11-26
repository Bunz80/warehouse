<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bank>
 */
class BankFactory extends Factory
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
            'code' => substr(fake()->name(), 0, 2),
            'iban' => fake()->bankAccountNumber(),
            'is_default' => rand(true, false),
            'is_activated' => rand(true, false),
        ];
    }
}
