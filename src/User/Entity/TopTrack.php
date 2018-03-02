<?php

namespace LuizPedone\LastFM\User\Entity;

class TopTrack
{
    /**
     * @var string
     */
    private $name;

    /**
     * @param string $name
     * @return TopTrack
     */
    public function setName(string $name): TopTrack
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}