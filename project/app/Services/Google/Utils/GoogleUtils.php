<?php

namespace App\Services\Google\Utils;

class GoogleUtils
{
    public static function getOauthURI(): string
    {
        return env('GOOGLE_REDIRECT_OAUTH_URI');
    }

    public static function getBindURI(): string
    {
        return env('GOOGLE_REDIRECT_BIND_URI');
    }
}
