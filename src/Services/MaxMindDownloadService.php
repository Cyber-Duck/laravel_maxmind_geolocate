<?php

namespace CyberDuck\GeoLocate\Services;

use CyberDuck\GeoLocate\Exceptions\MaxMindDownloadException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Config;
use Psr\Http\Message\StreamInterface;

class MaxMindDownloadService
{
    public function __construct(
        private readonly Client $client
    ) {
        //
    }

    /**
     * @throws MaxMindDownloadException
     */
    public function downloadArchive(): StreamInterface
    {
        if (empty(Config::get('geolocation.maxmind.api_key'))) {
            throw new MaxMindDownloadException('Missing API Key');
        }

        try {
            $response = $this->client->get($this->buildUrl());
        } catch (RequestException $ex) {
            throw new MaxMindDownloadException($ex->getMessage());
        }

        if ($response->getStatusCode() !== 200) {
            throw new MaxMindDownloadException(
                sprintf(
                    'Error fetching database from endpoint: [%d]: %s',
                    $response->getStatusCode(),
                    $response->getReasonPhrase()
                )
            );
        }

        $archive = $response->getBody();

        $this->verify($archive);

        return $archive;
    }

    private function verify(string $contents)
    {
        $response = $this->client->get($this->buildUrl('tar.gz.sha256'));

        $expectedHash = explode(' ', $response->getBody())[0];

        if (! hash_equals($expectedHash, hash('sha256', $contents))) {
            throw new MaxMindDownloadException('Unable to verify download - hash mismatch.');
        }
    }

    private function buildUrl(string $suffix = 'tar.gz'): string
    {
        $url = Config::get('geolocation.maxmind.permalink');
        $apiKey = Config::get('geolocation.maxmind.api_key');
        $edition = Config::get('geolocation.maxmind.edition');

        $url = str_replace(
            [':api_key', ':edition', ':suffix'],
            [$apiKey, $edition, $suffix],
            $url
        );

        return $url;
    }
}
