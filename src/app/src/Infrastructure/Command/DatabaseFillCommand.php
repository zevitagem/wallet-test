<?php

namespace App\Infrastructure\Command;

use App\Infrastructure\Command\DatabaseCommand;

class DatabaseFillCommand extends DatabaseCommand
{
    public function getStrategy(): string
    {
        return 'fill';
    }
}