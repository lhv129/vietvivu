<?php

namespace App\Services;

use App\Repositories\LocationRepository;

class LocationService extends BaseService
{
    public function repository()
    {
        return LocationRepository::class;
    }
}
