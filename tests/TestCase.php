<?php

namespace CyberDuck\GeoLocate\Tests;

use CyberDuck\GeoLocate\LaravelGeoLocateProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            LaravelGeoLocateProvider::class,
        ];
    }
}
