<?php

namespace App\Application\Traits;

use App\Application\Services\BaseService;

trait AvailabilityWithService
{
    private BaseService $service;

    protected function setService(BaseService $service)
    {
        $this->service = $service;
    }

    public function getService()
    {
        return $this->service;
    }
}