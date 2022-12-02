<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Customer;
use App\Models\Supplier;
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
        $type = fake()->randomElement(['App\Models\Company', 'App\Models\Supplier', 'App\Models\Customer']);
        
        if ($type == 'App\Models\Company') {
            $type_id = Company::inRandomOrder()->first()->id;
        }

        if ($type == 'App\Models\Supplier') {
            $type_id = Supplier::inRandomOrder()->first()->id;
        }

        if ($type == 'App\Models\Customer') {
            $type_id = Customer::inRandomOrder()->first()->id;
        }

        return [
            'bankable_type' => $type,
            'bankable_id' => $type_id,
            'name' => fake()->name(),
            'code' => fake()->swiftBicNumber(),
            'iban' => fake()->bankAccountNumber(),
            'is_default' => rand(true, false),
            'is_activated' => rand(true, false),
        ];
    }
}
