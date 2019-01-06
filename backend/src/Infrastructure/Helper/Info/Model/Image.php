<?php

namespace FileHosting\Infrastructure\Helper\Info\Model;

use JsonSerializable;

class Image implements InfoModel, JsonSerializable
{
    private $width;
    private $height;
    private $format;

    public function jsonSerialize()
    {
        return $this->getInfo();
    }

    public function getInfo(): array
    {
        return [
            'width'  => $this->width,
            'height' => $this->height,
            'format' => $this->format,
        ];
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

    public function setFormat($format): self
    {
        $this->format = $format;

        return $this;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function getFormat()
    {
        return $this->format;
    }
}
