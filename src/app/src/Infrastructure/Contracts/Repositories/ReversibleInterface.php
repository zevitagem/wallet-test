<?php

namespace App\Infrastructure\Contracts\Repositories;

interface ReversibleInterface
{
    public function beginTransaction(): void;

    public function commit(): void;

    public function rollBack(): void;
}