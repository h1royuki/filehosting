<?php

namespace FileHosting\Infrastructure\Helper\Info\Model;

use JsonSerializable;

class Audio implements Model, JsonSerializable
{
    const INFO = ['dataformat', 'bitrate'];
    const TAGS = ['artist', 'title', 'album'];

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

    public function fill(array $info): Model
    {
        foreach ($info['audio'] as $key => $value) {
            if (in_array($key, self::INFO)) {
                $this->$key = $value;
            }
        }

        foreach ($info['tags']['id3v2'] as $key => $value) {
            if (in_array($key, self::TAGS)) {
                $this->$key = $value[0];
            }
        }

        return $this;
    }
}
