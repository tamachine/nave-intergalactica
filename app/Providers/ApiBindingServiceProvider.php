<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Exceptions\Api\NotFoundException as ApiNotFoundException;

class ApiBindingServiceProvider extends ServiceProvider
{
    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->bindings();
    }

     /**
     * Define the model bindings
     *
     * @return void
     */
    public function bindings() {       

        Route::bind('api_blog_post_slug', function ($value) {
            $resource = \App\Models\BlogPost::where('slug', 'LIKE', '%' . $value . '%')->first();
            return $this->processResponse($resource);            
        });

        Route::bind('api_blog_author_slug', function ($value) {
            $resource = \App\Models\BlogAuthor::where('slug', 'LIKE', '%' . $value . '%')->first();
            return $this->processResponse($resource);            
        });

        Route::bind('api_blog_category_slug', function ($value) {
            $resource = \App\Models\BlogCategory::where('slug', 'LIKE', '%' . $value . '%')->first();
            return $this->processResponse($resource);            
        });

        Route::bind('api_blog_tag_slug', function ($value) {
            $resource = \App\Models\BlogTag::where('slug', 'LIKE', '%' . $value . '%')->first();
            return $this->processResponse($resource);            
        });

        Route::bind('api_page_name', function ($value) {
            $resource = \App\Models\Page::where('route_name', $value)->first();            
            return $this->processResponse($resource);  
        }); 

        Route::bind('api_booking_hashid', function ($value) {
            $resource = \App\Models\Booking::where('hashid', $value)->first();
            return $this->processResponse($resource); 
        });

        Route::bind('api_extra_hashid', function ($value) {
            $resource = \App\Models\Extra::where('hashid', $value)->first();
            return $this->processResponse($resource); 
        });

        Route::bind('api_car_hashid', function ($value) {
            $resource = \App\Models\Car::where('hashid', $value)->first();
            return $this->processResponse($resource); 
        });

        Route::bind('api_faq_hashid', function ($value) {
            $resource = \App\Models\Faq::where('hashid', $value)->first();
            return $this->processResponse($resource); 
        });

        Route::bind('api_translation_full_key', function ($value) {
            $resource = new \App\Models\Translation();

            $params = explode(".", $value, 2);

            if (count($params) == 2) {
                $resource = $resource->where('group', $params[0])->where('key', $params[1])->first();
            }

            return $this->processResponse($resource); 
        });

    }

    protected function processResponse($resource) {
        if($resource) {
            return $resource;
        } else {           
            throw new ApiNotFoundException();                     
        }
    }
}
