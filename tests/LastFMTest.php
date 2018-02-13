<?php

namespace LuizPedone\LastFM\Tests;

use LuizPedone\LastFM\ApiRequestBuilder;
use LuizPedone\LastFM\LastFM;
use PHPUnit\Framework\TestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class LastFMTest extends TestCase
{
    const API_KEY = 'api-key';

    /**
     * @test
     */
    public function shouldCallGetTopArtistsCorrectly()
    {
        $requestBuilderMock = \Mockery::mock('overload:' . ApiRequestBuilder::class);
        $requestBuilderMock->shouldReceive('getTopArtists')
            ->once()
            ->with('luiz-pedone')
            ->andReturn('anything');

        $lastFm = new LastFM(self::API_KEY);

        $this->assertEquals('anything', $lastFm->getTopArtists('luiz-pedone'));
    }
}
