<?php

namespace FileHosting\Infrastructure\Helper\Info\Model;

use JsonSerializable;

class Image implements Model, JsonSerializable
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

    public function fill(array $info): Model
    {
        $this->width = $info['video']['resolution_x'];
        $this->height = $info['video']['resolution_y'];
        $this->format = $info['fileformat'];

        return $this;
    }
}
