<?php

namespace App\Infrastructure\Libraries\Migration;

use App\Infrastructure\Libraries\Migration\MigrationAction;

final class MigrationFiller extends MigrationAction
{
    private array $files = [
        'tables.php',
        'seeders.php'
    ];

    public function handle(): mixed
    {
        if (!$this->can()) {
            return false;
        }

        $connection = $this->getManager()->getConnection();
        foreach ($this->getFiles() as $row) {

            include_once $row;
            if (empty($queries)) {
                continue;
            }

            $this->out('Processing:'.$row);
            foreach ($queries as $query) {
                $connection->query($query);
            }
        }

        return true;
    }

    public function can(): bool
    {
        $manager = $this->getManager();
        $result  = $manager->getRepository()->getTotalTables(
            $manager->getDatabase()
        );

        if ($result > 0) {
            throw new \RuntimeException('A populated database already exists, this action will be rejected');
        }

        return true;
    }

    private function getFiles(): array
    {
        $connectionType = $this->getManager()->getConnectionType();

        return array_filter(array_map(function ($file) use ($connectionType) {
            $path = "../database/{$connectionType}/{$file}";
            return (file_exists($path)) ? $path : null;
        }, $this->files));
    }
}