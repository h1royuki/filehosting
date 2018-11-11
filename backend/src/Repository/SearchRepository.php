<?php

namespace FileHosting\Repository;

use FileHosting\Model\File;
use PDO;

class SearchRepository
{
    private $sphinx;

    public function __construct(PDO $sphinx)
    {
        $this->sphinx = $sphinx;
    }

    public function getIdsByQuery(string $query) : ?array
    {
        $sphinx = $this->sphinx->prepare('SELECT * FROM rt_files, index_files WHERE MATCH(:query)');
        $sphinx->bindValue(':query', $query);
        $sphinx->execute();

        return $sphinx->fetchAll();
    }

    public function indexFile(File $file)
    {
        $sphinx = $this->sphinx->prepare('INSERT INTO rt_files (id, filename) VALUES (:id, :filename) ');
        $sphinx->bindValue(':id', $file->getId());
        $sphinx->bindValue(':filename', $file->getFilename());

        return $sphinx->execute();
    }
}
