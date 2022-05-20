<?php

namespace App\Infrastructure\Libraries;

use App\Infrastructure\Contracts\InputAdapterInterface;

class Router
{
    private string $preAction;
    private string $action;

    public function __construct(private InputAdapterInterface $controller)
    {

    }

    public function getController(): InputAdapterInterface
    {
        return $this->controller;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getPreAction(): string
    {
        return $this->preAction;
    }

    public function handle()
    {
        $extracted       = $this->extract();
        $this->action    = $extracted['action'];
        $this->preAction = $extracted['preAction'];

        try {
            $action = $this->getAction();

            if (empty($action) || $action === 'index') {
                return $this->call('index');
            }

            if (!method_exists($this->controller, $action)) {
                throw new \InvalidArgumentException(
                        sprintf('Could not resolve request with this method sent: "%s"',
                            $action)
                );
            }

            $this->call($action);
        } catch (\Throwable $exc) {
            die(
                json_encode([
                'status' => false,
                'message' => $exc->getMessage()
                ])
            );
        }
    }

    private function call(string $method)
    {
        $this
            ->getController()
            ->configure([
                'pre_action' => $this->getPreAction()])
            ->{$method}();
    }

    public function extract(): array
    {
        $uri = $_SERVER['REQUEST_URI'];

        $levels = explode('/', $uri);
        $total  = count($levels);

        $action    = $levels[$total - 1];
        $preAction = $levels[$total - 2];

        if ($preAction == 'public') {
            $preAction = 'transaction';
        }

        return compact('action', 'preAction');
    }
}