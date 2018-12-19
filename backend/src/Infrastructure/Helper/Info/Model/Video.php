<?php

namespace FileHosting\Infrastructure\Helper\Info\Model;

use JsonSerializable;

class Video implements Info, JsonSerializable
{
    private $bitrate;
    private $framerate;
    private $width;
    private $height;

    public function jsonSerialize()
    {
        return $this->getInfo();
    }

    public function getInfo(): array
    {
        return [
            'bitrate' => $this->bitrate,
            'framerate' => $this->framerate,
            'width' => $this->width,
            'height' => $this->height
        ];
    }

    public function setInfo(array $info): Info
    {
        $this->bitrate = $info['bitrate'];
        $this->framerate = $info['video']['frame_rate'];
        $this->width = $info['video']['resolution_x'];
        $this->height = $info['video']['resolution_y'];

        return $this;

    }

}