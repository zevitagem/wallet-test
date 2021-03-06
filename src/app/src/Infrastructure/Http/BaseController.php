<?php

namespace App\Infrastructure\Http;

use App\Infrastructure\Contracts\InputAdapterInterface;
use App\Infrastructure\Traits\HTTPVerbs;
use App\Infrastructure\Adapter\RestOutputAdapter;
use App\Infrastructure\Contracts\OutputAdapterInterface;
use App\Infrastructure\Traits\Configurable;
use App\Application\Traits\AvailabilityWithService as ApplicationServiceManager;

abstract class BaseController implements InputAdapterInterface
{
    use HTTPVerbs,
        Configurable,
        ApplicationServiceManager;
    protected RestOutputAdapter $output;

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
}