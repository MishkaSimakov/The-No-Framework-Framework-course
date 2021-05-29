<?php

namespace App\Auth\Providers;

use App\Models\User;
use Doctrine\ORM\EntityManager;

class DatabaseProvider implements UserProvider
{
    protected EntityManager $db;

    public function __construct(EntityManager $db)
    {
        $this->db = $db;
    }

    public function getByUsername(string $username): User
    {
        return $this->db->getRepository(User::class)->findOneBy([
            'email' => $username
        ]);
    }

    public function getById(int $id): User
    {
        return $this->db->getRepository(User::class)->find($id);
    }

    public function getUserByRememberIdentifier(string $identifier): ?User
    {
        return $this->db->getRepository(User::class)->findOneBy([
            'remember_identifier' => $identifier
        ]);
    }

    public function clearUserRememberToken(int $id)
    {
        $this->db->getRepository(User::class)->find($id)->update([
            'remember_identifier' => null,
            'remember_token' => null
        ]);

        $this->db->flush();
    }

    public function updateUserPasswordHash(int $id, $hash)
    {
        $this->db->getRepository(User::class)->find($id)->update([
            'password' => $hash
        ]);

        $this->db->flush();
    }

    public function setUserRememberToken(int $id, string $identifier, string $hash)
    {
        $this->db->getRepository(User::class)->find($id)->update([
            'remember_identifier' => $identifier,
            'remember_token' => $hash
        ]);

        $this->db->flush();
    }
}