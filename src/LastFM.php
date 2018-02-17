<?php

namespace LuizPedone\LastFM;

use GuzzleHttp\Client;

class LastFM
{
    /**
     * @var User
     */
    private $user;

    public function __construct($apiKey)
    {
        $this->user = new User(new Client(), $apiKey);
    }

    public function user()
    {
        return $this->user;
    }
}
