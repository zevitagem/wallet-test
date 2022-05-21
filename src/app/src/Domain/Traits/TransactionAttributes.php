<?php

namespace App\Domain\Traits;

trait TransactionAttributes
{
    public string $type;
    public ?int $origin = null;
    public ?int $destination = null;
    public int $amount;

    public function getOrigin(): ?int
    {
        return $this->origin;
    }

    public function getDestination(): ?int
    {
        return $this->destination;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function isTransfer(): bool
    {
        return ($this->getType() == 'transfer');
    }

    public function isDeposit(): bool
    {
        return ($this->getType() == 'deposit');
    }

    public function isWithdraw(): bool
    {
        return ($this->getType() == 'withdraw');
    }
}