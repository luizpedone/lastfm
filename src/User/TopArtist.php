<?php

namespace LuizPedone\LastFM\User;

class TopArtist
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $playCount;

    /**
     * @var string
     */
    private $mbid;

    /**
     * @var string
     */
    private $url;

    /**
     * @var boolean
     */
    private $isStreamAvailable;

    /**
     * @var array
     */
    private $images;

    /**
     * @var int
     */
    private $ranking;

    /**
     * TopArtist constructor.
     * @param $name
     * @param $playCount
     * @param $mbid
     * @param $url
     * @param $isStreamAvailable
     * @param $ranking
     */
    public function __construct($name, $playCount, $mbid, $url, $isStreamAvailable, $images, $ranking)
    {
        $this->name = $name;
        $this->playCount = $playCount;
        $this->mbid = $mbid;
        $this->url = $url;
        $this->isStreamAvailable = (boolean) $isStreamAvailable;
        $this->images = $this->buildImagesArray($images);
        $this->ranking = $ranking;
    }

    private function buildImagesArray($images)
    {
        $imagesResponse = [];

        foreach ($images as $image) {
            $imagesResponse[$image->size] = $image->{'#text'};
        }

        return $imagesResponse;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getPlayCount(): int
    {
        return $this->playCount;
    }

    /**
     * @return string
     */
    public function getMbid(): string
    {
        return $this->mbid;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return bool
     */
    public function getIsStreamAvailable(): bool
    {
        return $this->isStreamAvailable;
    }

    /**
     * @return int
     */
    public function getRanking(): int
    {
        return $this->ranking;
    }

    /**
     * @return array
     */
    public function getImages(): array
    {
        return $this->images;
    }
}