<?php

namespace App\Infrastructure\Command;

use App\Infrastructure\Contracts\InputAdapterInterface;
use App\Infrastructure\Adapter\StdoutOutputAdapter;
use App\Infrastructure\Libraries\Database\DatabaseManager;
use App\Infrastructure\Libraries\Migration\MigrationManager;
use Throwable;
use App\Infrastructure\Contracts\OutputAdapterInterface;

abstract class DatabaseCommand implements InputAdapterInterface
{
    abstract public function getStrategy(): string;
    
    public function __construct()
    {
        $this->setOutputAdapter(new StdoutOutputAdapter());
    }

    public function setOutputAdapter(OutputAdapterInterface $output)
    {
        $this->output = $output;
    }

    public function handle()
    {
        $status = false;

        $type      = DatabaseManager::getType();
        $info      = DatabaseManager::getByType($type);
        $connector = $info['class'];

        try {

            $strategy = $this->getStrategy();

            $result = (new MigrationManager(
                $connector, $connector->getConfig()['DATABASE'], $type
            ))->{$strategy}();

            $status  = (!empty($result));
            $message = ($result) 
                ? 'Operation executed successfully!'
                : 'An error occurred during processing, please try again';

        } catch (Throwable $exc) {
            $message = $exc->getMessage();
        }

        $this->output->handle(compact('status', 'message'));
    }
}