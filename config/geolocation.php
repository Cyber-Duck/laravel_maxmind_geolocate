<?php

return [
    'storage' => [
        /*
         * Remote disk to store the downloaded archive on.
         */
        'disk' => env('FILESYSTEM_DRIVER', 'local'),

        /*
         * Path to store the archive on the remote disk configured above.
         */
        'remote_path' => 'geolocation',

        /*
         * Path to store the extracted database file after fetching from the remote disk.
         */
        'local_path' => storage_path('app'),
    ],

    'maxmind' => [
        /*
         * API key obtained from https://www.maxmind.com/en/account/login
         */
        'api_key' => env('GEOIP_KEY'),

        /*
         * Database edition to download. Options include:
         * GeoLite2-Country
         * GeoLite2-City
         * GeoLite2-ASN
         */
        'edition' => env('GEOIP_DATABASE_EDITION', 'GeoLite2-Country'),

        /*
         * Permalink used to download the databases. Parameters are populated using configuration values.
         */
        'permalink' => 'https://download.maxmind.com/app/geoip_download?edition_id=:edition&license_key=:api_key&suffix=:suffix',
    ],
];
