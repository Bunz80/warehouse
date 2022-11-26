<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Category::factory()->count(1)->create();

        $csvFile = fopen(base_path('database/data/category.csv'), 'r');

        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ',')) !== false) {
            if (! $firstline) {
                Category::create([
                    'collection_name' => $data['0'],
                    'name' => $data['1'],
                    'icon' => $data['2'],
                    'is_default' => true,
                    'is_activated' => rand(true, false),
                ]);
            }
            $firstline = false;
        }

        fclose($csvFile);
    }
}
