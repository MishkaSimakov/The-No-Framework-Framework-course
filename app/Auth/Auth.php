<?php

namespace App\Auth;

use App\Auth\Hashing\Hasher;
use App\Auth\Providers\UserProvider;
use App\Cookie\CookieJar;
use App\Models\User;
use App\Session\SessionStore;
use Exception;

class Auth
{
    protected Hasher $hash;
    protected SessionStore $session;
    protected Recaller $recaller;

    protected User $user;
    protected UserProvider $userProvider;
    protected CookieJar $cookie;

    public function __construct(
        Hasher $hash,
        SessionStore $session,
        Recaller $recaller,
        CookieJar $cookie,

        UserProvider $userProvider
    )
    {
        $this->hash = $hash;
        $this->session = $session;
        $this->recaller = $recaller;
        $this->cookie = $cookie;

        $this->userProvider = $userProvider;
    }

    public function logout()
    {
        $this->session->clear('id');

        $this->userProvider->clearUserRememberToken($this->user->id);
        $this->cookie->clear('id');
    }

    public function attempt(string $username, string $password, bool $remember = false)
    {
        $user = $this->userProvider->getByUsername($username);

        if (!$user || !$this->hasValidCredentials($user, $password)) {
            return false;
        }

        if ($this->needsRehash($user)) {
            $this->userProvider->updateUserPasswordHash($user->id, $this->hash->create($password));
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

        if (!$user = $this->userProvider->getUserByRememberIdentifier($identifier)) {
            $this->cookie->clear('remember');
            return;
        }

        if (!$this->recaller->validateToken($token, $user->remember_token)) {
            $this->userProvider->clearUserRememberToken($user->id);
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

        $this->userProvider->setUserRememberToken(
            $user->id,
            $identifier,
            $this->recaller->getTokenHashForDatabase($token)
        );
    }

    protected function needsRehash(User $user)
    {
        return $this->hash->needsRehash($user->password);
    }

    public function user()
    {
        return $this->user;
    }

    public function hasUserInSession()
    {
        return $this->session->exists('id');
    }

    public function check()
    {
        return $this->hasUserInSession();
    }

    public function setUserFromSession()
    {
        $user = $this->userProvider->getById($this->session->get('id'));

        if (!$user) {
            throw new Exception();
        }

        $this->user = $user;
    }

    protected function setUserSession(User $user)
    {
        $this->session->set('id', $user->id);
    }

    protected function hasValidCredentials(User $user, $password)
    {
        return $this->hash->check($password, $user->password);
    }
}