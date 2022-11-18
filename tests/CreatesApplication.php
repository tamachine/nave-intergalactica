<?php

namespace Tests;

use App\Models\Car;
use App\Models\Location;
use App\Models\Season;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * Create a car
     *
     * @param      array   $attributes
     *
     * @return     object  \App\Models\Car
     */
    protected function createCar($attributes = [])
    {
        return Car::factory($attributes)->create();
    }

    /**
     * Create a location
     *
     * @param      array   $attributes
     *
     * @return     object  \App\Models\Location
     */
    protected function createLocation($attributes = [])
    {
        return Location::factory($attributes)->create();
    }

     /**
     * Create a season
     *
     * @param      array   $attributes
     *
     * @return     object  \App\Models\Season
     */
    protected function createSeason($attributes = [])
    {
        return Season::factory($attributes)->create();
    }

    /**
     * Create a user
     *
     * @param      array   $attributes
     *
     * @return     object  \App\Models\User
     */
    protected function createUser($attributes = [])
    {
        return User::factory($attributes)->create();
    }

    /**
     * Create a vendor
     *
     * @param      array   $attributes
     *
     * @return     object  \App\Models\Vendor
     */
    protected function createVendor($attributes = [])
    {
        return Vendor::factory($attributes)->create();
    }
}
