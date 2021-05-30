<?php

namespace App\Auth\Providers;

use App\Models\User;

class DatabaseProvider implements UserProvider
{
    public function getByUsername(string $username): User
    {
        return User::where('email', $username)->first();
    }

    public function getById(int $id): User
    {
        return User::find($id);
    }

    public function getUserByRememberIdentifier(string $identifier): ?User
    {
        return User::where('remember_identifier', $identifier)->first();
    }

    public function clearUserRememberToken(int $id)
    {
        User::find($id)->update([
            'remember_identifier' => null,
            'remember_token' => null
        ]);
    }

    public function updateUserPasswordHash(int $id, $hash)
    {
        User::find($id)->update([
            'password' => $hash
        ]);
    }

    public function setUserRememberToken(int $id, string $identifier, string $hash)
    {
        User::find($id)->update([
            'remember_identifier' => $identifier,
            'remember_token' => $hash
        ]);
    }
}