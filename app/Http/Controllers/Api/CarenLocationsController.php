<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Models\CarenLocation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CarenLocationsController extends BaseController
{

     /**
     * @lrd:start
     * ## Returns all locations ordered by appearance.
     * @QAparam locale string nullable
     * @lrd:end           
     */
    public function index(Request $request):JsonResponse {       
        $this->checkLocale($request);  
        return $this->successResponse($this->mapApiResponse(CarenLocation::with('location')->get()));
    }


}
