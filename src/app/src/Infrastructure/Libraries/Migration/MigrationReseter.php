<?php

namespace App\Infrastructure\Libraries\Migration;

use App\Infrastructure\Libraries\Migration\MigrationAction;

class MigrationReseter extends MigrationAction
{
    public function handle(): mixed
    {
        $manager    = $this->getManager();
        $repository = $manager->getRepository();
        $database   = $manager->getDatabase();

        $dropResult   = $repository->dropDatabase($database);
        $createResult = false;

        if ($dropResult) {
            $createResult = $repository->createDatabase($database);
        }

        return ($dropResult && $createResult);
    }

    public function can(): bool
    {
        return true;
    }
}