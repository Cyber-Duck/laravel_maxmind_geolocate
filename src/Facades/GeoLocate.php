<?php

namespace CyberDuck\GeoLocate\Facades;

use CyberDuck\GeoLocate\Services\GeoLocationService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \GeoIp2\Record\Country country(string $ipAddress)
 * @see GeoLocationService
 */
class GeoLocate extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'geolocate';
    }
}
