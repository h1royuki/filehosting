<?php

namespace FileHosting\Entity;

use JsonSerializable;

class Comment implements JsonSerializable
{
    protected $id;
    protected $file_id;
    protected $author;
    protected $message;
    protected $date;
    protected $parent_id;

    public function jsonSerialize() : array
    {
        return [
            'id'        => $this->getId(),
            'file_id'   => $this->getFileId(),
            'author'    => $this->getAuthor(),
            'message'   => $this->getMessage(),
            'date'      => $this->getDate(),
            'parent_id' => $this->getParentId(),
        ];
    }

    public function getParentId() : ?int
    {
        return $this->parent_id;
    }

    public function setParentId(?int $parent_id): self
    {
        $this->parent_id = $parent_id;

        return $this;
    }

    public function getDate() : ?string
    {
        return $this->date;
    }

    public function setDate(int $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getMessage() : ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getAuthor() : ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getId() : ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getFileId() : ?int
    {
        return $this->file_id;
    }

    public function setFileId(int $fileId): self
    {
        $this->file_id = $fileId;

        return $this;
    }
}
