<?php

namespace LuizPedone\LastFM;


use GuzzleHttp\Client;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class ApiRequestBuilderTest extends TestCase
{
    const API_KEY = 'api-key';

    /**
     * @var MockObject
     */
    private $guzzleHttp;

    public function setUp()
    {
        $this->guzzleHttp = $this->createMock(Client::class);
    }

    /**
     * @test
     */
    public function shouldBuildGetTopArtistsRequestCorrectly()
    {
        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('getBody')->willReturn($this->lastFmApiResponse());

        $this->guzzleHttp->expects($this->once())->method('request')->with(
            'GET',
            $this->buildRequestUrl(self::API_KEY, 'luiz-pedone')
        )->willReturn($responseMock);

        $apiRequestBuilder = new ApiRequestBuilder($this->guzzleHttp, self::API_KEY);
        $apiRequestBuilder->getTopArtists('luiz-pedone');
    }

    /**
     * @test
     */
    public function shouldParseResponseCorrectly()
    {
        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('getBody')->willReturn($this->lastFmApiResponse());

        $this->guzzleHttp->method('request')->willReturn($responseMock);

        $apiRequestBuilder = new ApiRequestBuilder($this->guzzleHttp, self::API_KEY);

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

        $topTracks = $apiRequestBuilder->getTopArtists('luiz-pedone');

        $this->assertEquals($expectedTopArtists, $topTracks);
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
}
