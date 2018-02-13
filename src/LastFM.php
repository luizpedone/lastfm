<?php

namespace LuizPedone\LastFM;

use GuzzleHttp\Client;

class LastFM
{
    /**
     * @var ApiRequestBuilder
     */
    protected $apiRequestBuilder;

    /**
     * @var string
     */
    private $apiKey;

    public function __construct($apiKey)
    {
        $this->apiRequestBuilder = new ApiRequestBuilder(new Client(), $apiKey);
        $this->apiKey = $apiKey;
    }

    public function getTopArtists($user) {
        return $this->apiRequestBuilder->getTopArtists($user);
    }
}