<?php

namespace App\Auth\Providers;

use App\Models\User;

interface UserProvider
{
    public function getByUsername(string $username): User;

    public function getById(int $id): User;

    public function getUserByRememberIdentifier(string $identifier): ?User;

    public function clearUserRememberToken(int $id);

    public function updateUserPasswordHash(int $id, $hash);

    public function setUserRememberToken(int $id, string $identifier, string $hash);
}