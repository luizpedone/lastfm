<?php

namespace LuizPedone\LastFM;

use GuzzleHttp\Client;

class LastFM
{
    private $apiKey;

    public function __construct($apiKey)
    {
        $this->$apiKey = $apiKey;
    }

    public function user()
    {
        return new User(new Client(), $this->apiKey);
    }
}
