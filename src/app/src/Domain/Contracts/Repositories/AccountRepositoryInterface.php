<?php
declare(strict_types=1);

namespace App\Domain\Contracts\Repositories;

use App\Domain\Entity\Account;

interface AccountRepositoryInterface
{
    public function getAccount(int $id);

    public function storeAccount(Account $account);

    public function updateAccount(Account $account);
}