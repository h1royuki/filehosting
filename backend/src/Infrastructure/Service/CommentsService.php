<?php

namespace FileHosting\Infrastructure\Service;

use FileHosting\Entity\Comment;
use FileHosting\Entity\Tree;
use FileHosting\Repository\CommentsRepository;

class CommentsService
{
    private $repository;

    public function __construct(CommentsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function addNewComment(Comment $comment) : Comment
    {
        $id = $this->repository->saveComment($comment);

        return $comment->setId($id);
    }

    public function getCommentsByFileId(int $file_id) : ?array
    {
        $comments = $this->repository->getComments($file_id);
        $comments = $this->createTree($comments);

        return $comments;
    }

    public function createTree(array $comments) : array
    {
        $tree = [];

        foreach ($comments as $comment) {
            if (!$comment->getParentId()) {
                $parent = new Tree($comment);
                $tree[$comment->getId()] = $parent;
            } else {
                $tree[$comment->getParentId()]->addChild($comment);
            }
        }

        return $tree;
    }
}
