<?php

namespace Database\Seeders;

use App\Models\Warehouse\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::factory()->count(220)->create();
    }
}
