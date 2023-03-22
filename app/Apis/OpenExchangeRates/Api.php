<?php

namespace App\Apis\OpenExchangeRates;

use App\Models\Rate;
use Exception;
use Illuminate\Support\Facades\Cache;

class Api
{
    /***************************************************
     * PROPERTIES
     **************************************************/

    /**
     * @var String
     */
    private $url = "";

    /**
     * @var String
     */
    private $appId = "";

    /**
     * Json response from the lookup
     *
     * @var object
     */
    public $json;

    /***************************************************
     * METHODS
     **************************************************/

    public function __construct()
    {
        $this->url   = config('open-exchange-rates.url');
        $this->appId = config('open-exchange-rates.app_id');
    }

    /**
     * Get the rates
     *
     * @return array
     */
    public function getRates()
    {
        $expiry = 10; // minutes
        $expiry_seconds = ($expiry * 60) - 10;

        return Cache::remember('exchange_rates',  $expiry_seconds, function () {
            try {
                $json = file_get_contents($this->url . $this->appId);
                return json_decode($json, true)["rates"];
            } catch (Exception $e) {
                // If OpenExchangeRates fails, use the rates in the database
                return Rate::all()->pluck('rate', 'code')->toArray();
            }
        });
    }
}
