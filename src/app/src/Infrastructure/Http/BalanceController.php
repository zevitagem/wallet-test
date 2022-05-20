<?php

namespace App\Infrastructure\Http;

use App\Infrastructure\Http\BaseController;
use App\Application\Services\AccountService;

class BalanceController extends BaseController
{
    public function __construct()
    {
        $this->setService(new AccountService());
    }

    public function index()
    {
        $this->mustGet();

        $this->getService()->find($_GET['account_id'] ?? 0);
    }

    public function handleGet()
    {
        $this->mustGet();

        echo 'balance get';
    }

    public function handlePost()
    {
        $this->mustPost();

        $this->getService()->store($_POST);
    }
}