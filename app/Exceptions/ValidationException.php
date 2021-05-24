<?php

namespace App\Exceptions;

use Exception;
use Psr\Http\Message\RequestInterface;

class ValidationException extends Exception
{
    protected RequestInterface $request;
    protected array $errors;

    public function __construct(RequestInterface $request, array $errors)
    {
        $this->request = $request;
        $this->errors = $errors;
    }

    public function getPath()
    {
        return $this->request->getUri();
    }

    public function getOldInput()
    {
        return $this->request->getParsedBody();
    }

    public function getErrors()
    {
        return $this->errors;
    }
}