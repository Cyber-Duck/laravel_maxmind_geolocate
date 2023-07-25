<?php

namespace CyberDuck\GeoLocate\Traits;

use Illuminate\Support\Facades\Config;

trait BuildsRemoteFilePath
{
    protected function buildRemoteFilePath(): string
    {
        return sprintf(
            '%s/%s.tar.gz',
            Config::get('geolocation.storage.path'),
            Config::get('geolocation.maxmind.edition')
        );
    }
}
