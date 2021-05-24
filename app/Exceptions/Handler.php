<?php

namespace App\Exceptions;

use Exception;
use ReflectionClass;

class Handler
{
    protected Exception $exception;

    public function __construct(Exception $exception)
    {
        $this->exception = $exception;
    }

    public function respond()
    {
        $class = (new ReflectionClass($this->exception))->getShortName();

        if (method_exists($this, $method = "handle$class")) {
            return $this->{$method}($this->exception);
        }

        $this->unhandledException($this->exception);
    }

    protected function handleValidationException(ValidationException $exception)
    {
        return redirect($exception->getPath());
    }

    protected function unhandledException(Exception $exception)
    {
        throw $exception;
    }
}