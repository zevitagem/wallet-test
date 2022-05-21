<?php

namespace App\Infrastructure\Http;

use App\Infrastructure\Http\BaseController;
use App\Infrastructure\Command\DatabaseResetCommand;

class ResetController extends BaseController
{
    public function index()
    {
        $this->mustPost();

        $command = new DatabaseResetCommand();
        $command->setOutputAdapter($this->output);
        $command->configure([
            'print_output' => false
        ]);

        $result = $command->handle();

        if ($result['status'] == false) {
            return $this->output->handle($result, 500);
        }

        $this->output->httpCode(200);
        echo 'OK';
    }
}