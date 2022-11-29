<?php

namespace Tests\Unit\Caren;

use Tests\TestCase;
use App\Apis\Caren\Api;

class FullCarListTest extends TestCase
{
    /**
     * @test
     * @group unit
     * @group api
     * @group api-caren
     * @group api-caren-cars
     * @group external
     *
     * @return void
     */
    public function fullCarListTest()
    {
        $api = new Api;
        $carList = $api->fullCarList(["RentalId" => 6]);

        $this->assertEquals(25, count($carList['Classes']));
    }
}
