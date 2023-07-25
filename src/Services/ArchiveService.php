<?php

namespace CyberDuck\GeoLocate\Services;

use CyberDuck\GeoLocate\Traits\BuildsLocalFilePath;
use Illuminate\Support\Facades\Config;
use Phar;
use PharData;

class ArchiveService
{
    use BuildsLocalFilePath;

    public function extract(string $archiveContents)
    {
        $tempArchive = $this->createTempArchive($archiveContents);

        $extractedDb = $this->extractArchive($tempArchive);

        $this->moveExtractedDbToApp($extractedDb);

        @unlink($extractedDb);
        @unlink($tempArchive);
    }

    private function moveExtractedDbToApp(string $extractedLocation): void
    {
        rename(
            $extractedLocation,
            $this->buildLocalFilePath(),
        );
    }

    private function extractArchive(string $archivePath): string
    {
        $tar = new PharData($archivePath, format: Phar::GZ);

        $dir = basename($tar->current()->getPathname());

        $pathInArchive = sprintf(
            '%s/%s.mmdb',
            $dir,
            Config::get('geolocation.maxmind.edition')
        );

        $tar->extractTo(
            sys_get_temp_dir(),
            $pathInArchive,
            overwrite: true
        );

        return sys_get_temp_dir() . '/' . $pathInArchive;
    }

    private function createTempArchive(string $archiveContents): string
    {
        $tempArchive = tempnam(sys_get_temp_dir(), 'geodb') . '.tar.gz';
        file_put_contents($tempArchive, $archiveContents);

        return $tempArchive;
    }
}
