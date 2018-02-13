<?php

namespace LuizPedone\LastFM;

use GuzzleHttp\Client;

class LastFM
{
    const TOP_ARTISTS = 'gettopartists';

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var array
     */
    private $additionalParameters = [];

    /**
     * @var Client
     */
    private $client;

    public function __construct($apiKey)
    {
        $this->client = new Client();
        $this->apiKey = $apiKey;
    }

    public function getTopArtists($user)
    {
        $topTracks = $this->client->request('GET', $this->buildRequestUrl(self::TOP_ARTISTS, $user));

        return $this->buildResponseFromBody($topTracks->getBody());
    }

    public function limit(int $limit)
    {
        $this->additionalParameters['limit'] = $limit;

        return $this;
    }

    public function page(int $page)
    {
        $this->additionalParameters['page'] = $page;

        return $this;
    }

    public function period($period)
    {
        $this->additionalParameters['period'] = $period;

        return $this;
    }

    private function buildRequestUrl($method, $user)
    {
        $baseUrl = 'http://ws.audioscrobbler.com/2.0/?method=user.%s&user=%s&api_key=%s&format=json';

        if (sizeof($this->additionalParameters) > 0) {
            $baseUrl = $baseUrl . '&' . http_build_query($this->additionalParameters);
        }

        return sprintf(
            $baseUrl,
            $method,
            $user,
            $this->apiKey
        );
    }

    private function parseJson($json)
    {
        return json_decode($json);
    }

    private function buildResponseFromBody($body)
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
