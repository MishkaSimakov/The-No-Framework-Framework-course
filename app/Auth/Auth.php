<?php

namespace App\Auth;

use App\Auth\Hashing\Hasher;
use App\Cookie\CookieJar;
use App\Models\User;
use App\Session\SessionStore;
use Doctrine\ORM\EntityManager;
use Exception;

class Auth
{
    protected EntityManager $db;
    protected Hasher $hasher;
    protected SessionStore $session;
    protected Recaller $recaller;

    protected User $user;
    protected CookieJar $cookie;

    public function __construct(
        EntityManager $db,
        Hasher $hasher,
        SessionStore $session,
        Recaller $recaller,
        CookieJar $cookie
    )
    {
        $this->db = $db;
        $this->hasher = $hasher;
        $this->session = $session;
        $this->recaller = $recaller;
        $this->cookie = $cookie;
    }

    public function logout()
    {
        $this->session->clear($this->key());
    }

    public function attempt(string $username, string $password, bool $remember = false)
    {
        $user = $this->getByUsername($username);

        if (!$user || !$this->hasValidCredentials($user, $password)) {
            return false;
        }

        if ($this->needsRehash($user)) {
            $this->rehashPassword($user, $password);
        }

        $this->setUserSession($user);

        if ($remember) {
            $this->setRememberToken($user);
        }

        return true;
    }

    public function hasRecaller()
    {
        return $this->cookie->exists('remember');
    }

    public function setUserFromCookie()
    {
        [$identifier, $token] = $this->recaller->splitCookieValue(
            $this->cookie->get('remember')
        );

        $user = $this->db->getRepository(User::class)->findOneBy([
            'remember_identifier' => $identifier
        ]);

        if (!$user) {
            $this->cookie->clear('remember');
            return;
        }

        if (!$this->recaller->validateToken($token, $user->remember_token)) {
            $user->update([
                'remember_identifier' => null,
                'remember_token' => null
            ]);

            $this->db->flush();

            $this->cookie->clear('remember');

            throw new Exception();
        }

        $this->setUserSession($user);
    }

    protected function setRememberToken($user)
    {
        [$identifier, $token] = $this->recaller->generate();

        $this->cookie->set(
            'remember',
            $this->recaller->generateValueForCookie($identifier, $token)
        );

        $this->db->getRepository(User::class)->find($user->id)->update([
            'remember_identifier' => $identifier,
            'remember_token' => $this->recaller->getTokenHashForDatabase($token)
        ]);

        $this->db->flush();
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