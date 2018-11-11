<?php

namespace FileHosting\Validator;

use Exception;
use FileHosting\Model\Comment;

class CommentValidator
{
    private $settings;

    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    public function validate(Comment $comment): void
    {
        if (empty($comment->getMessage())) {
            throw new Exception('Empty message');
        }

        if (strlen($comment->getMessage()) > $this->settings['message_length']) {
            throw new Exception('Message too long');
        }

        if (strlen($comment->getAuthor()) > $this->settings['name_length']) {
            throw new Exception('Name too long');
        }
    }
}
