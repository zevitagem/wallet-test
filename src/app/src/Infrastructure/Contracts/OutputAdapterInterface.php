<?php

namespace App\Infrastructure\Contracts;

interface OutputAdapterInterface
{
    public function handle(array $data);
}