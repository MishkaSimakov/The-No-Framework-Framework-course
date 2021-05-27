<?php

namespace App\Auth;

use App\Auth\Hashing\Hasher;
use App\Models\User;
use App\Session\SessionStore;
use Doctrine\ORM\EntityManager;
use Exception;

class Auth
{
    protected EntityManager $db;
    protected Hasher $hasher;
    protected SessionStore $session;

    protected User $user;

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

        if ($this->needsRehash($user)) {
            $this->rehashPassword($user, $password);
        }

        $this->setUserSession($user);

        return true;
    }

    protected function needsRehash(User $user)
    {
        return $this->hasher->needsRehash($user->password);
    }

    protected function rehashPassword(User $user, string $password)
    {
        $this->db->getRepository(User::class)->find($user->id)->update([
            'password' => $this->hasher->create($password)
        ]);

        $this->db->flush();
    }

    public function user(): User
    {
        return $this->user;
    }

    public function hasUserInSession()
    {
        return $this->session->exists($this->key());
    }

    public function check()
    {
        return $this->hasUserInSession();
    }

    public function setUserFromSession()
    {
        $user = $this->getById($this->session->get($this->key()));

        if (!$user) {
            throw new Exception();
        }

        $this->user = $user;
    }

    protected function getById(int $id): User
    {
        return $this->db->getRepository(User::class)->find($id);
    }

    protected function key(): string
    {
        return 'id';
    }

    protected function setUserSession(User $user)
    {
        $this->session->set($this->key(), $user->{$this->key()});
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