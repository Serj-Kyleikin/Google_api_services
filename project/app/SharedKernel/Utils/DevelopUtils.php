<?php

namespace App\SharedKernel\Utils;

class DevelopUtils
{
    public static function isNotDevelop(): bool
    {
        return env('APP_ENV') != 'local';
    }
}
