<?php

namespace FileHosting\Validator;

use FileHosting\Entity\Comment;
use FileHosting\Exception\ValidationException;

class CommentValidator
{
    private $settings;

    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    public function validate(Comment $comment): void
    {
        $errors = [];

        if (empty($comment->getMessage())) {
            $errors[] = 'Empty message';
        }

        if (strlen($comment->getMessage()) > $this->settings['message_length']) {
            $errors[] = 'Message too long';
        }

        if (strlen($comment->getAuthor()) > $this->settings['name_length']) {
            $errors[] = 'Name too long';
        }

        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
    }
}
