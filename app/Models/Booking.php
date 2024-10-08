<?php

namespace App\Models;

use App\Traits\HashidTrait;
use App\Traits\HasPdf;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Casts\ArrayCast;
use App\Traits\HasApiResponse;

class Booking extends Model
{
    use HasFactory, HashidTrait, SoftDeletes, HasPdf, HasApiResponse;    

    protected $apiResponse = ['hashid', 'status', 'payment_status', 'valitor_request', 'valitor_response', 'name', 'email', 'telephone', 'address', 'city', 'postal_code', 'country'];

    protected $valitor_reference_number_column = 'hashid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'car_id', 'vendor_id', 'status', 'cancel_reason', 'car_name', 'vendor_name',
        'pickup_name', 'dropoff_name', 'pickup_at', 'dropoff_at', 'pickup_location', 'dropoff_location',
        'rental_price', 'exrtras_price', 'total_price', 'online_payment',
        'order_number', 'amount_paid', 'currency_paid', 'payment_method',
        'payment_status', 'vendor_status', 'pickup_input_info', 'dropoff_input_info',
        'first_name', 'last_name', 'email', 'telephone', 'number_passengers',
        'driver_name', 'driver_date_birth', 'driver_id_passport', 'driver_license_number',
        'country', 'address', 'city', 'state', 'postal_code','additional_info', 'weight_info',
        'extra_driver_info1', 'extra_driver_info2', 'extra_driver_info3', 'extra_driver_info4',
        'affiliate_id', 'affiliate_commission',
        'caren_id', 'caren_guid', 'caren_info',
        'created_at'
    ];

    protected $append = ['pay_now_amount', 'valitor_reference_number'];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'caren_info'        => 'array',
        'pickup_at'         => 'datetime',
        'dropoff_at'        => 'datetime',
        'valitor_request'   => ArrayCast::class,
        'valitor_response'  => ArrayCast::class
    ];     

    /**
     * Route notifications for the mail channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return array|string
     */
    public function routeNotificationForMail($notification)
    {        
        return $this->email;        
    }

    /**********************************
     * Accessors & Mutators
     **********************************/

     /**
      * Get the referenc number that we will send to valitor
      */
    public function getValitorReferenceNumberAttribute() {
        $valitor_reference_number_column = $this->valitor_reference_number_column;

        return $this->$valitor_reference_number_column;
    }    

    /**
     * Get the percentage to pay now
     *
     * @return     string
     */
    public function getPayNowAmountAttribute()
    {
        return round($this->total_price * ($this->car->booking_percentage / 100));
    }

    /**
     * Get the booking's edit URL
     *
     * @return     string
     */
    public function getEditUrlAttribute()
    {
        return route('intranet.booking.edit', $this->hashid);
    }

    /**
     * Get the order ID
     * - If it's from Caren, return the Caren ID
     * - If it's from "Own", return the Order Number
     *
     * @return     string
     */
    public function getOrderIdAttribute()
    {
        return $this->caren_id ? $this->caren_id : $this->order_number;
    }

    /**
     * Check if a booking must have Caren info or not
     *
     * @return     string
     */
    public function getIsCarenAttribute()
    {
        return $this->caren_id || $this->vendor->caren_settings;
    }

    /**
     * Get the last log
     *
     * @return     string
     */
    public function getLastLogAttribute()
    {
        $lastLog = $this->logs()->latest('id')->first();

        return $lastLog ? $lastLog->message : "-";
    }

    /**
     * Get the affiliate name
     *
     * @return     string
     */
    public function getAffiliateNameAttribute()
    {
        return $this->affiliate ? $this->affiliate->name : "";
    }

    /**
     * Get the customer's full name
     *
     * @return     string
     */
    public function getNameAttribute()
    {
        return $this->first_name . " " . $this->last_name;
    }

    /**
     * Get the parameters to create a booking in Caren
     *
     * @return     array
     */
    public function getCarenParametersAttribute()
    {
        $extras = [];
        $insurances = [];

        foreach ($this->bookingExtras()->with('extra')->get() as $bookingExtra) {
            if (!$bookingExtra->extra->caren_id) {
                continue;
            }

            if ($bookingExtra->extra->category == 'insurance') {
                $insurances[] = [$bookingExtra->extra->caren_id, $bookingExtra->units];
            } else {
                $extras[] = [$bookingExtra->extra->caren_id, $bookingExtra->units];
            }
        }

        // We send only the main driver info
        $drivers = [
            [
                "", // ID (always empty)
                $this->driver_name,
                $this->driver_license_number,
                $this->driver_date_birth,
                true
            ]
        ];

        return [
            "RentalId" => $this->vendor->caren_settings["rental_id"],
            "Language" => "en-GB",
            "ClassId" => $this->car->caren_id,
            "DateFrom" => $this->pickup_at->format('Y-m-d H:i:s'),
            "DateTo" => $this->dropoff_at->format('Y-m-d H:i:s'),
            "PickupLocationId" => $this->pickupLocation->caren_settings["caren_pickup_location_id"],
            "DropoffLocationId" => $this->dropoffLocation->caren_settings["caren_dropoff_location_id"],
            "Passengers" => $this->number_passengers,

            "Customer" => [
                "FirstName" => $this->first_name,
                "LastName" => $this->last_name,
                "Email" => $this->email,
                "Mobile" => $this->telephone,
                "Passport" => $this->driver_id_passport,
                "Address" => $this->address,
                "City" => $this->city,
                "ZipCode" => $this->postal_code,
                "CountryId" => null
            ],

            "Extras" => $extras,
            "Insurances" => $insurances,
            "Drivers" => $drivers,
            "ArrivalTime" => $this->pickup_at->format('H:i'),
            "FlightNumber" => "",
            "Comments" => "Scandinavian Test"
        ];
    }

    /**********************************
     * Scopes
     **********************************/

    /**
     * Scope to search the model for the history search
     *
     * @param      object  $query               Illuminate\Database\Query\Builder
     * @param      string  $booking_start_date  string
     * @param      string  $booking_end_date    string
     * @param      string  $pickup_start_date   string
     * @param      string  $pickup_end_date     string
     * @param      string  $dropoff_start_date  string
     * @param      string  $dropoff_end_date    string
     * @param      string  $payment_status      string
     * @param      string  $vendor_status       string
     * @param      string  $booking_status      string
     * @param      string  $vehicle             string
     * @param      string  $vendor              string
     * @param      string  $order_id            string
     * @param      string  $email               string
     * @param      string  $first_name          string
     * @param      string  $last_name           string
     * @param      string  $telephone           string
     * @param      string  $pickup_location     string
     * @param      string  $dropoff_location     string
     *
     * @return     object  Illuminate\Database\Query\Builder
     */
    public function scopeHistorySearch(
        $query,
        $booking_start_date,
        $booking_end_date,
        $pickup_start_date,
        $pickup_end_date,
        $dropoff_start_date,
        $dropoff_end_date,
        $payment_status,
        $vendor_status,
        $booking_status,
        $vehicle,
        $vendor,
        $order_id,
        $email,
        $first_name,
        $last_name,
        $telephone,
        $pickup_location,
        $dropoff_location,
    ) {
        if (!empty($booking_start_date)) {
            $query->whereDate('created_at', '>=', Carbon::createFromFormat("d-m-Y", $booking_start_date));
        }

        if (!empty($booking_end_date)) {
            $query->whereDate('created_at', '<=', Carbon::createFromFormat("d-m-Y", $booking_end_date));
        }

        if (!empty($pickup_start_date)) {
            $query->whereDate('pickup_at', '>=', Carbon::createFromFormat("d-m-Y", $pickup_start_date));
        }

        if (!empty($pickup_end_date)) {
            $query->whereDate('pickup_at', '<=', Carbon::createFromFormat("d-m-Y", $pickup_end_date));
        }

        if (!empty($dropoff_start_date)) {
            $query->whereDate('dropoff_at', '>=', Carbon::createFromFormat("d-m-Y", $dropoff_start_date));
        }

        if (!empty($dropoff_end_date)) {
            $query->whereDate('dropoff_at', '<=', Carbon::createFromFormat("d-m-Y", $dropoff_end_date));
        }

        if (!empty($payment_status)) {
            $query->where('payment_status', $payment_status);
        }

        if (!empty($vendor_status)) {
            $query->where('vendor_status', $vendor_status);
        }

        if (!empty($booking_status)) {
            $query->where('status', $booking_status);
        }

        if (!empty($vehicle)) {
            $query->where('car_id', dehash($vehicle));
        }

        if (!empty($vendor)) {
            $query->where('vendor_id', dehash($vendor));
        }

        if (!empty($order_id)) {
            collect(str_getcsv($order_id, ' ', '"'))->filter()->each(function ($term) use ($query) {
                $term = '%' . $term . '%';
                $query->where('order_number', 'like', $term)
                    ->orWhere('caren_id', 'like', $term);
            });
        }

        if (!empty($email)) {
            collect(str_getcsv($email, ' ', '"'))->filter()->each(function ($term) use ($query) {
                $term = '%' . $term . '%';
                $query->where('email', 'like', $term);
            });
        }

        if (!empty($first_name)) {
            collect(str_getcsv($first_name, ' ', '"'))->filter()->each(function ($term) use ($query) {
                $term = '%' . $term . '%';
                $query->where('first_name', 'like', $term);
            });
        }

        if (!empty($last_name)) {
            collect(str_getcsv($last_name, ' ', '"'))->filter()->each(function ($term) use ($query) {
                $term = '%' . $term . '%';
                $query->where('last_name', 'like', $term);
            });
        }

        if (!empty($telephone)) {
            collect(str_getcsv($telephone, ' ', '"'))->filter()->each(function ($term) use ($query) {
                $term = '%' . $term . '%';
                $query->where('telephone', 'like', $term);
            });
        }

        if (!empty($pickup_location)) {
            $query->where('pickup_location', dehash($pickup_location));
        }

        if (!empty($dropoff_location)) {
            $query->where('dropoff_location', dehash($dropoff_location));
        }

        return $query;
    }

    /**
     * Scope to search the model for the affiliate search
     *
     * @param      object  $query               Illuminate\Database\Query\Builder
     * @param      string  $year    int|null
     * @param      string  $status  string
     *
     * @return     object  Illuminate\Database\Query\Builder
     */
    public function scopeAffiliateSearch($query, $year, $status)
    {
        if (!empty($year)) {
            $query->whereYear('dropoff_at', $year);
        }

        if (!empty($status)) {
            if ($status == 'concluded') {
                $query->where('status', 'concluded');
            } else {
                $query->where('status', '!=', 'concluded');
            }
        }

        return $query;
    }

    /**
     * Scope to search the model for the statistics section
     *
     * @param      object  $query               Illuminate\Database\Query\Builder
     * @param      string  $booking_start_date  string
     * @param      string  $booking_end_date    string
     * @param      string  $date                int
     * @param      string  $vendor              string
     *
     * @return     object  Illuminate\Database\Query\Builder
     */
    public function scopeStatisticsSearch($query, $booking_start_date, $booking_end_date, $date, $vendor)
    {
        if (!empty($booking_start_date)) {
            $query->whereDate('bookings.created_at', '>=', Carbon::createFromFormat("d-m-Y", $booking_start_date));
        }

        if (!empty($booking_end_date)) {
            $query->whereDate('bookings.created_at', '<=', Carbon::createFromFormat("d-m-Y", $booking_end_date));
        }

        if (!empty($date)) {
            if (strlen($date) == 4) {
                $from = date($date . '-01-01');
                $to = date($date . '-12-31');
                $query->whereBetween('bookings.created_at', [$from, $to]);
            } else {
                $from = date((now()->year - $date) . '-01-01');
                $query->whereDate('bookings.created_at', '>=', $from);
            }
        }

        if (!empty($vendor)) {
            $query->where('bookings.vendor_id', dehash($vendor));
        }

        return $query;
    }

    /**
     * Scope to search by the valitorreference number.
     * @param      object  $query             Illuminate\Database\Query\Builder
     * @param      string  $reference_number  string
     *
     * @return     object  Illuminate\Database\Query\Builder
     */
    public function scopeValitorReferenceNumber($query, $reference_number) {
        return $query->where($this->valitor_reference_number_column, $reference_number);
    }

    /**********************************
     * Relationships
     **********************************/

    /**
     * Related car
     *
     * @return object
     */
    public function car()
    {
        return $this->belongsTo(Car::class);
    }

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
     * Related affiliate
     *
     * @return object
     */
    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class);
    }

    /**
     * Related pickup location
     *
     * @return object
     */
    public function pickupLocation()
    {
        return $this->belongsTo(Location::class, 'pickup_location');
    }

    /**
     * Related dropoff location
     *
     * @return object
     */
    public function dropoffLocation()
    {
        return $this->belongsTo(Location::class, 'dropoff_location');
    }

    /**
     * Related booking extras (pivot)
     *
     * @return object
     */
    public function bookingExtras()
    {
        return $this->hasMany(BookingExtra::class);
    }

    /**
     * Related extras
     *
     * @return object
     */
    public function extras()
    {
        return $this->belongsToMany(Extra::class, 'booking_extra');
    }

    /**
     * Related booking logs
     *
     * @return object
     */
    public function logs()
    {
        return $this->hasMany(BookingLog::class);
    }
}
