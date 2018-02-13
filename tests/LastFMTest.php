<?php

namespace LuizPedone\LastFM\Tests;

use GuzzleHttp\Client;
use LuizPedone\LastFM\ApiRequestBuilder;
use LuizPedone\LastFM\LastFM;
use LuizPedone\LastFM\Period;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use ReflectionException;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class LastFMTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    const API_KEY = 'api-key';
    const USER_NAME = 'luiz-pedone';

    /**
     * @test
     */
    public function shouldCallGetTopArtistsCorrectly()
    {
        $client = $this->mockClient();

        $client->shouldReceive('request')
            ->withArgs(['GET', $this->buildRequestUrl(self::API_KEY, 'luiz-pedone')])
            ->times(1)
            ->andReturn($this->mockResponseInterface());

        $lastFm = new LastFM(self::API_KEY);
        $lastFm->getTopArtists('luiz-pedone');
    }

    /**
     * @test
     */
    public function shouldParseGetTopArtistsResponseCorrectly()
    {
        $client = $this->mockClient();

        $client->shouldReceive('request')->andReturn($this->mockResponseInterface());

        $lastFm = new LastFM(self::API_KEY);

        $expectedTopArtists = [
            [
                'name' => 'Les Cowboys Fringants',
                'playcount' => 8377
            ],
            [
                'name' => 'Milton Nascimento',
                'playcount' => 4927
            ]
        ];

        $topTracks = $lastFm->getTopArtists('luiz-pedone');

        $this->assertEquals($expectedTopArtists, $topTracks);
    }

    /**
     * @test
     */
    public function shouldAddLimitToRequestUrl()
    {
        $client = $this->mockClient();

        $client->shouldReceive('request')->withArgs([
            'GET',
            $this->buildRequestUrl(self::API_KEY, 'luiz-pedone') . '&limit=10'
        ])->andReturn($this->mockResponseInterface());

        $lastFm = new LastFM(self::API_KEY);
        $lastFm->limit(10)->getTopArtists('luiz-pedone');
    }

    /**
     * @test
     */
    public function shouldAddPageToRequestUrl()
    {
        $client = $this->mockClient();

        $client->shouldReceive('request')->withArgs([
            'GET',
            $this->buildRequestUrl(self::API_KEY, 'luiz-pedone') . '&page=2'
        ])->andReturn($this->mockResponseInterface());

        $lastFm = new LastFM(self::API_KEY);
        $lastFm->page(2)->getTopArtists('luiz-pedone');
    }

    /**
     * @test
     */
    public function shouldAddPeriodToRequestUrl()
    {
        $client = $this->mockClient();

        $client->shouldReceive('request')->withArgs([
            'GET',
            $this->buildRequestUrl(self::API_KEY, 'luiz-pedone') . '&period=7day'
        ])->andReturn($this->mockResponseInterface());

        $lastFm = new LastFM(self::API_KEY);
        $lastFm->period(Period::LAST_SEVEN_DAYS)->getTopArtists('luiz-pedone');
    }

    /**
     * @test
     */
    public function shouldBuildRequestWithMultipleAdditionalParameters()
    {
        $client = $this->mockClient();

        $client->shouldReceive('request')->withArgs([
            'GET',
            $this->buildRequestUrl(self::API_KEY, 'luiz-pedone') . '&period=7day&limit=10&page=2'
        ])->andReturn($this->mockResponseInterface());

        $lastFm = new LastFM(self::API_KEY);
        $lastFm->period(Period::LAST_SEVEN_DAYS)
            ->limit(10)
            ->page(2)
            ->getTopArtists('luiz-pedone');
    }

    private function buildRequestUrl($apiKey, $user)
    {
        return sprintf(
            'http://ws.audioscrobbler.com/2.0/?method=user.gettopartists&user=%s&api_key=%s&format=json',
            $user,
            $apiKey
        );
    }

    private function lastFmApiResponse()
    {
        return file_get_contents(getcwd() . '/tests/responses/top-artists.json');
    }

    private function mockClient()
    {
        $client = Mockery::mock('overload:' . Client::class);
        $client->shouldReceive('getBody')->andReturn($this->lastFmApiResponse());

        return $client;
    }

    /**
     * @return MockObject
     * @throws ReflectionException
     */
    public function mockResponseInterface(): MockObject
    {
        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('getBody')->willReturn($this->lastFmApiResponse());
        return $responseMock;
    }
}
