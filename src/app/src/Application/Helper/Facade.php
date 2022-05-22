<?php

namespace App\Application\Helper;

class Facade
{
    public static function env(string $key)
    {
        return env($key);
    }
}