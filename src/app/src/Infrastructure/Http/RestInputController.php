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
use App\Application\Exceptions\ValidatorException;
use App\Application\Exceptions\ResourceNotFoundException;

class RestInputController extends BaseController
{
    public function handle()
    {
        try {

            $data           = $this->getConfig();
            $controllerName = $this->getControllerName();

            $className = $controllerName."Controller";
            $file      = "../src/Infrastructure/Http/{$className}.php";

            if (!file_exists($file)) {
                return $this->output->handle([
                        'status' => false,
                        'message' => sprintf('Could not resolve request with this controller sent: "%s"',
                            $controllerName)
                ]);
            }

            include_once $file;
            $fullClassName = 'App\\Infrastructure\\Http\\'.$className;
            $instance      = new $fullClassName;

            $router = new Router($instance);
            $router->catchException(false);
            $router->handle();
            
        } catch (ValidatorException $exc) {

            $this->output->handle([
                'status' => false,
                'message' => json_decode($exc->getMessage())
            ]);
            
        } catch (ResourceNotFoundException $exc) {

            $this->output->header(404);
            echo $exc->getMessage();

        } catch (\Throwable $exc) {

            $this->output->handle([
                'status' => false,
                'message' => $exc->getMessage()
            ]);
        }
    }

    private function getControllerName(): string
    {
        $data = $this->getConfig();

        $controller = $data['preAction'];

        return ucfirst($controller);
    }
}