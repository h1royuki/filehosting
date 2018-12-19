<?php

namespace FileHosting\Controller;

use DateTime;
use FileHosting\Entity\Comment;
use FileHosting\Infrastructure\Service\CommentsService;
use FileHosting\Repository\CommentsRepository;
use FileHosting\Validator\CommentValidator;
use Slim\Http\Request;
use Slim\Http\Response;

class CommentsController
{
    private $repository;
    private $service;
    private $validator;

    public function __construct(CommentValidator $validator, CommentsService $service, CommentsRepository $repository)
    {
        $this->repository = $repository;
        $this->service = $service;
        $this->validator = $validator;
    }

    public function view(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];

        $comments = $this->service->getCommentsByFileId($id);

        return $response->withJson($comments);
    }

    public function add(Request $request, Response $response): Response
    {
        $comment = $this->deserialize($request);

        $this->validator->validate($comment);

        $comment = $this->service->addNewComment($comment);

        return $response->withJson($comment);
    }

    private function deserialize(Request $request): Comment
    {
        $request = $request->getParsedBody();

        $file = $request['file_id'];
        $author = $request['author'] ? $request['author'] : 'Anonymous';
        $parent_id = $request['parent_id'] ? $request['parent_id'] : null;
        $message = $request['message'];
        $date = new DateTime();

        return (new Comment())
            ->setFileId($file)
            ->setAuthor($author)
            ->setMessage($message)
            ->setDate($date->getTimestamp())
            ->setParentId($parent_id);
    }
}
