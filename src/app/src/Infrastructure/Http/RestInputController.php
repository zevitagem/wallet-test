<?php

namespace App\Infrastructure\Http;

use App\Infrastructure\Contracts\InputAdapterInterface;
use App\Infrastructure\Traits\HTTPVerbs;
use App\Infrastructure\Command\DatabaseResetCommand;
use App\Infrastructure\Adapter\RestOutputAdapter;
use App\Infrastructure\Contracts\OutputAdapterInterface;
use App\Infrastructure\Traits\Configurable;
use App\Infrastructure\Libraries\Router;
use App\Infrastructure\Http\BaseController;

class RestInputController extends BaseController
{
    public function handle()
    {
        $data = $this->getConfig();

        $className = ucfirst($data['preAction'])."Controller";
        $file      = "../src/Infrastructure/Http/$className.php";

        if (!file_exists($file)) {
            return $this->output->handle([
                    'status' => false,
                    'message' => sprintf('Could not resolve request with this controller sent: "%s"',
                        $data['preAction'])
            ]);
        }

        include_once $file;
        $fullClassName = 'App\\Infrastructure\\Http\\'.$className;
        $instance      = new $fullClassName;

        $router = new Router($instance);
        $router->handle();
    }
}