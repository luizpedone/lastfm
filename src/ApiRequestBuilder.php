<?php

namespace LuizPedone\LastFM;

use GuzzleHttp\Client;

class ApiRequestBuilder
{
    const TOP_ARTISTS = 'gettopartists';
    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $apiKey;

    public function __construct(Client $client, string $apiKey)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
    }

    public function getTopArtists($user)
    {
        $topTracks = $this->client->request('GET', $this->buildRequestUrl(self::TOP_ARTISTS, $user));

        return $this->builResponseFromBody($topTracks->getBody());
    }

    private function buildRequestUrl($method, $user)
    {
        return sprintf(
            'http://ws.audioscrobbler.com/2.0/?method=user.%s&user=%s&api_key=%s&format=json',
            $method,
            $user,
            $this->apiKey
        );
    }

    private function parseJson($json)
    {
        return json_decode($json);
    }

    private function builResponseFromBody($body)
    {
        $formattedJson = $this->parseJson($body);
        $topArtists = [];

        foreach ($formattedJson->topartists->artist as $artist) {
            $formattedArtistOutput = [
                'name' => $artist->name,
                'playcount' => $artist->playcount
            ];
            $topArtists[] = $formattedArtistOutput;
        }

        return $topArtists;
    }
}
