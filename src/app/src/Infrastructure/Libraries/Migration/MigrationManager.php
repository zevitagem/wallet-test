<?php

namespace App\Infrastructure\Libraries\Migration;

use App\Infrastructure\Contracts\Adapter\Database\SimpleDatabaseInterface;
use App\Infrastructure\Libraries\Migration\MigrationFiller;
use App\Infrastructure\Libraries\Migration\MigrationReseter;
use App\Infrastructure\Traits\AvailabilityWithDependencie;
use App\Infrastructure\Factory\DatabaseRepositoryFactory;
use App\Infrastructure\Contracts\Repositories\DatabaseLevelInterface;
use App\Infrastructure\Traits\Configurable;

class MigrationManager
{
    use AvailabilityWithDependencie,
        Configurable;
    
    private mixed $connection;

    public function __construct(
        private SimpleDatabaseInterface $conector,
        private string $database,
        private string $connectionType
    )
    {
        self::setConnection($conector);

        $this->setDependencie('filler', new MigrationFiller($this));
        $this->setDependencie('reseter', new MigrationReseter($this));
    }

    public function setConnection(SimpleDatabaseInterface $conector)
    {
        $this->connection = $conector->getConnection();
    }

    public function getDatabase(): string
    {
        return $this->database;
    }

    public function getRepository(): DatabaseLevelInterface
    {
        return DatabaseRepositoryFactory::newDatabase($this->getConnectionType());
    }

    public function getConnectionType(): string
    {
        return $this->connectionType;
    }

    public function getConnection(): mixed
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