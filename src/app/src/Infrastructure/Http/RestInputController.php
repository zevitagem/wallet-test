<?php

namespace App\Infrastructure\Http;

use App\Infrastructure\Contracts\InputAdapterInterface;
use App\Infrastructure\Traits\HTTPVerbs;
use App\Infrastructure\Command\DatabaseResetCommand;
use App\Infrastructure\Adapter\RestOutputAdapter;
use App\Infrastructure\Contracts\OutputAdapterInterface;

class RestInputController implements InputAdapterInterface
{
    use HTTPVerbs;
    
    private RestOutputAdapter $output;

    public function __construct()
    {
        $this->output = new RestOutputAdapter();
    }

    public function handle()
    {
        // do nothinhg;
    }

    public function setOutputAdapter(OutputAdapterInterface $output)
    {
        return null;
    }

    public function handleGet()
    {
        $this->mustGet();

        echo 'vou fazer';
    }

    public function handlePost()
    {
        $this->mustPost();

        echo 'vou fazer';
    }

    public function reset()
    {
        $this->mustPost();

        $command = new DatabaseResetCommand();
        $command->setOutputAdapter($this->output);
        $command->handle();
    }
}