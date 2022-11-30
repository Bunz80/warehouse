<?php

namespace Database\Seeders;

use App\Models\Warehouse\OrderDetail;
use Illuminate\Database\Seeder;

class OrderDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrderDetail::factory()->count(420)->create();
    }
}
