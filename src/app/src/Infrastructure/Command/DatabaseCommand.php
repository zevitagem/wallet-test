<?php

namespace App\Infrastructure\Command;

use App\Infrastructure\Contracts\InputAdapterInterface;
use App\Infrastructure\Adapter\StdoutOutputAdapter;
use App\Infrastructure\Libraries\Database\DatabaseManager;
use App\Infrastructure\Libraries\Migration\MigrationManager;
use Throwable;
use App\Infrastructure\Contracts\OutputAdapterInterface;
use App\Infrastructure\Traits\Configurable;

abstract class DatabaseCommand implements InputAdapterInterface
{
    use Configurable;

    protected OutputAdapterInterface $output;

    abstract public function getStrategy(): string;

    public function __construct()
    {
        $this->setOutputAdapter(new StdoutOutputAdapter());
        $this->configure([
            'print_output' => true
        ]);
    }

    public function setOutputAdapter(OutputAdapterInterface $output)
    {
        $this->output = $output;
    }

    public function handle()
    {
        $status         = false;
        $canPrintOutput = $this->isValidConfig('print_output');

        $type      = DatabaseManager::getType();
        $info      = DatabaseManager::getByType($type);
        $connector = $info['class'];

        try {

            $strategy = $this->getStrategy();
            $manager = new MigrationManager(
                $connector, $connector->getConfig()['DATABASE'], $type
            );

            $manager->configure(['print_output' => $canPrintOutput]);
            $result = $manager->{$strategy}();

            $status  = (!empty($result));
            $message = ($result) ? 'Operation executed successfully!' : 'An error occurred during processing, please try again';
        } catch (Throwable $exc) {
            $message = $exc->getMessage();
        }

        $toOutput = compact('status', 'message');

        if ($canPrintOutput) {
            $this->output->handle($toOutput);
        }

        return $toOutput;
    }
}