<?php

namespace App\Models;

use App\Traits\HasApiResponse;
use App\Traits\HasFeaturedImage;
use App\Traits\HashidTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Vendor extends Model
{
    use HasFactory, HashidTrait, SoftDeletes, HasFeaturedImage, HasApiResponse;

    protected $apiResponse = ['name', 'service_fee', 'vendor_code', 'status', 'brand_color', 'logo',
    'long_rental', 'early_bird',
    'caren_settings'];

    protected $featured_image_attribute = 'logo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'service_fee', 'vendor_code', 'status', 'brand_color', 'logo',
        'long_rental', 'early_bird',
        'caren_settings'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'long_rental' => 'array',
        'early_bird' => 'array',
        'caren_settings' => 'array'
    ];

    /**********************************
     * Accessors & Mutators
     **********************************/

    /**
     * Get the vendor's edit URL
     *
     * @return     string
     */
    public function getEditUrlAttribute()
    {
        return route('intranet.vendor.edit', $this->hashid);
    }

    /**
     * Get the vendor's logo URL
     *
     * @return     string
     */
    public function getLogoUrlAttribute()
    {
        return $this->featured_image_url;            
    }

    /**
     * Get the vendor's PDF path
     *
     * @return     string
     */
    public function getPdfPathAttribute()
    {
        return Storage::disk('public')->exists('vendors/pdf/' . $this->hashid . '.pdf')
            ? asset('storage/vendors/pdf/' . $this->hashid . '.pdf')
            : '';
    }

    /**********************************
     * Scopes
     **********************************/

    /**
     * Scope to search the model
     *
     * @param      object  $query    Illuminate\Database\Query\Builder
     * @param      object  $request  Illuminate\Http\Request
     *
     * @return     object  Illuminate\Database\Query\Builder
     */
    public function scopeLivewireSearch($query, $search)
    {
        if (!empty($search)) {
            //break down multiple words into sepearate string queries, using " " to group words
            //into a single query
            collect(str_getcsv($search, ' ', '"'))->filter()->each(function ($term) use ($query) {
                $term = '%' . $term . '%';
                $query->where('name', 'like', $term)
                    ->orWhere('vendor_code', 'like', $term);
            });
        }

        return $query;
    }

     /**
     * Scope to get active vendors
     *
     * @param      object  $query   Illuminate\Database\Query\Builder
     *
     * @return     object  Illuminate\Database\Query\Builder
     */
    public function scopeActive($query) {
        return $query->where('status', 'active');
    }

    /**********************************
     * Relationships
     **********************************/

    /**
     * Pivot table vendor_location
     *
     * @return object
     */
    public function vendorLocations()
    {
        return $this->hasMany(VendorLocation::class);
    }

    /**
     * Pivot table vendor_location_fees
     *
     * @return object
     */
    public function vendorLocationFees()
    {
        return $this->hasMany(VendorLocationFee::class);
    }

    /**
     * Vendor locations
     *
     * @return object
     */
    public function locations()
    {
        return $this->belongsToMany(Location::class, 'vendor_location');
    }

    /**
     * Vendor cars
     *
     * @return object
     */
    public function cars()
    {
        return $this->hasMany(Car::class);
    }

    /**
     * Vendor holidays
     *
     * @return object
     */
    public function holidays()
    {
        return $this->hasMany(VendorHoliday::class);
    }
}
