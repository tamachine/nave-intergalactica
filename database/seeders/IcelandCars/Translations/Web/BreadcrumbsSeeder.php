<?php

namespace Database\Seeders\IcelandCars\Translations\Web;

use Illuminate\Database\Seeder;
use App\Models\Translation;

class BreadcrumbsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {                
        Translation::firstOrCreate(
            [
                'group' => 'breadcrumbs',
                'key' => 'home',    
            ],
            [                
                'text' => ['en' => 'Home', 'es' => 'Home'],
            ]
        );   

        Translation::firstOrCreate(
            [
                'group' => 'breadcrumbs',
                'key' => 'blog',    
            ],
            [                
                'text' => ['en' => 'Blog', 'es' => 'Blog'],
            ]
        );   
        
    }
}
