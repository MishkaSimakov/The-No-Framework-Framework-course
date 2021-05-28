<?php

namespace App\Rules;

use Doctrine\ORM\EntityManager;

class ExistsRule implements Rule
{
    protected EntityManager $db;

    public function __construct(EntityManager $db)
    {
        $this->db = $db;
    }

    public function validate($field, $value, $params, $fields): bool
    {
        $result = $this->db->getRepository($params[0])
            ->findOneBy([
                $field => $value
            ]);

        return $result === null;
    }

    public function message(): string
    {
        return 'is already in use';
    }
}