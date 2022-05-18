<?php

namespace App\Application\Helper;

class Facade
{
    public static function env(string $key)
    {
        $envs = parse_ini_file('../.env');

        return $envs[$key] ?? null;
    }

    public static function numberFormat(float $value)
    {
        return number_format($value, 2, '.', '');
    }
}