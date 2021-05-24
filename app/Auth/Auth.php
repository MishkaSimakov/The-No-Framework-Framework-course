<?php

namespace App\Auth;

use App\Auth\Hashing\Hasher;
use App\Models\User;
use App\Session\SessionStore;
use Doctrine\ORM\EntityManager;

class Auth
{
    protected EntityManager $db;
    protected Hasher $hasher;
    protected SessionStore $session;

    public function __construct(EntityManager $db, Hasher $hasher, SessionStore $session)
    {
        $this->db = $db;
        $this->hasher = $hasher;
        $this->session = $session;
    }

    public function attempt(string $username, string $password)
    {
        $user = $this->getByUsername($username);

        if (!$user || !$this->hasValidCredentials($user, $password)) {
            return false;
        }

        $this->setUserSession($user);

        return true;
    }

    protected function setUserSession(User $user)
    {
        $this->session->set('id', $user->id);
    }

    protected function hasValidCredentials(User $user, $password)
    {
        return $this->hasher->check($password, $user->password);
    }

    protected function getByUsername(string $username): User
    {
        return $this->db->getRepository(User::class)->findOneBy([
            'email' => $username
        ]);
    }
}