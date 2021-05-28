<?php

namespace App\Exceptions;

use App\Session\SessionStore;
use App\Views\View;
use Exception;
use ReflectionClass;
use Zend\Diactoros\Response;

class Handler
{
    protected Exception $exception;
    protected SessionStore $session;
    protected View $view;

    public function __construct(Exception $exception, SessionStore $session, View $view)
    {
        $this->exception = $exception;
        $this->session = $session;
        $this->view = $view;
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

    protected function handleCsrfTokenException(CsrfTokenException $exception)
    {
        return $this->view->render(
            new Response(),
            'errors/csrf.twig'
        );
    }

    protected function unhandledException(Exception $exception)
    {
        throw $exception;
    }
}