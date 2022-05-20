<?php

namespace App\Infrastructure\Libraries\Migration;

use App\Infrastructure\Repositories\DatabaseRepository;
use App\Infrastructure\Contracts\Adapter\Database\SimpleDatabaseInterface;
use App\Infrastructure\Libraries\Migration\MigrationFiller;
use App\Infrastructure\Libraries\Migration\MigrationReseter;
use \App\Infrastructure\Traits\AvailabilityWithDependencie;

class MigrationManager
{
    use AvailabilityWithDependencie;

    public function __construct(
        private SimpleDatabaseInterface $conector,
        private string $database,
        private string $connectionType
    )
    {
        $this->connection         = $conector->getConnection();
        $this->databaseRepository = new DatabaseRepository();

        $this->setDependencie('filler', new MigrationFiller($this));
        $this->setDependencie('reseter', new MigrationReseter($this));
    }

    public function getDatabase(): string
    {
        return $this->database;
    }

    public function getRepository(): DatabaseRepository
    {
        return $this->databaseRepository;
    }

    public function getConnectionType(): string
    {
        return $this->connectionType;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function fill(): mixed
    {
        return $this->getDependencie('filler')->handle();
    }

    public function reset(): mixed
    {
        return $this->getDependencie('reseter')->handle();
    }
}