<?php

namespace Database\Seeders;

use App\Models\Warehouse\Order;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Order::factory()->count(120)->create();
    }
}
