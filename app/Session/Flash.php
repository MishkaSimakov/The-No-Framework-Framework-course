<?php

namespace App\Session;

class Flash
{
    protected SessionStore $session;

    protected $messages;

    public function __construct(SessionStore $session)
    {
        $this->session = $session;

        $this->loadFlashMessagesIntoCache();

        $this->clear();
    }

    public function has($key)
    {
        return isset($this->messages[$key]);
    }

    public function get($key)
    {
        if ($this->has($key)) {
            return $this->messages[$key];
        }

        return null;
    }

    public function now($key, $value)
    {
        $this->session->set($this->sessionKey(), array_merge(
            $this->session->get($this->sessionKey()) ?? [],
            [$key => $value]
        ));
    }

    protected function clear()
    {
        $this->session->clear($this->sessionKey());
    }

    protected function loadFlashMessagesIntoCache()
    {
        $this->messages = $this->getAll();
    }

    protected function getAll()
    {
        return $this->session->get($this->sessionKey());
    }

    protected function sessionKey(): string
    {
        return 'flash';
    }
}