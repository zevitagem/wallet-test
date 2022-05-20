<?php

namespace App\Application\Validators;

use App\Application\Contracts\DTOInterface;

class BaseValidator
{
    private DTOInterface $data;
    private string $method;
    private array $errors     = [];
    /* -- */
    protected array $messages = [];

    public function setData(DTOInterface $data): self
    {
        $this->data = $data;
        return $this;
    }

    protected function addError(string $key, array $data = []): void
    {
        $this->errors[] = vsprintf($this->messages[$key], $data);
    }

    private function setMethod(string $method): void
    {
        $this->method = $method;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return (!empty($this->getErrors()));
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getData(): DTOInterface
    {
        return $this->data;
    }

    public function run(string $method = ''): null|bool
    {
        if (!method_exists($this, $method)) {
            return null;
        }

        $this->setMethod($method);
        $this->{$method}();

        return (!$this->hasErrors());
    }

    public function translate(): string
    {
        // case json, do x
        // case html, do y

        return json_encode($this->errors);
    }
}