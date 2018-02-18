<?php

namespace LuizPedone\LastFM;

use GuzzleHttp\Client;
use LuizPedone\LastFM\User\TopArtists;

class User
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $apiKey;

    public function __construct(Client $client, $apiKey)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
    }

    public function topArtists($user)
    {
        return new TopArtists($this->client, $this->apiKey, $user);
    }
}