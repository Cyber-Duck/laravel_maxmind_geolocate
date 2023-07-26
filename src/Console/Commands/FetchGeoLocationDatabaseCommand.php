<?php

namespace CyberDuck\GeoLocate\Console\Commands;

use CyberDuck\GeoLocate\Services\ArchiveService;
use CyberDuck\GeoLocate\Traits\BuildsRemoteFilePath;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class FetchGeoLocationDatabaseCommand extends Command
{
    use BuildsRemoteFilePath;

    protected $signature = 'geolocation:fetch {--u|update-if-missing}';

    protected $description = 'Fetch the latest stored version of the GeoLite database from the configured remote disk.';

    public function __construct(
        private readonly ArchiveService $archiveService
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('Downloading archive from remote disk...');

        try {
            $archive = Storage::disk(Config::get('geolocation.storage.disk'))
                ->get($this->buildRemoteFilePath() . '222');
        } catch (FileNotFoundException $ex) {
            return $this->handleRemoteFileMissing();
        }

        $this->info('Extracting database from archive...');

        $this->archiveService->extract($archive);

        return static::SUCCESS;
    }

    private function handleRemoteFileMissing(): int
    {
        $this->error('Database not found on remote disk.');

        if (! $this->option('update-if-missing')) {
            return static::FAILURE;
        }

        // Attempt to fetch the database into the remote storage disk, and then re-call
        // the current command (without the -u flag) to process it. This allows 1
        // attempt at update + fetch. If it is still missing, the flow fails.
        $this->call(UpdateGeoLocationDatabaseCommand::class);

        return $this->call(self::class);
    }
}
