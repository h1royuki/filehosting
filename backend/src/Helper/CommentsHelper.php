<?php

namespace FileHosting\Helper;

use DateTime;
use FileHosting\Model\Comment;
use FileHosting\Model\Tree;
use FileHosting\Repository\CommentsRepository;

class CommentsHelper
{
    private $repository;

    public function __construct(CommentsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function parseRequest(array $post, Comment $comment) : Comment
    {
        $file = $post['file_id'];
        $author = $post['author'] ? $post['author'] : 'Anonymous';
        $parent_id = $post['parent_id'] ? $post['parent_id'] : null;
        $message = $post['message'];
        $date = new DateTime();

        return $comment
            ->setFileId($file)
            ->setAuthor($author)
            ->setMessage($message)
            ->setDate($date->getTimestamp())
            ->setParentId($parent_id);
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
