<?php

namespace App\Infrastructure\Libraries;

use App\Infrastructure\Contracts\InputAdapterInterface;

class Router
{
    public function __construct(private InputAdapterInterface $controller)
    {
    }

    public function handle()
    {
        try {
            $action = $this->getAction();

            if (empty($action) || $action === 'index') {
                return $this->controller->index();
            }

            if (!method_exists($this->controller, $action)) {
                throw new \InvalidArgumentException(
                        sprintf('Could not resolve request with this method sent: "%s"',
                            $action)
                );
            }

            $this->controller->{$action}();
        } catch (\Throwable $exc) {
            die(
                json_encode([
                    'status' => false,
                    'message' => $exc->getMessage()
                ])
            );
        }
    }

    private function getAction(): string
    {
        $uri = $_SERVER['REQUEST_URI'];

        $levels = explode('/', $uri);

        end($levels);

        return current($levels);
    }
}