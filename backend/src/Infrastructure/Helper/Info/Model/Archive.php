<?php

namespace FileHosting\Infrastructure\Helper\Info\Model;

use JsonSerializable;

class Archive implements Info, JsonSerializable
{
    private $directories = [];
    private $files = [];
    private $max_items;

    public function __construct(int $max_items)
    {
        $this->max_items = $max_items;
    }

    public function getInfo(): array
    {
        return [
            'directories' => $this->directories,
            'files'       => $this->files,
        ];
    }

    public function jsonSerialize()
    {
        return $this->getInfo();
    }

    public function setInfo(array $info): Info
    {
        $count = 0;

        foreach ($info as $key => $item) {
            if ($count >= $this->max_items) {
                break;
            }

            if (is_array($item)) {
                $this->directories[] = $key;
            } else {
                $this->files[$key] = $item;
            }

            $count++;
        }

        return $this;
    }
}
