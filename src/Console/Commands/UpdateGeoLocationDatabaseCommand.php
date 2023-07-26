<?php

namespace CyberDuck\GeoLocate\Console\Commands;

use CyberDuck\GeoLocate\Services\MaxMindDownloadService;
use CyberDuck\GeoLocate\Traits\BuildsRemoteFilePath;
use Illuminate\Console\Command;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class UpdateGeoLocationDatabaseCommand extends Command
{
    use BuildsRemoteFilePath;

    protected $signature = 'geolocation:update';

    protected $description = 'Download a new version of GeoLite database, and store on the configured disk.';

    public function handle(): int
    {
        $this->info('Downloading Archive...');

        $archive = Container::getInstance()->make(MaxMindDownloadService::class)->downloadArchive();

        $this->info('Writing to disk...');

        $success = Storage::disk(Config::get('geolocation.storage.disk'))
            ->put($this->buildRemoteFilePath(), $archive);

        return $success ? static::SUCCESS : static::FAILURE;
    }
}
