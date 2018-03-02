<?php

namespace LuizPedone\LastFM\Tests;

use GuzzleHttp\Client;
use LuizPedone\LastFM\Period;
use LuizPedone\LastFM\User;
use LuizPedone\LastFM\User\Entity\TopArtist;
use LuizPedone\LastFM\User\TopArtists;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use ReflectionException;

class TopArtistsTest extends TestCase
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

        $this->lastFmUser->topArtists('luiz-pedone')->get();
    }

    /**
     * @test
     */
    public function shouldParseGetTopArtistsBasicInfoCorrectly()
    {
        $this->client->method('request')->willReturn($this->mockResponseInterface());

        $topTracks = $this->lastFmUser->topArtists('luiz-pedone')->get();

        $this->assertEquals('Les Cowboys Fringants', $topTracks[0]->getName());
        $this->assertEquals('Milton Nascimento', $topTracks[1]->getName());

        $this->assertEquals(8377, $topTracks[0]->getPlayCount());
        $this->assertEquals(4927, $topTracks[1]->getPlayCount());

        $this->assertEquals('0fb04a47-2c27-4598-9fbc-b90ac573cb97', $topTracks[0]->getMbid());
        $this->assertEquals('1bfa27e3-0376-4206-a772-4586e25a64f5', $topTracks[1]->getMbid());

        $this->assertEquals('https://www.last.fm/music/Les+Cowboys+Fringants', $topTracks[0]->getUrl());
        $this->assertEquals('https://www.last.fm/music/Milton+Nascimento', $topTracks[1]->getUrl());

        $this->assertEquals(false, $topTracks[0]->getIsStreamAvailable());
        $this->assertEquals(false, $topTracks[1]->getIsStreamAvailable());

        $this->assertEquals(1, $topTracks[0]->getRanking());
        $this->assertEquals(2, $topTracks[1]->getRanking());
    }

    /**
     * @test
     */
    public function shouldContainTopArtistsImages()
    {
        $this->client->method('request')->willReturn($this->mockResponseInterface());

        $images = [
            'small' => 'https://lastfm-img2.akamaized.net/i/u/34s/10a43c7380d74de180764777db0259fa.png',
            'medium' => 'https://lastfm-img2.akamaized.net/i/u/64s/10a43c7380d74de180764777db0259fa.png',
            'large' => 'https://lastfm-img2.akamaized.net/i/u/174s/10a43c7380d74de180764777db0259fa.png',
            'extralarge' => 'https://lastfm-img2.akamaized.net/i/u/300x300/10a43c7380d74de180764777db0259fa.png',
            'mega' => 'https://lastfm-img2.akamaized.net/i/u/300x300/10a43c7380d74de180764777db0259fa.png'
        ];

        $topTracks = $this->lastFmUser->topArtists('luiz-pedone')->get();

        $this->assertEquals($images, $topTracks[0]->getImages());
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

        $this->lastFmUser->topArtists('luiz-pedone')->limit(10)->get();
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

        $this->lastFmUser->topArtists('luiz-pedone')->page(2)->get();
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

        $this->lastFmUser->topArtists('luiz-pedone')->period(Period::LAST_SEVEN_DAYS)->get();
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

        $this->lastFmUser->topArtists('luiz-pedone')->period(Period::LAST_SEVEN_DAYS)
            ->limit(10)
            ->page(2)
            ->get();
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
        return file_get_contents(__DIR__ . '/../responses/top-artists.json');
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
