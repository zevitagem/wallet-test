<?php

namespace App\Infrastructure\Command;

use App\Infrastructure\Command\DatabaseCommand;

class DatabaseResetCommand extends DatabaseCommand
{
    public function getStrategy(): string
    {
        return 'reset';
    }
}