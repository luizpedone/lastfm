<?php

namespace LuizPedone\LastFM\Tests;

use GuzzleHttp\Client;
use LuizPedone\LastFM\User;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class TopTracksTest extends TestCase
{
    const API_KEY = 'api-key';
    const USER_NAME = 'luiz-pedone';

    /**
     * @var MockObject
     */
    private $client;

    /**
     * @var \LuizPedone\LastFM\User
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

        $this->lastFmUser->topTracks('luiz-pedone')->get();
    }

    /**
     * @test
     */
    public function shouldParseGetTopArtistsBasicInfoCorrectly()
    {
        $this->client->method('request')->willReturn($this->mockResponseInterface());

        $topTracks = $this->lastFmUser->topTracks('luiz-pedone')->get();

        $this->assertEquals('Les Étoiles Filantes', $topTracks[0]->getName());
        $this->assertEquals('Plus rien', $topTracks[1]->getName());
    }

    private function buildRequestUrl($apiKey, $user)
    {
        return sprintf(
            'http://ws.audioscrobbler.com/2.0/?method=user.gettoptracks&user=%s&api_key=%s&format=json',
            $user,
            $apiKey
        );
    }

    private function lastFmApiResponse()
    {
        return file_get_contents(__DIR__ . '/../responses/top-tracks.json');
    }

    /**
     * @return MockObject
     * @throws \ReflectionException
     */
    public function mockResponseInterface(): MockObject
    {
        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('getBody')->willReturn($this->lastFmApiResponse());
        return $responseMock;
    }
}