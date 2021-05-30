<?php

namespace App\Rules;

use App\Models\User;

class ExistsRule implements Rule
{
    public function validate($field, $value, $params, $fields): bool
    {
        $result = User::where($field, $value)->first();

        return $result === null;
    }

    public function message(): string
    {
        return 'is already in use';
    }
}