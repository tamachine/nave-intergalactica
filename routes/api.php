<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::apiResource('translation-groups', TranslationGroupsController::class)->only('index');   
Route::apiResource('translations', TranslationsController::class, ['parameters' => ['translations' => 'api_translation_full_key']])->only(['index', 'show']);     

Route::apiResource('faq-categories', FaqCategoriesController::class)->only('index');     
Route::apiResource('faqs', FaqsController::class, ['parameters' => ['faqs' => 'api_faq_hashid']])->only(['index', 'show']);     

Route::apiResource('car-filters', CarFiltersController::class, ['parameters' => ['car-filters' => 'car_filter_id']])->only(['index', 'show']);     
Route::get('car-search', 'CarsController@search'); 
Route::get('cars/{api_car_hashid}/seoconfigurations/{api_page_name}', 'CarsController@seoConfigurations'); 
Route::get('cars/{api_car_hashid}/insurances', 'CarsController@insurances'); 
Route::get('cars/{api_car_hashid}/extras', 'CarsController@extras'); 
Route::get('cars/{api_car_hashid}/prices', 'CarsController@prices'); 
Route::put('cars/{api_car_hashid}/booking/create', 'CarBookingController@create'); 

Route::get('valitor/requestParams/{api_booking_hashid}', 'ValitorController@requestParams'); 
Route::get('valitor/checkResponse/{api_booking_hashid}', 'ValitorController@checkResponse'); 

Route::apiResource('bookings', BookingsController::class, ['parameters' => ['bookings' => 'api_booking_hashid']])->only(['update', 'show']);
Route::put('bookings/pdf', 'BookingsController@pdf'); 

Route::apiResource('extras', ExtrasController::class, ['parameters' => ['extras' => 'api_extra_hashid']])->only(['index', 'show']);
Route::apiResource('insurances', InsurancesController::class)->only(['index']);

Route::apiResource('insurance-features', InsuranceFeaturesController::class)->only('index');

Route::apiResource('affiliates', AffiliatesController::Class)->only('index');

Route::apiResource('locations', LocationsController::class)->only('index');
Route::apiResource('caren-locations', CarenLocationsController::class)->only('index');

Route::apiResource('config', ConfigController::class)->only('index');

Route::apiResource('currency-rates', CurrencyRatesController::class)->only('index');

Route::get('page-classes', 'PagesController@classes'); 
Route::get('pages/{api_page_name}/seoconfigurations', 'PagesController@seoConfigurations'); 
Route::apiResource('pages', PagesController::class, ['parameters' => ['pages' => 'api_page_name']])->only(['index', 'show']);

Route::get('post-authors/{api_blog_author_slug}/seoconfigurations/{api_page_name}', 'BlogAuthorsController@seoConfigurations'); 
Route::get('post-categories/{api_blog_category_slug}/seoconfigurations/{api_page_name}', 'BlogCategoriesController@seoConfigurations'); 
Route::get('posts/{api_blog_post_slug}/seoconfigurations/{api_page_name}', 'BlogPostsController@seoConfigurations'); 
Route::apiResource('post-authors', BlogAuthorsController::class, ['parameters' => ['post-authors' => 'api_blog_author_slug']])->only(['index','show']);    
Route::apiResource('post-categories', BlogCategoriesController::class, ['parameters' => ['post-categories' => 'api_blog_category_slug']])->only(['index', 'show']);    
Route::apiResource('post-tags', BlogTagsController::class, ['parameters' => ['post-tags' => 'api_blog_tag_slug']])->only(['index', 'show']);    
Route::apiResource('posts', BlogPostsController::class, ['parameters' => ['posts' => 'api_blog_post_slug']])->only(['index', 'show']);    
Route::get('post-preview/{api_blog_post_slug}/token/{token}/verify', 'BlogPostsPreviewController@verify'); 

Route::put('/newsletter-user/submitted', 'NewsletterUserController@submitted');

Route::put('/contact-form/submitted', 'ContactFormController@submitted');
Route::get('/contact-form/types', 'ContactFormController@types');





