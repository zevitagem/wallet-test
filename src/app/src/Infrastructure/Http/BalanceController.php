<?php

namespace App\Infrastructure\Http;

use App\Infrastructure\Http\BaseController;
use App\Application\Services\AccountService;

class BalanceController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->setService(new AccountService());
    }

    public function index()
    {
        $this->mustGet();

        $result = $this->getService()->find($_GET['account_id'] ?? 0);

        $this->output->header(200);
        echo $result->getBalance();
    }
}