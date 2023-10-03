<?php 

namespace App\Services\CarPrices\Caren;

use App\Models\Extra;
use App\Models\Insurance;
use App\Models\Location;
use DateTime;

class CarPricesParams {
    public $car;
    public string $dateFrom;
    public string $dateTo;
    public int $carenPickupLocationId; 
    public int $carenDropoffLocationId;
    public $extras;
    public $insurances;

    public function setDateFrom($value) {
        $date = new DateTime($value);

        $this->dateFrom = $date->format('Y-m-d H:i:s');
    }

    public function setDateTo($value) {
        $date = new DateTime($value);

        $this->dateTo = $date->format('Y-m-d H:i:s');
    }

    public function setPickupLocation($hashid) {
        $this->carenPickupLocationId = Location::findByHashid($hashid)->carenLocation->caren_pickup_location_id;
    }

    public function setDropoffLocation($hashid) {
        $this->carenDropoffLocationId = Location::findByHashid($hashid)->carenLocation->caren_dropoff_location_id;
    }

    public function setExtras(array $value) {
        $extras = [];

        foreach($value as $hashid => $quantity) {            
            $carenId = optional(Extra::caren()->findByHashid($hashid))->caren_id ?? null; //add only caren extras
            if($carenId) $extras[] = [$carenId , $quantity];
        }

        $this->extras = $extras;
    }

    public function setInsurances(array $value) {
        $insurances = [];

        foreach($value as $hashid) {
            $carenId = Insurance::findByHashid($hashid)->caren_id;
            $insurances[] =  $carenId;
        }

        $this->insurances = $insurances;
    }
}