<?php

namespace FileHosting\Exception;

use Exception;

class ValidationException extends Exception {

    private $errors;

    public function __construct(array $errors)
    {
        $this->errors = $errors;
    }

    public function getErrors(): array {
        return $this->errors;
    }
}
