<?php

namespace FileHosting\Infrastructure\Helper\Info\Model;

use JsonSerializable;

class Audio implements InfoModel, JsonSerializable
{

    private $bitrate;
    private $artist;
    private $album;
    private $title;
    private $format;

    public function jsonSerialize()
    {
        return $this->getInfo();
    }

    public function getInfo(): array
    {
        return [
            'bitrate' => $this->bitrate,
            'artist'  => $this->artist,
            'album'   => $this->album,
            'title'   => $this->title,
            'format'  => $this->format,
        ];
    }

    public function setBitrate($bitrate): self
    {
        $this->bitrate = $bitrate;

        return $this;
    }

    public function setArtist($artist): self
    {
        $this->artist = $artist;

        return $this;
    }

    public function setAlbum($album): self
    {
        $this->album = $album;

        return $this;
    }

    public function setTitle($title): self
    {
        $this->title = $title;

        return $this;
    }

    public function setFormat($format): self
    {
        $this->format = $format;

        return $this;
    }

    public function getBitrate()
    {
        return $this->bitrate;
    }

    public function getArtist()
    {
        return $this->artist;
    }

    public function getAlbum()
    {
        return $this->album;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getFormat()
    {
        return $this->format;
    }
}
