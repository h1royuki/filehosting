<?php

namespace FileHosting\Model;

use JsonSerializable;
use Slim\Http\UploadedFile;

class File implements JsonSerializable
{

    const DATE_FORMAT = 'Y-m-d H:i:s';

    const AUDIO_TYPE = '1';
    const VIDEO_TYPE = '2';
    const IMAGE_TYPE = '3';
    const OTHER_TYPE = '0';

    protected $id;
    protected $file;
    protected $filename;
    protected $type;
    protected $info;
    protected $hash;
    protected $size;
    protected $downloads;
    protected $date_upload;

    public function jsonSerialize() : array
    {
        return [
            'id'          => $this->getId(),
            'filename'    => $this->getFilename(),
            'type'        => $this->getType(),
            'info'        => $this->getDecodedInfo(),
            'size'        => $this->getSize(),
            'downloads'   => $this->getDownloads(),
            'date_upload' => $this->getDateUpload(),
        ];
    }

    public function getFilename() : ?string
    {
        return $this->filename;
    }

    public function getSize() : ?int
    {
        return $this->size;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function getInfo() : ?string
    {
        return $this->info;
    }

    public function getDecodedInfo()
    {
        return json_decode($this->info);
    }

    public function getId() : ?int
    {
        return $this->id;
    }

    public function getFile() : ?UploadedFile
    {
        return $this->file;
    }

    public function getDateUpload() : ?string
    {
        return $this->date_upload;
    }

    public function getDownloads() : ?int
    {
        return $this->downloads;
    }

    public function getHash() : ?string
    {
        return $this->hash;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function setInfo(array $info): self
    {
        $this->info = json_encode($info);

        return $this;
    }

    public function setDownloads(int $count) : self
    {
        $this->downloads = $count;

        return $this;
    }

    public function setFile(UploadedFile $file) : self
    {
        $this->file = $file;

        return $this;
    }

    public function setFilename(string $filename) : self
    {
        $this->filename = $filename;

        return $this;
    }

    public function setHash(string $hash) : self
    {
        $this->hash = $hash;

        return $this;
    }

    public function setSize(string $size) : self
    {
        $this->size = $size;

        return $this;
    }

    public function setDateUpload(string $date_upload) : self
    {
        $this->date_upload = $date_upload;

        return $this;
    }
}
