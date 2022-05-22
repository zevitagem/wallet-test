<?php

namespace App\Infrastructure\Libraries;

use App\Infrastructure\Contracts\InputAdapterInterface;

class Router
{
    private string $preAction;
    private string $action;
    private bool $hasCatchException = true;

    public function __construct(private InputAdapterInterface $controller)
    {
    }

    public function catchException(bool $value): void
    {
        $this->hasCatchException = $value;
    }

    public function hasCatchException(): bool
    {
        return ($this->hasCatchException == true);
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

    private function end()
    {
        $action = $this->getAction();

        if (empty($action) || $action === 'index') {
            return $this->callController('index');
        }

        if (!method_exists($this->getController(), $action)) {
            throw new \InvalidArgumentException(
                sprintf('Could not resolve request with this method sent: "%s"',
                    $action)
            );
        }

        $this->callController($action);
    }

    public function handle()
    {
        $extracted       = $this->extractUrl();
        $this->action    = $extracted['action'];
        $this->preAction = $extracted['preAction'];

        if (!$this->hasCatchException()) {
            return $this->end();
        }

        try {
            $this->end();
        } catch (\Throwable $exc) {
            die(
                json_encode([
                    'status' => false,
                    'message' => $exc->getMessage()
                ])
            );
        }
    }

    private function callController(string $method)
    {
        $this
            ->getController()
            ->configure([
                'pre_action' => $this->getPreAction()])
            ->{$method}();
    }

    public function extractUrl(): array
    {
        $uri    = $_SERVER['REQUEST_URI'];
        $levels = explode('/', $uri);

        $action    = $this->defineAction($levels);
        $preAction = $this->definePreAction($levels);

        if ($preAction == 'public') {
            $preAction = $action;
            $action    = 'index';
        }

        return compact('action', 'preAction');
    }

    private function definePreAction(array $levels): string
    {
        $total     = count($levels);
        $preAction = $levels[$total - 2];

        return $preAction;
    }

    private function defineAction(array $levels): string
    {
        $total  = count($levels);
        $action = $levels[$total - 1];

        $pos = strpos($action, '?');
        if ($pos !== false) {
            $action = substr($action, 0, $pos);
        }

        return $action;
    }
}