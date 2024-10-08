<?php

namespace App\Models;

use App\Models\Location;
use App\Traits\HashidTrait;
use App\Traits\HasApiResponse;
use App\Traits\HasFeaturedImage;
use App\Traits\HasFeaturedImageHover;
use App\Traits\HasSEOConfigurations;
use App\Traits\HasUploadImages;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class Car extends Model
{
    use HasFactory, HashidTrait, SoftDeletes, HasTranslations, HasApiResponse, HasUploadImages, HasFeaturedImage, HasFeaturedImageHover, HasSEOConfigurations;

    protected $apiResponse = [
        'hashid', 'active', 'name', 'description', 'year',
        'ranking', 'fleet_position', 'users_number_votes', 'adult_passengers',
        'doors', 'luggage', 'beds', 'kitchen', 'heater',
        'engine', 'transmission', 'vehicle_type', 'vehicle_brand', 'f_roads_name', 
        'featured_image', 'featured_image_url', 'featured_image_hover', 'getFeaturedImageModelImageInstance', 'getFeaturedImagaHoverModelImageInstance',
        'fRoadAllowed', 'caren_settings', 'vendor', 'booking_percentage', 'price_from'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'active', 'vendor_id', 'name', 'car_code', 'description', 'year',
        'ranking', 'fleet_position', 'users_number_votes', 'min_preparation_time', 'min_days_booking',
        'adult_passengers', 'doors', 'luggage', 'units', 'online_percentage', 'discount_percentage', 'beds',
        'km_unlimited', 'bath_shower', 'kitchen', 'heater', 'cdw_insurance', 'driver_extra',
        'engine', 'transmission', 'vehicle_type', 'vehicle_brand', 'f_roads_name', 'vehicle_class',
        'caren_id', 'price_from'
    ];

    protected $append = ['booking_percentage'];

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    public $translatable = ['name', 'description'];

    /**
     * Returns for a given locale the translated slug
     * It is used for translatable routes in mcnamara localization package and in Services\SeoCOnfigurations class
     * This method has to be defined when implementing LocalizedUrlRoutable or using HasSEOConfigurations trait
     * @return string
     */
    public function getLocalizedRouteKey($locale)
    {
        return $this->hashid;
    }

    /**********************************
     * Accessors & Mutators
     **********************************/

     /**
      * Get the booking percentage for the pay now action
      * @return int
      */
     public function getBookingPercentageAttribute() {
        if(!empty($this->caren_id)) {            
            return $this->vendor->caren_settings["online_percentage"];
        } else {
            return 20;
        }
     }

    /**
     * Get the car's edit URL
     *
     * @return     string
     */
    public function getEditUrlAttribute()
    {
        return route('intranet.car.edit', $this->hashid);
    }

    /**********************************
     * Methods
     **********************************/

    /**
     * Update some fields from Caren Settings
     *
     * @param   array   $carenSettings
     *
     * @return  void
     */
    public function updateFromCarenCar($carenSettings)
    {
        $carFields = [];

        if (isset($carenSettings["Id"])) {
            $carFields["caren_id"] = $carenSettings["Id"];
        }

        if (isset($carenSettings["Seats"])) {
            $carFields["adult_passengers"] = $carenSettings["Seats"];
        }

        if (isset($carenSettings["Doors"])) {
            $carFields["doors"] = $carenSettings["Doors"];
        }

        if (isset($carenSettings["Beds"])) {
            $carFields["beds"] = $carenSettings["Beds"];
        }

        if (isset($carenSettings["FuelName"])) {
            $fuelName = translate_caren_fuelname($carenSettings["FuelName"]);
            if ($fuelName != '') {
                $carFields["engine"] = $fuelName;
            }
        }

        if (isset($carenSettings["TransmissionName"])) {
            $transmissionName = translate_caren_transmission($carenSettings["TransmissionName"]);
            if ($transmissionName != '') {
                $carFields["transmission"] = $transmissionName;
            }
        }

        if (isset($carenSettings["DriveName"])) {
            $roadName = translate_caren_road($carenSettings["DriveName"]);
            if ($roadName != '') {
                $carFields["f_roads_name"] = $roadName;
            }
        }

        $this->update($carFields);
    }

    /**
     * Check if the car is allowed in F roads
     *
     * @return  bool
     */
    public function fRoadAllowed()
    {
        return $this->f_roads_name == 'fwd';
    }

    /**
     * Get the insurance list for a car
     *
     * @return  object
     */
    public function insuranceList($active = null)
    {
        $list = new Collection();

        // Get the insurance prices from Caren
        $insurancePrices = [];
        $api = new \App\Apis\Caren\Api();
        $carenParams = [
            "RentalId" => $this->vendor->caren_settings["rental_id"],
            "classId" => $this->caren_id,
        ];
        $carenInsurances = $api->extraList('insurace', $carenParams);

        if (isset($carenInsurances['Insurances'])) {
            foreach ($carenInsurances['Insurances'] as $carenInsurance) {
                $insurancePrices[$carenInsurance['Id']] = $carenInsurance['Price'];
            }
        }

        if($active) {
            $insurances = $this->insurances()->where('active', 1);
        } else {
            $insurances = $this->insurances;
        }

        foreach($insurances as $insurance) {            
            $insurance->price = isset($insurancePrices[$insurance['caren_id']])
                ? $insurancePrices[$insurance['caren_id']]
                : 0;
            $list->push($insurance);
        }

        return $list;
    }

    /**
     * Get extra list for a car including caren and own
     * It also includes the price of the extra in unit_price extra property
     *
     * @return mixed
     */
    public function extraList($active = null)
    {        
        $carenExtras = $this->getCarenExtras();

        // Get extras and use filter to remove extras that does not have price
        $query = $this->extras()->where('category', 'standard');

        if($active) $query->where('active', 1);

        $list = $query->get()
            ->map(function ($extra) use ($carenExtras) {  
                $extra->unit_price = $extra->getPriceUsingCarenExtras($carenExtras);

                return $extra;
            })
            ->filter(function ($extra) {
                return $extra->unit_price !== null;
            })
            ->values();

        return $list;
    }

    /**
     * Get extra list from Caren
     *
     * @return array
     */
    public function getCarenExtras()
    {
        $api = new \App\Apis\Caren\Api();
        $carenParams = [
            "RentalId" => $this->vendor->caren_settings["rental_id"],
            "classId"  => $this->caren_id,
        ];
        $carenExtras = $api->extraList('extra', $carenParams);        
       
        return $carenExtras;
    }

    /**********************************
     * Accessors & Mutators
     **********************************/

    /**
     * Get the vendor's logo URL
     *
     * @return     \Illuminate\Support\Collection
     */
    public function getAssignableLocationsAttribute()
    {
        $vendorLocationIds = ($this->vendor->vendorLocations->pluck('location_id'));

        return Location::whereIn('id', $vendorLocationIds)->orderBy('name')->get();
    }

    /**********************************
     * Scopes
     **********************************/

    /**
     * Scope to search the model
     *
     * @param      object  $query   Illuminate\Database\Query\Builder
     * @param      string  $status  Car Status
     * @param      string  $vendor  Vendor hashid
     * @param      string  $search  String to search
     *
     * @return     object  Illuminate\Database\Query\Builder
     */
    public function scopeLivewireSearch($query, $status, $vendor, $search)
    {
        if (!empty($status)) {
            $query->where('cars.active', intval($status));
        }

        if (!empty($vendor)) {
            $query->where('cars.vendor_id', dehash($vendor));
        }

        if (!empty($search)) {
            //break down multiple words into sepearate string queries, using " " to group words
            //into a single query
            collect(str_getcsv($search, ' ', '"'))->filter()->each(function ($term) use ($query) {
                $term = '%' . $term . '%';
                $query->where('cars.name', 'like', $term)
                    ->orWhere('vendors.name', 'like', $term);
            });
        }

        return $query;
    }

    /**
     * Scope to get cars from caren
     *
     * @param      object  $query   Illuminate\Database\Query\Builder
     *
     * @return     object  Illuminate\Database\Query\Builder
     */
    public function scopeFromCaren($query) {
        return $query->whereNotNull('caren_id');
    }

    /**
     * Scope to get active cars
     *
     * @param      object  $query   Illuminate\Database\Query\Builder
     *
     * @return     object  Illuminate\Database\Query\Builder
     */
    public function scopeActive($query) {
        return $query->where('active', 1);
    }

    /**********************************
     * Relationships
     **********************************/

    /**
     * Related vendor
     *
     * @return object
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }    

    /**
     * Related unavailable dates
     *
     * @return object
     */
    public function unavailableDates()
    {
        return $this->hasMany(CarUnavailable::class);
    }

    /**
     * Pivot table car_free_day
     *
     * @return object
     */
    public function carFreeDays()
    {
        return $this->hasMany(CarFreeDay::class);
    }

    /**
     * Pivot table car_location
     *
     * @return object
     */
    public function carLocations()
    {
        return $this->hasMany(CarLocation::class);
    }

    /**
     * Define belongsToMany extras
     *
     * @return object
     */
    public function extras()
    {
        return $this->belongsToMany('App\Models\Extra')->withTimestamps();
    }

    public function carenCar() {
        return $this->hasOne(CarenCar::class, 'caren_id', 'caren_id');
    }

    /**
     * If the car does not belong to caren, return all the extras that are not from caren
     * If the car belongs to caren, return all the extras that are not from caren and the ones that are from caren and belong to the car (excluding the extras that are from caren but belong to another caren car)
     *
     * @return object
     */
    public function availableExtras()
    {
        if($this->caren_id) {     
            return Extra::where('extras.vendor_id', $this->vendor_id)
            ->leftJoin('caren_extras', 'extras.caren_id', '=', 'caren_extras.caren_id')
            ->leftJoin('caren_car_caren_extra', 'caren_extras.id', '=', 'caren_car_caren_extra.caren_extra_id')
            ->where(function ($query) {
                // Select extras where caren_id is null
                $query->whereNull('extras.caren_id')
                      // Or where caren_extra_id is linked to this car in caren_car_caren_extra
                      ->orWhere(function ($query)  {
                          $query->whereNotNull('extras.caren_id')
                                ->where('caren_car_caren_extra.caren_car_id', $this->carenCar->id);
                      });
            })
            ->select('extras.*') // Select only the columns from the extras table
            ->distinct();            

        } else {
            return Extra::where('extras.vendor_id', $this->vendor_id)->whereNull('caren_id');
        }
    }

    /**
     * Define belongsToMany insurances
     *
     * @return object
     */
    public function insurances()
    {
        return $this->belongsToMany('App\Models\Insurance', 'car_extra', 'car_id', 'extra_id')->withTimestamps();
    }
}
