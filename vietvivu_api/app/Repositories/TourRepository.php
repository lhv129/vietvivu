<?php

namespace App\Repositories;

use App\Models\Tour;

class TourRepository extends BaseRepository
{
    public function model()
    {
        return Tour::class;
    }
}