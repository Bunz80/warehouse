<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Customer;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
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
            'addressable_type' => $type,
            'addressable_id' => $type_id,
            'collection_name' => 'Warehouse-Address',
            'name' => fake()->streetName(),
            'address' => fake()->streetAddress(),
            'street_number' => fake()->randomNumber(),
            'zip' => fake()->postcode(),
            'city' => fake()->city(),
            'province' => fake()->cityPrefix(),
            'state' => fake()->state(),
        ];
    }
}
