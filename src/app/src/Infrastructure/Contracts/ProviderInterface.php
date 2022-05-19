<?php

namespace App\Infrastructure\Contracts;

interface ProviderInterface
{
    public static function boot(): void;
}