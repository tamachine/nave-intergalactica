<?php

namespace Database\Seeders\Translations\Web;

use Illuminate\Database\Seeder;
use App\Models\Translation;

class AppErrorsSeeder extends Seeder
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
                'group' => 'app-errors',
                'key' => 'not-found-title',
            ],
            [
                'text' => [
                    'en' => 'While this page no longer exists, we wanted to leave you with this funny picture.',
                    'es' => 'Aunque esta página ya no existe, queríamos dejaros con esta divertida imagen.'
                ],
            ]
        );

        Translation::firstOrCreate(
            [
                'group' => 'app-errors',
                'key' => 'not-found-button',
            ],
            [
                'text' => ['en' => 'Go to homepage', 'es' => 'Ir a la página de inicio'],
            ]
        );

        Translation::firstOrCreate(
            [
                'group' => 'app-errors',
                'key' => 'error-500-title',
            ],
            [
                'text' => [
                    'en' => "Our trolls are in trouble, we hope they'll be back to sleep soon.",
                    'es' => 'Nuestros trolls tienen problemas, esperamos que vuelvan a dormir en breve.'
                ],
            ]
        );

        Translation::firstOrCreate(
            [
                'group' => 'app-errors',
                'key' => 'error-500-button',
            ],
            [
                'text' => ['en' => 'Go to homepage', 'es' => 'Ir a la página de inicio'],
            ]
        );
    }
}
