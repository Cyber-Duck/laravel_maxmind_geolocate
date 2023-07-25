<?php

namespace CyberDuck\GeoLocate\Console\Commands;

use CyberDuck\GeoLocate\Services\ArchiveService;
use CyberDuck\GeoLocate\Traits\BuildsRemoteFilePath;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class FetchGeoLocationDatabaseCommand extends Command
{
    use BuildsRemoteFilePath;

    protected $name = 'geolocation:fetch';

    protected $description = 'Fetch the latest stored version of the GeoLite database from the configured remote disk.';

    public function __construct(
        private readonly ArchiveService $archiveService
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('Downloading archive from remote disk...');

        $archive = Storage::disk(Config::get('geolocation.storage.disk'))
            ->get($this->buildRemoteFilePath());

        $this->info('Extracting database from archive...');

        $this->archiveService->extract($archive);

        return static::SUCCESS;
    }
}
