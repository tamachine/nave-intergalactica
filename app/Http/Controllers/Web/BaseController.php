<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use View; 
use App\Services\ReviewsInfo\GoogleReviewsInfoComponent;
use App\Services\ReviewsInfo\FacebookReviewsInfoComponent;
use App\Services\ReviewsInfo\TrustPilotReviewsInfoComponent;
use App\Services\Selectable\CarSearchHoursSelectableComponent;

abstract class BaseController extends Controller
{    
    public function __construct() 
    {        
        $this->commonViewParams();
    }

    protected function commonViewParams() {
        View::share('googleReviewInfoComponent', new GoogleReviewsInfoComponent());
        View::share('facebookReviewInfoComponent', new FacebookReviewsInfoComponent());
        View::share('trustpilotReviewInfoComponent', new TrustPilotReviewsInfoComponent());
        View::share('carSearchHoursSelectableComponent', new CarSearchHoursSelectableComponent());

        View::share('footerImagePath', $this->footerImagePath());
    }

    abstract protected function footerImagePath() : string;
}
