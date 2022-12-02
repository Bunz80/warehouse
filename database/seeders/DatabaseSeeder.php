<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // user login
            UserSeeder::class,
            // registry
            CategorySeeder::class,
            CompanySeeder::class,
            SupplierSeeder::class,
            CustomerSeeder::class,
            // Contacts & Addresses
            AddressSeeder::class,
            ContactSeeder::class,
            BankSeeder::class,
            // warehouse
            ProductSeeder::class,
            OrderSeeder::class,
            OrderDetailSeeder::class,
        ]);
    }
}
