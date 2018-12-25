<?php

namespace FileHosting\Infrastructure\Helper\Info\Model;

use JsonSerializable;

class Archive implements InfoModel, JsonSerializable
{
    private $directories = [];
    private $files = [];

    public function jsonSerialize()
    {
        return $this->getInfo();
    }

    public function getInfo(): array
    {
        return [
            'directories' => $this->directories,
            'files'       => $this->files,
        ];
    }

    public function addDirectory(string $directory): self
    {
        $this->directories[] = $directory;

        return $this;
    }

    public function getDirectories(): array
    {
        return $this->directories;
    }

    public function addFile(string $key, string $value): self
    {
        $this->files[$key] = $value;

        return $this;
    }

    public function getFiles(): array
    {
        return $this->files;
    }
}
