<?php

namespace App\Traits;

/**
 * This trait, generates a toApiResponse() method similar to toArray() method with all the attributes, appends or methods included in $apiResponse array:
 * 
 * use App\Traits\HasApiResponse;
 * 
 * class Translation extends LanguageLine
 * {
 * 
 *  use HasApiResponse;
 * 
 *  protected $apiResponse = ['hashid', 'full_key', 'text']; //attributes, appends or methods
 * 
 *  ...
 */
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Helpers\Api;
use App;
use Illuminate\Database\Eloquent\Collection;

trait HasApiResponse
{
    public function toApiResponse($locale = null): array {
        
        if (!is_subclass_of($this, 'Illuminate\Database\Eloquent\Model')) return $this->toApiResponseNotModel();        
        
        $apiResponse = $this->getParams($locale);
        
        $this->handleLocale($apiResponse,  $locale);

        return $apiResponse;
    }    

    public function setApiResponse($params) {
        $this->apiResponse = $params;
    }

    public function getApiResponse(): array {
        return $this->apiResponse;
    }

    /**
     * same as toApiResponse but for classes that don't extend model class
     */
    public function toApiResponseNotModel() {
        $apiResponse[] = array_column(Api::getPublicPropertiesOfClass($this), 'name'); 
        
        if(isset($this->apiResponse)) {
            $apiResponse = [];

            foreach($this->apiResponse as $param) {
                if (isset($this->$param)) { //its an attribute
                    $apiResponse[$param] = $this->jsonResponse($this->$param);
                }                 
                elseif (method_exists($this, $param)) {  //its a method
                    $apiResponse[$param] = $this->jsonResponse($this->$param());               
                }                
            }            
        }        

        return $apiResponse;
    }

    /**
     * handles the apiResponse params defined in the model. 
     * @return array params defined in $apiResponse or all of them
     */
    protected function getParams($locale = null):array {
        $apiResponse = $this->attributesToArray();

        if(isset($this->apiResponse)){
            $apiResponse = [];

            foreach($this->apiResponse as $param) {
                if($this?->featured_image_attribute == $param) {
                    $apiResponse[$param] = $this->jsonResponse($this->getFeaturedImageModelImageInstance());
                } elseif (array_key_exists($param, $this->attributes)) { //its an attribute                                                        
                    $apiResponse[$param] = $this->jsonResponse($this->attributes[$param], $locale);                                    
                } elseif (is_array($this->append) && in_array($param, $this->append)) { //its an append attribute                    
                    $apiResponse[$param] = $this->jsonResponse($this->$param, $locale);                    
                } elseif (method_exists($this, $param)) {  
                    if($this->$param() instanceof HasMany || $this->$param() instanceof MorphMany || $this->$param() instanceof BelongsToMany) { 
                        $apiResponse[$param] = $this->jsonResponse($this->getHasMany($this->$param, $locale));   
                    } elseif($this->$param() instanceof BelongsTo) { //its a MorphMany relation
                        $apiResponse[$param] = $this->jsonResponse($this->getBelongsTo($param, $locale));  
                    } else { //its a method
                        $apiResponse[$param] = $this->jsonResponse($this->getMethod($param, $locale));
                    }                  
                } else { //for example when an attribute is defined with a method but not added in the append array (getFeaturedImagePathAttribute for instance)
                    try{                        
                        $apiResponse[$param] = $this->jsonResponse($this->$param, $locale);  
                    } catch(\Exception $e) {

                    }
                    
                }              
            }            
        }  

        return $apiResponse;
    }

    /**
     * handles the locale param
     * @param array &$apiResponse
     * @param string $locale
     */
    protected function handleLocale(&$apiResponse, $locale): array {
        if(isset($this->translatable)) {        
            foreach($apiResponse as $param => $value) {
                if (isset($this->attributes[$param]) && in_array($param, $this->translatable)) { //its an attribute and its translatable   
                    if(is_subclass_of($this, 'Spatie\TranslationLoader\LanguageLine')) {
                        $apiResponse[$param] = $this->jsonResponse($this->getTranslation($locale ?? App::getLocale())); //LangualeLine handles the translations differently
                    } else {
                        $apiResponse[$param] = $this->jsonResponse($this->getTranslation($param, $locale ?? App::getLocale()));
                    }                                               
                }
            }
        }

        return $apiResponse;
    }

    protected function jsonResponse($value, $locale = null) {         
        if ($value instanceof Collection) {
            return $this->getHasMany($value, $locale);
        }

        if (is_object($value)) {
            if (method_exists($value, 'toApiResponse')) {
                return $value->toApiResponse($locale);
            }
            return $value;
        }

        if (is_array($value)) {
            return $value;
        }

        if (str($value)->isJson()) {
            return json_decode($value);
        }
        
        return $value;               
    }    

    protected function getHasMany($collection, $locale = null) {
        return Api::mapApiRepsonse($collection, $locale);
    }

    protected function getBelongsTo($param, $locale = null) {
        return $this->$param?->toApiResponse($locale);
    }

    protected function getMethod($param, $locale = null) {
        $methodResult = $this->$param();

        if(is_object($methodResult) && method_exists($methodResult, 'toApiResponse')) { //the method returns an instance of an object model that uses the apiResponse trait
            $response = $methodResult->toApiResponse($locale);
        } else {
            $response = $methodResult;
        }

        return $response;
    }
}
