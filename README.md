# Laravel GeoLocation Package

This package utilises the MaxMind GeoLite databases to allow GeoLocation of an IP Address.

## Console Commands

### php artisan geolocation:update

Downloads a recent archive of the database from MaxMind, validates the hash, and stores on a remote disk (e.g S3) as specified in configuration.

### php artisan geolocation:fetch

Fetches, extracts, and moves to application storage, the archive previously stored on a remove disk (e.g S3)

## Usage

Once a database has been downloaded and extracted to the app local storage directory, to geolocate an IP address, use the provided facade, as such:

```php
use CyberDuck\GeoLocate\Facades\GeoLocate;

$result = GeoLocate::country('1.2.3.4');

// $result is an instance of \GeoIp2\Record\Country

$result->isoCode // == 'GB' for example.
```
