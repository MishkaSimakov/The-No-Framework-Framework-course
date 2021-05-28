<?php


namespace App\Security;


use App\Session\SessionStore;

class Csrf
{
    protected bool $persistToken = false;
    protected SessionStore $session;

    public function __construct(SessionStore $session)
    {
        $this->session = $session;
    }

    public function key()
    {
        return '_token';
    }
    
    public function token()
    {
        if (!$this->tokenNeedsToBeGenerated()) {
            return $this->getTokenFromSession();
        }

        $this->session->set(
            $this->key(),
            $token = bin2hex(random_bytes(32))
        );

        return $token;
    }

    public function tokenIsValid($token): bool
    {
        return $token === $this->session->get($this->key());
    }

    protected function getTokenFromSession()
    {
        return $this->session->get($this->key());
    }

    protected function tokenNeedsToBeGenerated()
    {
        if (!$this->session->exists($this->key())) {
            return true;
        }

        if ($this->shouldPersistToken()) {
            return false;
        }

        return $this->session->exists($this->key());
    }

    protected function shouldPersistToken(): bool
    {
        return $this->persistToken;
    }
}