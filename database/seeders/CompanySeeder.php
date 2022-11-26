<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::factory()->create([
            'name' => 'Medmar Navi',
            'logo' => 'https://www.medmargroup.it/catalog/view/theme/default/image/BASE-logo-v.png',
            'vat' => '05984260637',
            'fiscal_code' => '05984260637',
            'invoice_code' => 'WY7PJ6K',
            'default_tax_rate' => '22',
            'is_activated' => true,
        ]);

        Company::factory()->create([
            'name' => 'Rifim',
            'invoice_code' => 'WY7PJ6K',
            'default_tax_rate' => '22',
            'is_activated' => true,
        ]);

        Company::factory()->create([
            'name' => 'Mediterranea Marittima',
            'invoice_code' => 'WY7PJ6K',
            'default_tax_rate' => '22',
            'is_activated' => true,
        ]);
    }
}
