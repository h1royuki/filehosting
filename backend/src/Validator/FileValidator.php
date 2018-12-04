<?php

namespace FileHosting\Validator;

use FileHosting\Exception\ValidationException;
use Slim\Http\UploadedFile;

class FileValidator
{
    private $settings;

    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    public function validate(UploadedFile $file = null) : void
    {
        $errors = [];

        if (!$file) {
            $errors[] = 'File not attached';
        }

        if (empty($file->getClientFilename())) {
            $errors[] = 'Unknown file name';
        }

        if ($file->getSize() > $this->settings['max_size']) {
            $errors[] = 'File size too big';
        }

        if (!empty($this->errors)) {
            throw new ValidationException($errors);
        }
    }
}
