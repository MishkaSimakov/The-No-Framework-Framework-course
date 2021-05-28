<?php

namespace App\Middleware;

use App\Exceptions\CsrfTokenException;
use App\Security\Csrf;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CsrfGuard implements MiddlewareInterface
{
    protected Csrf $csrf;

    public function __construct(Csrf $csrf)
    {
        $this->csrf = $csrf;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!$this->requestRequiresProtection($request)) {
            return $handler->handle($request);
        }

        if (!$this->csrf->tokenIsValid($this->getTokenFromRequest($request))) {
            throw new CsrfTokenException();
        }

        return $handler->handle($request);
    }

    protected function getTokenFromRequest(ServerRequestInterface $request)
    {
        return $request->getParsedBody()[$this->csrf->key()] ?? null;
    }

    protected function requestRequiresProtection(ServerRequestInterface $request): bool
    {
        return in_array($request->getMethod(), [
            'POST', 'PUT', 'DELETE', 'PATCH'
        ]);
    }
}