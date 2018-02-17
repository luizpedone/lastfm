<?php

namespace LuizPedone\LastFM\Tests;

use GuzzleHttp\Client;
use LuizPedone\LastFM\Period;
use LuizPedone\LastFM\User;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use ReflectionException;

class UserTest extends TestCase
{
    const API_KEY = 'api-key';
    const USER_NAME = 'luiz-pedone';

    /**
     * @var MockObject
     */
    private $client;

    /**
     * @var User
     */
    private $lastFmUser;

    public function setUp()
    {
        $this->client = $this->createMock(Client::class);
        $this->lastFmUser = new User($this->client, self::API_KEY);
    }

    /**
     * @test
     */
    public function shouldCallGetTopArtistsCorrectly()
    {
        $this->client->expects($this->once())
            ->method('request')
            ->with('GET', $this->buildRequestUrl(self::API_KEY, 'luiz-pedone'))
            ->willReturn($this->mockResponseInterface());

        $this->lastFmUser->getTopArtists('luiz-pedone');
    }

    /**
     * @test
     */
    public function shouldParseGetTopArtistsResponseCorrectly()
    {
        $this->client->method('request')->willReturn($this->mockResponseInterface());

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

        $topTracks = $this->lastFmUser->getTopArtists('luiz-pedone');

        $this->assertEquals($expectedTopArtists, $topTracks);
    }

    /**
     * @test
     */
    public function shouldAddLimitToRequestUrl()
    {
        $this->client->expects($this->once())
            ->method('request')
            ->with('GET', $this->buildRequestUrl(self::API_KEY, 'luiz-pedone') . '&limit=10')
            ->willReturn($this->mockResponseInterface());

        $this->lastFmUser->limit(10)->getTopArtists('luiz-pedone');
    }

    /**
     * @test
     */
    public function shouldAddPageToRequestUrl()
    {
        $this->client->expects($this->once())
            ->method('request')
            ->with('GET', $this->buildRequestUrl(self::API_KEY, 'luiz-pedone') . '&page=2')
            ->willReturn($this->mockResponseInterface());

        $this->lastFmUser->page(2)->getTopArtists('luiz-pedone');
    }

    /**
     * @test
     */
    public function shouldAddPeriodToRequestUrl()
    {
        $this->client->expects($this->once())
            ->method('request')
            ->with('GET', $this->buildRequestUrl(self::API_KEY, 'luiz-pedone') . '&period=7day')
            ->willReturn($this->mockResponseInterface());

        $this->lastFmUser->period(Period::LAST_SEVEN_DAYS)->getTopArtists('luiz-pedone');
    }

    /**
     * @test
     */
    public function shouldBuildRequestWithMultipleAdditionalParameters()
    {
        $baseUrl = $this->buildRequestUrl(self::API_KEY, 'luiz-pedone');
        $expectedUrl = $baseUrl . '&period=7day&limit=10&page=2';

        $this->client->expects($this->once())
            ->method('request')
            ->with('GET', $expectedUrl)
            ->willReturn($this->mockResponseInterface());

        $this->lastFmUser->period(Period::LAST_SEVEN_DAYS)
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