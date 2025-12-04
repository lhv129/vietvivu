<?php

namespace App\Repositories;

use App\Models\TourDayLocation;

class TourDayLocationRepository extends BaseRepository{
    public function model()
    {
        return TourDayLocation::class;
    }
}