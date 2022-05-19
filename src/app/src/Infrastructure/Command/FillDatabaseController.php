<?php

namespace App\Infrastructure\Command;

use App\Infrastructure\Contracts\InputAdapterInterface;
use App\Infrastructure\Adapter\StdoutOutputAdapter;
use App\Infrastructure\Libraries\Database\DatabaseManager;
use App\Infrastructure\Libraries\Migration;
use Throwable;

class FillDatabaseController implements InputAdapterInterface
{
    public function __construct()
    {
        $this->output = new StdoutOutputAdapter();
    }

    public function handle()
    {
        $status = false;

        $type      = DatabaseManager::getType();
        $info      = DatabaseManager::getByType($type);
        $connector = $info['class'];

        try {
                (new Migration(
                    $connector, $connector->getConfig()['DATABASE'], $type
                ))->fill();

            $message = 'Operation executed successfully!';
            $status  = true;
        } catch (Throwable $exc) {
            $message = $exc->getMessage();
        }

        $this->output->handle(compact('status', 'message'));
    }
}