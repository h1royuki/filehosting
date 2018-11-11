<?php

namespace FileHosting\Controller;

use FileHosting\Helper\CommentsHelper;
use FileHosting\Model\Comment;
use FileHosting\Repository\CommentsRepository;
use FileHosting\Validator\CommentValidator;
use Slim\Http\Request;
use Slim\Http\Response;

class CommentsController
{
    private $repository;
    private $helper;
    private $validator;

    public function __construct(CommentValidator $validator, CommentsHelper $helper, CommentsRepository $repository)
    {
        $this->repository = $repository;
        $this->helper = $helper;
        $this->validator = $validator;
    }

    public function view(Request $request, Response $response, $args): Response
    {
        $id = $args['id'];

        $comments = $this->helper->getCommentsByFileId($id);

        return $response->withJson($comments);
    }

    public function add(Request $request, Response $response, array $args): Response
    {
        $post = $request->getParsedBody();

        $comment = new Comment();
        $comment = $this->helper->parseRequest($post, $comment);
        $this->validator->validate($comment);

        $comment = $this->helper->addNewComment($comment);

        return $response->withJson($comment);
    }
}
