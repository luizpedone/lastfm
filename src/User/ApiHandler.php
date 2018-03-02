<?php

namespace LuizPedone\LastFM\User;

abstract class ApiHandler
{
    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var array
     */
    protected $additionalParameters = [];

    /**
     * @var string
     */
    protected $user;

    public function __construct($client, $apiKey, $user)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
        $this->user = $user;
    }

    protected function getFromLastFmApi($resource)
    {
        $request = $this->client->request('GET', $this->buildRequestUrl($resource, $this->user));

        return $this->parseJson($request->getBody());
    }

    protected function buildRequestUrl($method, $user)
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

    protected function parseJson($json)
    {
        return json_decode($json);
    }
}