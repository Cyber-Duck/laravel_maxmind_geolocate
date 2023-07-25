<?php

namespace CyberDuck\GeoLocate\Tests\Services;

use CyberDuck\GeoLocate\Exceptions\MaxMindDownloadException;
use CyberDuck\GeoLocate\Services\MaxMindDownloadService;
use CyberDuck\GeoLocate\Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Config;

class MaxMindDownloadServiceTest extends TestCase
{
    /** @test */
    public function it_throws_exception_if_api_key_is_empty()
    {
        Config::set('geolocation', []);

        $this->expectException(MaxMindDownloadException::class);
        $this->expectExceptionMessage('Missing API Key');

        $this->app->make(MaxMindDownloadService::class)->downloadArchive();
    }

    /** @test */
    public function if_throws_exception_if_response_is_not_ok()
    {
        $mock = new MockHandler([new Response(400)]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $this->expectException(MaxMindDownloadException::class);
        $this->expectExceptionMessageMatches('/400 Bad Request/');

        (new MaxMindDownloadService($client))->downloadArchive();
    }

    /** @test */
    public function it_throws_exception_if_hash_does_not_match()
    {
        $mock = new MockHandler([
            new Response(200, [], 'the archive content'),
            new Response(200, [], 'bad_hash  file.tar.gz'),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $this->expectException(MaxMindDownloadException::class);
        $this->expectExceptionMessage('Unable to verify download - hash mismatch.');

        (new MaxMindDownloadService($client))->downloadArchive();
    }

    /** @test */
    public function it_returns_archive_as_string()
    {
        $mockContent = 'the archive content';
        $hash = hash('sha256', $mockContent);

        $mock = new MockHandler([
            new Response(200, [], $mockContent),
            new Response(200, [], $hash . '  file.tar.gz'),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $archive = (new MaxMindDownloadService($client))->downloadArchive();

        $this->assertEquals($mockContent, $archive);
    }
}
