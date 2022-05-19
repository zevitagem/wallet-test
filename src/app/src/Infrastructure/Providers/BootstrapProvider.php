<?php

namespace App\Infrastructure\Providers;

use App\Infrastructure\Providers\DatabaseProvider;
use App\Infrastructure\Contracts\ProviderInterface;

class BootstrapProvider implements ProviderInterface
{
    public static function boot(): void
    {
        foreach ([
            DatabaseProvider::class
        ] as $provider) {
            $provider::boot();
        }
    }
}