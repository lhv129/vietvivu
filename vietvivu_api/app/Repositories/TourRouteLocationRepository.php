<?php

namespace App\Repositories;

use App\Models\TourRouteLocation;

class TourRouteLocationRepository extends BaseRepository
{
    public function model()
    {
        return TourRouteLocation::class;
    }

    public function storeTourRouteLocations($tourId, array $locations)
    {
        foreach ($locations as $loc) {
            $this->model::create([
                'tour_id' => $tourId,
                'location_id' => $loc,
            ]);
        }
    }
}
