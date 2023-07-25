<?php

namespace CyberDuck\GeoLocate\Tests\Services;

use CyberDuck\GeoLocate\Services\GeoLocationService;
use CyberDuck\GeoLocate\Tests\TestCase;
use GeoIp2\Database\Reader;
use GeoIp2\Model\Country as CountryModel;
use GeoIp2\Record\Country as CountryRecord;

class GeoLocationServiceTest extends TestCase
{
    /** @test */
    public function it_calls_the_reader_and_returns_a_country_record()
    {
        $reader = $this->mock(Reader::class);

        $reader->shouldReceive('country')
            ->with('1.2.3.4')
            ->once()
            ->andReturn(new CountryModel(['country' => ['iso_code' => 'FR']]));

        $output = (new GeoLocationService($reader))->country('1.2.3.4');

        $this->assertInstanceOf(CountryRecord::class, $output);
        $this->assertEquals('FR', $output->isoCode);
    }
}
