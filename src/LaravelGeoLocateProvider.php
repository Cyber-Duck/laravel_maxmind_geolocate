<?php

namespace CyberDuck\GeoLocate;

use CyberDuck\GeoLocate\Console\Commands\FetchGeoLocationDatabaseCommand;
use CyberDuck\GeoLocate\Console\Commands\UpdateGeoLocationDatabaseCommand;
use CyberDuck\GeoLocate\Services\GeoLocationService;
use CyberDuck\GeoLocate\Traits\BuildsLocalFilePath;
use GeoIp2\Database\Reader;
use Illuminate\Support\ServiceProvider;

class LaravelGeoLocateProvider extends ServiceProvider
{
    use BuildsLocalFilePath;

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                UpdateGeoLocationDatabaseCommand::class,
                FetchGeoLocationDatabaseCommand::class,
            ]);
        }
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/geolocation.php', 'geolocation'
        );

        $this->app->bind('geolocate', function () {
            return new GeoLocationService(new Reader($this->buildLocalFilePath()));
        });
    }
}
