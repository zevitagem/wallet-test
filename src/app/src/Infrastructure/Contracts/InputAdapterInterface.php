<?php

namespace App\Infrastructure\Contracts;

interface InputAdapterInterface
{
    public function handle(mixed $data);
}