<?php

namespace App\Domain\UseCases;

final class UseCaseResponse
{
    public function __construct(private bool $status, private mixed $content)
    {
    }

    public function getContent(): mixed
    {
        return $this->content;
    }

    public function isSuccessfully(): bool
    {
        return ($this->status == true);
    }
}