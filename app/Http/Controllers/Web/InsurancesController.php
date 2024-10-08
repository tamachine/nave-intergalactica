<?php

namespace App\Http\Controllers\Web;

use App\Models\Car;
use App\Models\Feature as InsuranceFeature;

class InsurancesController extends BaseController
{
    public function index(Car $car)
    {
        if (!checkSessionInsurances()) {
            return redirect()->route('cars');
        }

        return view(
            'web.insurances.index', ['car' => $car, 'insurances' => $car->insuranceList(), 'InsuranceFeatures' => InsuranceFeature::all()]
        );
    }

    protected function footerImagePath() : string
    {
        return '/images/footer/insurances.png';
    }
}

