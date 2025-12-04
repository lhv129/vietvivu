<?php

namespace App\Repositories;

use App\Models\StartLocation;

class StartLocationRepository extends BaseRepository
{
    public function model()
    {
        return StartLocation::class;
    }
}
