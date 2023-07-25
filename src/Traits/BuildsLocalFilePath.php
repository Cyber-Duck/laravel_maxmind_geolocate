<?php

namespace CyberDuck\GeoLocate\Traits;

use Illuminate\Support\Facades\Config;

trait BuildsLocalFilePath
{
    protected function buildLocalFilePath(): string
    {
        return sprintf(
            '%s/%s.mmdb',
            Config::get('geolocation.storage.local_path'),
            Config::get('geolocation.maxmind.edition')
        );
    }
}
