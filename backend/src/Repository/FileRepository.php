<?php

namespace FileHosting\Repository;

use FileHosting\Entity\File;
use PDO;

class FileRepository
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function beginTransaction() : void
    {
        $this->pdo->beginTransaction();
    }

    public function commitTransaction() : void
    {
        $this->pdo->commit();
    }

    public function abortTransaction() : void
    {
        $this->pdo->rollBack();
    }

    public function addNewFile(File $file) : File
    {
        $pdo = $this->pdo->prepare(
            'INSERT INTO files 
            (filename, type, info, hash, size, date_upload, downloads) 
            VALUES (:filename, :type, :info, :hash, :size, :date_upload, :downloads)');

        $pdo->bindValue(':filename', $file->getFilename());
        $pdo->bindValue(':type', $file->getType());
        $pdo->bindValue(':info', $file->getInfo());
        $pdo->bindValue(':hash', $file->getHash());
        $pdo->bindValue(':size', $file->getSize());
        $pdo->bindValue(':date_upload', $file->getDateUpload());
        $pdo->bindValue(':downloads', $file->getDownloads());
        $pdo->execute();

        return $file->setId($this->pdo->lastInsertId());
    }

    public function updateDownloads(File $file): bool
    {
        $pdo = $this->pdo->prepare((
            'UPDATE files SET downloads= :downloads WHERE id= :id'
        ));

        $pdo->bindValue(':downloads', $file->getDownloads());
        $pdo->bindValue(':id', $file->getId());

        return $pdo->execute();
    }

    public function getFileById($id)
    {
        $pdo = $this->pdo->prepare('SELECT * FROM files WHERE id = :id');
        $pdo->bindValue(':id', $id);
        $pdo->execute();
        $pdo->setFetchMode(\PDO::FETCH_CLASS, '\FileHosting\Entity\File');

        return $pdo->fetch();
    }

    public function getLastFiles($limit): ?array
    {
        $pdo = $this->pdo->prepare("SELECT * FROM files ORDER BY date_upload DESC LIMIT $limit");
        $pdo->execute();
        $pdo->setFetchMode(\PDO::FETCH_CLASS, '\FileHosting\Entity\File');

        return $pdo->fetchAll();
    }

    public function getFilesByIds(array $ids): ?array
    {
        $ids = implode(', ', $ids);

        $pdo = $this->pdo->prepare('SELECT id, filename FROM files WHERE id IN (:ids)');
        $pdo->bindValue(':ids', $ids);
        $pdo->execute();

        $pdo->setFetchMode(\PDO::FETCH_CLASS, '\FileHosting\Entity\File');

        return $pdo->fetchAll();
    }
}
