<?php

namespace CyberDuck\GeoLocate\Services;

use GeoIp2\Database\Reader;
use GeoIp2\Record\Country;

class GeoLocationService
{
    public function __construct(
        private readonly Reader $reader
    ) {
        //
    }

    public function country(string $ipAddress): Country
    {
        return $this->reader->country($ipAddress)->country;
    }
}
