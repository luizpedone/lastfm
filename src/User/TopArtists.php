<?php
namespace LuizPedone\LastFM\User;

class TopArtists
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

    /**
     * @var array
     */
    private $additionalParameters = [];

    /**
     * @var string
     */
    private $user;

    public function __construct($client, $apiKey, $user)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
        $this->user = $user;
    }

    public function get()
    {
        $topTracks = $this->client->request('GET', $this->buildRequestUrl(self::TOP_ARTISTS, $this->user));

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
            $topArtists[] = $this->createTopArtistFromResponse($artist);
        }

        return $topArtists;
    }

    /**
     * @param $artist
     * @return TopArtist
     */
    private function createTopArtistFromResponse($artist): TopArtist
    {
        $topArtist = new TopArtist();

        $topArtist->setName($artist->name)
            ->setPlayCount($artist->playcount)
            ->setMbid($artist->mbid)
            ->setUrl($artist->url)
            ->setIsStreamAvailable($artist->streamable)
            ->setImages($artist->image)
            ->setRanking($artist->{'@attr'}->rank);

        return $topArtist;
    }
}