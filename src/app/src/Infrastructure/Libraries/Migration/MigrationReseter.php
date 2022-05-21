<?php

namespace App\Infrastructure\Libraries\Migration;

use App\Infrastructure\Libraries\Migration\MigrationAction;
use App\Infrastructure\Providers\DatabaseProvider;

class MigrationReseter extends MigrationAction
{
    public function handle(): mixed
    {
        $manager    = $this->getManager();
        $repository = $manager->getRepository();
        $database   = $manager->getDatabase();

        $dropResult = $repository->dropDatabase($database);
        if (!$dropResult) {
            return false;
        }

        $createResult = $repository->createDatabase($database);
        if (!$createResult) {
            return false;
        }

        $databaseBoot = DatabaseProvider::boot();
        $manager->setConnection($databaseBoot['class']);

        return $manager->fill();
    }

    public function can(): bool
    {
        return true;
    }
}