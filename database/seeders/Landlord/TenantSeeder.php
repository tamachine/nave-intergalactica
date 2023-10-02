<?php

namespace Database\Seeders\Landlord;

use Illuminate\Database\Seeder;
use App\Models\Landlord\Tenant;

class TenantSeeder extends Seeder
{
     /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tenant::create([
            'name' => 'reykjavikauto',
            'long_name' => 'Reykjavik Auto',
            'domain' => 'reykjavikauto.campervanplanet.com',
            'database' => 'tenant_reykjavikauto'
        ]);

        Tenant::create([
            'name' => 'carsiceland',
            'long_name' => 'Cars Iceland',
            'domain' => 'carsiceland.campervanplanet.com',
            'database' => 'tenant_carsiceland'
        ]);
    }
}
