<?php

namespace FileHosting\Infrastructure\Helper\Info\Model;

use JsonSerializable;

class Archive implements Model, JsonSerializable
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

    public function fill(array $info): Model
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
