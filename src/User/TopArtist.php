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

    /**
     * @param string $name
     * @return TopArtist
     */
    public function setName(string $name): TopArtist
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param int $playCount
     * @return TopArtist
     */
    public function setPlayCount(int $playCount): TopArtist
    {
        $this->playCount = $playCount;

        return $this;
    }

    /**
     * @param string $mbid
     * @return TopArtist
     */
    public function setMbid(string $mbid): TopArtist
    {
        $this->mbid = $mbid;

        return $this;
    }

    /**
     * @param string $url
     * @return TopArtist
     */
    public function setUrl(string $url): TopArtist
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @param bool $isStreamAvailable
     * @return TopArtist
     */
    public function setIsStreamAvailable(bool $isStreamAvailable): TopArtist
    {
        $this->isStreamAvailable = $isStreamAvailable;

        return $this;
    }

    /**
     * @param array $images
     * @return TopArtist
     */
    public function setImages(array $images): TopArtist
    {
        $this->images = $this->buildImagesArray($images);

        return $this;
    }

    /**
     * @param int $ranking
     * @return TopArtist
     */
    public function setRanking(int $ranking): TopArtist
    {
        $this->ranking = $ranking;

        return $this;
    }
}