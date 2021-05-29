<?php

namespace App\Auth;

class Recaller
{
    protected string $separator = '|';

    public function generate(): array
    {
        return [$this->generateIdentifier(), $this->generateToken()];
    }

    public function generateValueForCookie(string $identifier, string $token): string
    {
        return $identifier . $this->separator . $token;
    }

    public function splitCookieValue($value): array
    {
        return explode($this->separator, $value);
    }

    public function validateToken(string $plain, string $hash): bool
    {
        return $this->getTokenHashForDatabase($plain) === $hash;
    }

    public function getTokenHashForDatabase(string $token)
    {
        return hash('sha256', $token);
    }

    protected function generateIdentifier(): string
    {
        return bin2hex(random_bytes(32));
    }

    protected function generateToken(): string
    {
        return bin2hex(random_bytes(64));
    }
}