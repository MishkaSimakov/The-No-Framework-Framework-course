<?php

namespace App\Exceptions;

use App\Session\SessionStore;
use Exception;
use ReflectionClass;

class Handler
{
    protected Exception $exception;
    protected SessionStore $session;

    public function __construct(Exception $exception, SessionStore $session)
    {
        $this->exception = $exception;
        $this->session = $session;
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
        $this->session->set([
            'errors' => $exception->getErrors(),
            'old' => $exception->getOldInput()
        ]);

        return redirect($exception->getPath());
    }

    protected function unhandledException(Exception $exception)
    {
        throw $exception;
    }
}