<?php

namespace FileHosting\Repository;

use FileHosting\Entity\Comment;
use PDO;

class CommentsRepository
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function saveComment(Comment $comment) : int
    {
        $pdo = $this->pdo->prepare('INSERT INTO comments (file_id, author, message, parent_id) VALUES (:file_id, :author, :message, :parent_id)');

        $pdo->bindValue(':file_id', $comment->getFileId());
        $pdo->bindValue(':author', $comment->getAuthor());
        $pdo->bindValue(':message', $comment->getMessage());
        $pdo->bindValue(':parent_id', $comment->getParentId());

        $pdo->execute();

        return $this->pdo->lastInsertId();
    }

    public function getComments(int $file_id) : array
    {
        $pdo = $this->pdo->prepare('SELECT * FROM comments WHERE file_id = :file_id ORDER BY id ASC');

        $pdo->bindValue(':file_id', $file_id);
        $pdo->execute();
        $pdo->setFetchMode(\PDO::FETCH_CLASS, '\FileHosting\Entity\Comment');

        return $pdo->fetchAll();
    }
}
