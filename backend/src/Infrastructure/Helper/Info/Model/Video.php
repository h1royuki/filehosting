<?php

namespace FileHosting\Infrastructure\Helper\Info\Model;

use JsonSerializable;

class Video implements InfoModel, JsonSerializable
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
            'bitrate'   => $this->bitrate,
            'framerate' => $this->framerate,
            'width'     => $this->width,
            'height'    => $this->height,
        ];
    }

    public function setBitrate($bitrate): self
    {
        $this->bitrate = $bitrate;
        return $this;
    }

    public function setFramerate($framerate): self
    {
        $this->framerate = $framerate;
        return $this;
    }

    public function setWidth($width): self
    {
        $this->width = $width;
        return $this;
    }

    public function setHeight($height): self
    {
        $this->height = $height;
        return $this;
    }

    public function getBitrate()
    {
        return $this->bitrate;
    }

    public function getFramerate()
    {
        return $this->framerate;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function getHeight()
    {
        return $this->height;
    }

}
