<?php

namespace LuizPedone\LastFM\User;

use LuizPedone\LastFM\User\Entity\TopArtist;

class TopArtists extends ApiHandler
{
    const ENDPOINT = 'gettopartists';

    public function get()
    {
        return $this->buildResponseFromBody($this->getFromLastFmApi(self::ENDPOINT));
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

    protected function buildResponseFromBody($body)
    {
        $topArtists = [];

        foreach ($body->topartists->artist as $artist) {
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
