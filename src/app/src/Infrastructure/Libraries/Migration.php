<?php

namespace App\Infrastructure\Libraries;

use App\Infrastructure\Repositories\DatabaseRepository;
use App\Infrastructure\Contracts\Adapter\Database\SimpleDatabaseInterface;

class Migration
{
    private array $files = [
        'tables.php',
        'seeders.php'
    ];

    public function __construct(
        private SimpleDatabaseInterface $conector,
        private string $database,
        private string $connectionType
    )
    {
        $this->connection         = $conector->getConnection();
        $this->databaseRepository = new DatabaseRepository();
    }

    public function getDatabase(): string
    {
        return $this->database;
    }

    public function getConnectionType(): string
    {
        return $this->connectionType;
    }

    public function fill(): void
    {
        if (!$this->canFill()) {
            return;
        }

        foreach ($this->getFiles() as $row) {

            include_once $row;
            if (empty($queries)) {
                continue;
            }

            echo 'Processing:'.$row.PHP_EOL;
            foreach ($queries as $query) {
                $this->connection->query($query);
            }
        }
    }

    private function canFill(): bool
    {
        $result = $this->databaseRepository->getTotalTables(
            $this->getDatabase()
        );

        if ($result > 0) {
            throw new \RuntimeException('A populated database already exists, this action will be rejected');
        }

        return true;
    }

    private function getFiles(): array
    {
        return array_filter(array_map(function ($file) {
            $path = "../src/Infrastructure/Migrations/Database/{$this->getConnectionType()}/$file";
            return (file_exists($path)) ? $path : null;
        }, $this->files));
    }
}