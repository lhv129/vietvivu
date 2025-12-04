<?php

namespace App\Services;

use App\Repositories\StartLocationRepository;

class StartLocationService extends BaseService
{
    public function repository()
    {
        return StartLocationRepository::class;
    }
}
