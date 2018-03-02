<?php

namespace LuizPedone\LastFM\User;

use LuizPedone\LastFM\User\Entity\TopTrack;

class TopTracks extends ApiHandler
{
    const ENDPOINT = 'gettoptracks';

    public function get()
    {
        return $this->buildResponseFromBody($this->getFromLastFmApi(self::ENDPOINT));
    }

    protected function buildResponseFromBody($body)
    {
        $topTracks = [];

        foreach ($body->toptracks->track as $track) {
            $topTracks[] = $this->createTopTrackFromResponse($track);
        }

        return $topTracks;
    }

    /**
     * @param $track
     * @return TopTrack
     */
    private function createTopTrackFromResponse($track): TopTrack
    {
        $topTrack = new TopTrack();

        $topTrack->setName($track->name);

        return $topTrack;
    }
}