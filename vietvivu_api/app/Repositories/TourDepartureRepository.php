<?php

namespace App\Repositories;

use App\Models\TourDeparture;
use App\Repositories\BaseRepository;

class TourDepartureRepository extends BaseRepository
{
    public function model()
    {
        return TourDeparture::class;
    }

    public function storeDepartures($tourId, array $departures)
    {
        foreach ($departures as $d) {
            $this->model::create([
                'tour_id' => $tourId,
                'start_date' => $d['start_date'],
                'available_seats' => $d['available_seats'],
                'booked_seats' => 0,
                'price_adult' => $d['price_adult'],
                'price_child' => $d['price_child'] ?? null,
                'discount_percent' => $d['discount_percent'] ?? null,
                'discount_amount' => $d['discount_amount'] ?? null,
            ]);
        }
    }
}
