<?php

namespace App\Rules;

interface Rule
{
    public function validate($field, $value, $params, $fields): bool;

    public function message(): string;
}